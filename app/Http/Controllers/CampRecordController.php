<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CampRecord;

class CampRecordController extends Controller
{
    private function authorizeFinanceAccess($action = 'view')
    {
        $currentUser = auth()->user();
        if (!$currentUser || !$currentUser->hasFinancePermission($action)) {
            abort(403, 'Unauthorized access: You do not have permission to access the finances section.');
        }
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('camp_name', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('rm', 'like', "%{$s}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }
        if ($request->filled('rm')) {
            $query->where('rm', 'like', "%{$request->rm}%");
        }
        if ($request->filled('doctor')) {
            $query->where('doctor_name', 'like', "%{$request->doctor}%");
        }
        if ($request->filled('profit_status')) {
            if ($request->profit_status === 'profit') {
                $query->where('net_profit_loss', '>=', 0);
            } elseif ($request->profit_status === 'loss') {
                $query->where('net_profit_loss', '<', 0);
            }
        }
        if ($request->filled('min_patients')) {
            $query->where('patients_count', '>=', $request->min_patients);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess('view');
        $query = $this->applyFilters(CampRecord::query(), $request);
        $records = $query->latest()->get();

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('camp_records.partials.table', compact('records'))->render(),
                'pagination_html' => '',
                'total' => $records->count(),
                'stats' => [
                    'count' => $records->count(),
                    'net_profit' => $records->sum('profit') - $records->sum('expenses'),
                ],
            ]);
        }

        return view('camp_records.index', compact('records'));
    }

    public function export(Request $request)
    {
        $this->authorizeFinanceAccess('view');
        $query = $this->applyFilters(CampRecord::query(), $request);
        $records = $query->latest()->get();

        $filename = 'camp_records_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($file, [
                'Date',
                'Camp Name',
                'Location',
                'RM',
                'Doctor',
                'Pathologist',
                'Pharmacist',
                'Patients',
                'Medicine MRP',
                'Discounted Prize',
                'Total Discount',
                'Buying %',
                'Gross Profit',
                'Expenses',
                'Net Profit/Loss',
                'Expense Details',
            ]);

            foreach ($records as $r) {
                // Build expense details string
                $expenseDetails = '';
                if ($r->expense_details && is_array($r->expense_details)) {
                    $items = [];
                    foreach ($r->expense_details as $detail) {
                        $cat = $detail['category'] ?? 'N/A';
                        $amt = number_format($detail['amount'] ?? 0, 2);
                        $note = !empty($detail['note']) ? " ({$detail['note']})" : '';
                        $items[] = "{$cat}: Rs.{$amt}{$note}";
                    }
                    $expenseDetails = implode(' | ', $items);
                }

                fputcsv($file, [
                    $r->date ? \Carbon\Carbon::parse($r->date)->format('d-m-Y') : '',
                    $r->camp_name ?? '',
                    $r->location ?? '',
                    $r->rm ?? '',
                    $r->doctor_name ?? '',
                    $r->pathologist ?? '',
                    $r->pharmacists_name ?? '',
                    $r->patients_count ?? 0,
                    number_format($r->medicine_mrp ?? 0, 2, '.', ''),
                    number_format($r->medicine_discount ?? 0, 2, '.', ''),
                    number_format($r->total_discount ?? 0, 2, '.', ''),
                    number_format($r->buying_percentage ?? 0, 2, '.', '') . '%',
                    number_format($r->profit ?? 0, 2, '.', ''),
                    number_format($r->expenses ?? 0, 2, '.', ''),
                    number_format($r->net_profit_loss ?? 0, 2, '.', ''),
                    $expenseDetails,
                ]);
            }

            fclose($file);
        };

    }

    public function exportPdf(Request $request, CampRecord $campRecord)
    {
        $this->authorizeFinanceAccess('view');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('camp_records.pdf', compact('campRecord'));

        $filename = 'camp_record_' . $campRecord->id . '_' . now()->format('Ymd_His') . '.pdf';

        if ($request->has('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function create()
    {
        $this->authorizeFinanceAccess('edit');
        $rms = \App\Models\User::where('designation', 'rm')->with('profile')->get();
        return view('camp_records.create', compact('rms'));
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess('edit');

        $validated = $request->validate([
            'camp_name' => 'required|string|max:255',
            'camp_type' => 'required|in:travel_allowance,doctor_appointment',
            'location' => 'nullable|string|max:255',
            'rm' => 'nullable|string|max:255',
            'date' => 'required|date',
            'patients_count' => 'nullable|integer',
            'medicine_mrp' => 'nullable|numeric',
            'medicine_discount' => 'nullable|numeric',
            'total_discount' => 'nullable|numeric',
            'buying_percentage' => 'nullable|numeric',
            'doctor_appointment_fees' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'doctor_name' => 'nullable|string|max:255',
            'pathologist' => 'nullable|string|max:255',
            'pharmacists_name' => 'nullable|string|max:255',
            'expenses' => 'nullable|numeric',
            'expense_details' => 'nullable|array',
            'net_profit_loss' => 'nullable|numeric',
        ]);

        // Server-side recalculation for data integrity
        $mrp = floatval($validated['medicine_mrp'] ?? 0);
        $dPrize = floatval($validated['medicine_discount'] ?? 0);
        $bPerc = floatval($validated['buying_percentage'] ?? 0);

        $validated['total_discount'] = max(0, $mrp - $dPrize);
        $cost = $mrp * ($bPerc / 100);
        $grossProfit = $dPrize - $cost;

        // If doctor appointment based, add (patients × doctor fees) to gross profit
        if (($validated['camp_type'] ?? 'travel_allowance') === 'doctor_appointment') {
            $patients = intval($validated['patients_count'] ?? 0);
            $doctorFees = floatval($validated['doctor_appointment_fees'] ?? 0);
            $grossProfit += ($patients * $doctorFees);
        }

        $validated['profit'] = round($grossProfit, 2);

        // Sum expense details
        $totalExpenses = 0;
        if (!empty($validated['expense_details']) && is_array($validated['expense_details'])) {
            foreach ($validated['expense_details'] as $detail) {
                $totalExpenses += floatval($detail['amount'] ?? 0);
            }
        }
        $validated['expenses'] = round($totalExpenses, 2);
        $validated['net_profit_loss'] = round($grossProfit - $totalExpenses, 2);

        CampRecord::create($validated);

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record created successfully.');
    }

    public function edit(CampRecord $campRecord)
    {
        $this->authorizeFinanceAccess('edit');
        $rms = \App\Models\User::where('designation', 'rm')->with('profile')->get();
        return view('camp_records.edit', compact('campRecord', 'rms'));
    }

    public function update(Request $request, CampRecord $campRecord)
    {
        $this->authorizeFinanceAccess('edit');

        $validated = $request->validate([
            'camp_name' => 'required|string|max:255',
            'camp_type' => 'required|in:travel_allowance,doctor_appointment',
            'location' => 'nullable|string|max:255',
            'rm' => 'nullable|string|max:255',
            'date' => 'required|date',
            'patients_count' => 'nullable|integer',
            'medicine_mrp' => 'nullable|numeric',
            'medicine_discount' => 'nullable|numeric',
            'total_discount' => 'nullable|numeric',
            'buying_percentage' => 'nullable|numeric',
            'doctor_appointment_fees' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'doctor_name' => 'nullable|string|max:255',
            'pathologist' => 'nullable|string|max:255',
            'pharmacists_name' => 'nullable|string|max:255',
            'expenses' => 'nullable|numeric',
            'expense_details' => 'nullable|array',
            'net_profit_loss' => 'nullable|numeric',
        ]);

        // Server-side recalculation for data integrity
        $mrp = floatval($validated['medicine_mrp'] ?? 0);
        $dPrize = floatval($validated['medicine_discount'] ?? 0);
        $bPerc = floatval($validated['buying_percentage'] ?? 0);

        $validated['total_discount'] = max(0, $mrp - $dPrize);
        $cost = $mrp * ($bPerc / 100);
        $grossProfit = $dPrize - $cost;

        // If doctor appointment based, add (patients × doctor fees) to gross profit
        if (($validated['camp_type'] ?? 'travel_allowance') === 'doctor_appointment') {
            $patients = intval($validated['patients_count'] ?? 0);
            $doctorFees = floatval($validated['doctor_appointment_fees'] ?? 0);
            $grossProfit += ($patients * $doctorFees);
        }

        $validated['profit'] = round($grossProfit, 2);

        // Sum expense details
        $totalExpenses = 0;
        if (!empty($validated['expense_details']) && is_array($validated['expense_details'])) {
            foreach ($validated['expense_details'] as $detail) {
                $totalExpenses += floatval($detail['amount'] ?? 0);
            }
        }
        $validated['expenses'] = round($totalExpenses, 2);
        $validated['net_profit_loss'] = round($grossProfit - $totalExpenses, 2);

        $campRecord->update($validated);

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record updated successfully.');
    }

    public function destroy(CampRecord $campRecord)
    {
        $this->authorizeFinanceAccess('edit');
        $campRecord->delete();

        return redirect()->route('camp_records.index')
            ->with('success', 'Camp record deleted successfully.');
    }
}
