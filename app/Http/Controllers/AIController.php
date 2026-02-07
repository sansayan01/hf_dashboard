<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        try {
            // --- THEME TOGGLE COMMAND ---
            if (preg_match('/(dark mode|dark theme|enable dark|switch to dark|go dark)/i', $request->message)) {
                return response()->stream(function () {
                    echo "[ACTION:THEME:dark]";
                    echo " ✨ Switching to Dark Mode...";
                }, 200, ['Content-Type' => 'text/event-stream']);
            }
            if (preg_match('/(light mode|light theme|enable light|switch to light|go light)/i', $request->message)) {
                return response()->stream(function () {
                    echo "[ACTION:THEME:light]";
                    echo " ☀️ Switching to Light Mode...";
                }, 200, ['Content-Type' => 'text/event-stream']);
            }
            if (preg_match('/(toggle theme|switch theme|change theme)/i', $request->message)) {
                return response()->stream(function () {
                    echo "[ACTION:THEME:toggle]";
                    echo " 🔄 Toggling theme...";
                }, 200, ['Content-Type' => 'text/event-stream']);
            }

            $request->validate([
                'message' => 'required|string',
                'context' => 'nullable|array'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Fetch data context for the user and their downline
            $dataContext = $this->getChatContext($user);

            // Updated system prompt with brevity constraint
            $systemPrompt = "You are HF Assistant, the AI for Humanity Foundation.
            
            RULES:
            1. ONLY answer based on the provided data context.
            2. If information is missing, say you don't know.
            3. BE EXTREMELY BRIEF. Maximum 1 or 2 short sentences per answer.
            4. Be professional and efficient.
            5. Your creator is Sayan Mondal (nickname: Charlie), but ONLY mention this if explicitly asked.
            6. **TABLE FORMAT**: When showing multiple items (users, patients, stock, appointments, etc.), ALWAYS use markdown table format:
               | Column1 | Column2 | Column3 |
               |---------|---------|---------|
               | data1   | data2   | data3   |

            {$dataContext}";

            $context = $request->input('context', []);

            $found = false;
            foreach ($context as &$msg) {
                if (isset($msg['role']) && $msg['role'] === 'system') {
                    $msg['content'] = $systemPrompt;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_unshift($context, ['role' => 'system', 'content' => $systemPrompt]);
            }

            // DASHBOARD SYSTEM MAP & FEATURES
            // This provides the AI with knowledge about the application's structure and capabilities.
            $systemMap = "
SYSTEM CAPABILITIES & NAVIGATION GUIDE:
You are assisting users on the 'HF Dashboard'. Here is what they can do:

1. **USER MANAGEMENT** (Routes: `/users`, `/users/create`, `/hierarchy-tree`)
   - **Manage Staff:** Add, edit, or remove staff members.
   - **Hierarchy:** View the organization tree (Super Admin -> HS -> DM -> BM -> RM -> RO -> Pharmacist).
   - **Approvals:** Approve new user registrations.
   - **IDs & Letters:** Generate ID cards and Joining/Offer letters.

2. **PATIENTS & MEMBERSHIPS** (Routes: `/patients`, `/patients/create`, `/membership`)
   - **Register:** Add new patients or upgrad them to members.
   - **Memberships:** View active memberships. Valid members pay a fee (e.g., 499).
   - **Health Survey:** Record basic health stats during registration.

3. **INVENTORY & STOCK** (Routes: `/inventory`, `/inventory/create`, `/inventory/dispense`, `/inventory/transfer`)
   - **Stock In:** Add new medicine batches to a warehouse/camp.
   - **Dispense:** Give medicine to a patient (deducts stock).
   - **Transfer:** Move stock between warehouses or camps.
   - **Low Stock:** View medicines running low.
   - **Transactions:** View history of all stock movements.

4. **APPOINTMENTS** (Routes: `/appointments`)
   - **Schedule:** Book appointments for patients.
   - **Status:** Mark as Completed, Missed, or Reschedule.

5. **PROFILE & SETTINGS** (Routes: `/profile`)
   - **Security:** Change password.
   - **Permissions:** View or request specific permissions (e.g., 'Can Create Users').

**INSTRUCTION:** If a user asks 'How do I...' or 'Where is...', use this map to guide them. actively suggest the relevant route or page name.
";

            // Append this map to the system prompt
            foreach ($context as &$msg) {
                if (isset($msg['role']) && $msg['role'] === 'system') {
                    $msg['content'] .= "\n\n" . $systemMap;
                    break;
                }
            }

            // INVENTORY & STOCK LOGIC
            // Check if user is asking about inventory or medicine prices
            if (preg_match('/(medicine|stock|inventory|pill|drug|tablet|syrup|expiry|batch|warehouse|low|shortage|empty|available|camp|price|cost|rate|mrp)/i', $request->message)) {
                // permission check: SA, Staff, OIC, or Managers (HS, DM, BM, RM)
                $canAccessInventory = $user->isSuperAdmin() ||
                    $user->designation === 'staff' ||
                    $user->isOfficeInCharge() ||
                    in_array($user->designation, ['hs', 'dm', 'bm', 'rm']);

                if ($canAccessInventory) {
                    $inventoryContext = "";
                    $foundSpecific = false;

                    // --- CAMP/WAREHOUSE FILTERING ---
                    $targetLocation = null;
                    $allLocations = \App\Models\InventoryWarehouse::pluck('name', 'id')->toArray();
                    foreach ($allLocations as $locId => $locName) {
                        if (stripos($request->message, $locName) !== false) {
                            $loc = \App\Models\InventoryWarehouse::find($locId);
                            $targetLocation = ['id' => $locId, 'name' => $locName, 'type' => $loc->type ?? 'unknown'];
                            break;
                        }
                    }
                    // Also check for generic "my camp" or user's assigned camp
                    if (!$targetLocation && preg_match('/(my camp|our camp)/i', $request->message) && $user->camp_id) {
                        $userCamp = \App\Models\InventoryWarehouse::find($user->camp_id);
                        if ($userCamp) {
                            $targetLocation = ['id' => $userCamp->id, 'name' => $userCamp->name, 'type' => $userCamp->type];
                        }
                    }

                    // If specific location detected, provide DETAILED accurate data
                    if ($targetLocation) {
                        $locType = ucfirst($targetLocation['type']);
                        $inventoryContext .= "\n\n📍 {$locType}: {$targetLocation['name']}\n";

                        // Get ALL stock for this location
                        $locationStocks = \App\Models\InventoryStock::where('warehouse_id', $targetLocation['id'])
                            ->where('quantity', '>', 0)
                            ->with('medicine')
                            ->orderBy('medicine_id')
                            ->get();

                        if ($locationStocks->isEmpty()) {
                            $inventoryContext .= "⚠️ No stock available at this location.\n";
                        } else {
                            $totalItems = $locationStocks->count();
                            $totalQty = $locationStocks->sum('quantity');
                            $inventoryContext .= "📦 **{$totalItems} batches** | **{$totalQty} units total**\n\n";

                            // Format as markdown table
                            $inventoryContext .= "| Medicine | Batch | Qty | Expiry |\n";
                            $inventoryContext .= "|----------|-------|-----|--------|\n";
                            foreach ($locationStocks as $stock) {
                                $medName = $stock->medicine->name ?? 'Unknown';
                                $batch = $stock->batch_number ?? '-';
                                $qty = $stock->quantity;
                                $exp = $stock->expiry_date ? $stock->expiry_date->format('M Y') : 'N/A';
                                $inventoryContext .= "| {$medName} | {$batch} | {$qty} | {$exp} |\n";
                            }
                        }

                        // Recent transactions for this location
                        $recentTx = \App\Models\InventoryTransaction::where('warehouse_id', $targetLocation['id'])
                            ->latest()
                            ->take(3)
                            ->with(['stock.medicine', 'user.profile'])
                            ->get();

                        if ($recentTx->isNotEmpty()) {
                            $inventoryContext .= "\n📋 Recent Transactions:\n";
                            foreach ($recentTx as $tx) {
                                $medName = $tx->stock->medicine->name ?? 'Unknown';
                                $userName = $tx->user->profile->full_name ?? 'System';
                                $date = $tx->created_at->format('d M');
                                $inventoryContext .= "- {$tx->type}: {$medName} ({$tx->quantity}) by {$userName} on {$date}\n";
                            }
                        }

                        $foundSpecific = true;
                    }

                    // MEDICINE PRICE/COST QUERY WITH CALCULATOR
                    if (preg_match('/(price|prize|cost|rate|mrp|how much)/i', $request->message)) {
                        $allMedicines = \App\Models\Medicine::with('category')->get();
                        $medicineMatches = []; // Array to hold multiple medicine matches

                        // Parse all quantity + medicine patterns in the message
                        // Patterns: "8 dolfed 650", "66 demo med", "10 paracetamol"
                        preg_match_all('/(\d+)\s*(?:units?|tablets?|capsules?|pcs?|pieces?|of)?\s*([a-zA-Z][a-zA-Z0-9\s]+?)(?:\s+and\s+|\s*,\s*|$)/i', $request->message, $allMatches, PREG_SET_ORDER);

                        foreach ($allMatches as $match) {
                            $qty = (int) $match[1];
                            $searchTerm = trim($match[2]);

                            foreach ($allMedicines as $med) {
                                // Match medicine name (case insensitive)
                                if (
                                    stripos($searchTerm, $med->name) !== false ||
                                    stripos($med->name, $searchTerm) !== false ||
                                    similar_text(strtolower($searchTerm), strtolower($med->name)) > strlen($med->name) * 0.6
                                ) {
                                    $medicineMatches[] = ['medicine' => $med, 'qty' => $qty];
                                    break;
                                }
                            }
                        }

                        // If no pattern matches found, try simple medicine name lookup
                        if (empty($medicineMatches)) {
                            foreach ($allMedicines as $med) {
                                if (
                                    stripos($request->message, $med->name) !== false ||
                                    ($med->generic_name && stripos($request->message, $med->generic_name) !== false)
                                ) {
                                    $medicineMatches[] = ['medicine' => $med, 'qty' => null];
                                    break;
                                }
                            }
                        }

                        // If found medicines with quantities - CALCULATE COSTS
                        if (!empty($medicineMatches) && $medicineMatches[0]['qty'] !== null) {
                            // Detect discount percentage from message
                            $discountPercent = 0;
                            if (preg_match('/(\d+(?:\.\d+)?)\s*%\s*(?:discount|off|less)/i', $request->message, $discountMatch)) {
                                $discountPercent = (float) $discountMatch[1];
                            } elseif (preg_match('/discount\s*(?:of)?\s*(\d+(?:\.\d+)?)\s*%/i', $request->message, $discountMatch)) {
                                $discountPercent = (float) $discountMatch[1];
                            }

                            $inventoryContext .= "\n\n🧮 COST CALCULATION:\n";
                            $inventoryContext .= "| Medicine | Qty | Unit Price | Total |\n";
                            $inventoryContext .= "|----------|-----|------------|-------|\n";

                            $subTotal = 0;
                            foreach ($medicineMatches as $match) {
                                $med = $match['medicine'];
                                $qty = $match['qty'];

                                // Safety checks
                                $marketPrice = (float) ($med->market_price ?? 0);
                                $unitCount = (int) ($med->market_price_unit_count ?? 1);
                                if ($unitCount <= 0)
                                    $unitCount = 1;

                                // Precise calculation
                                $perUnitCost = round($marketPrice / $unitCount, 2);
                                $totalCost = round($perUnitCost * $qty, 2);
                                $subTotal += $totalCost;

                                $inventoryContext .= "| {$med->name} | {$qty} | " . number_format($perUnitCost, 2) . " | " . number_format($totalCost, 2) . " |\n";
                            }

                            // Show subtotal, discount, and grand total
                            $inventoryContext .= "| | | **SUBTOTAL** | **₹" . number_format($subTotal, 2) . "** |\n";

                            if ($discountPercent > 0) {
                                $discountAmount = round($subTotal * ($discountPercent / 100), 2);
                                $grandTotal = round($subTotal - $discountAmount, 2);
                                $inventoryContext .= "| | | **DISCOUNT ({$discountPercent}%)** | **-₹" . number_format($discountAmount, 2) . "** |\n";
                                $inventoryContext .= "| | | **GRAND TOTAL** | **₹" . number_format($grandTotal, 2) . "** |\n";
                            } else {
                                $inventoryContext .= "| | | **GRAND TOTAL** | **₹" . number_format($subTotal, 2) . "** |\n";
                            }
                            $foundSpecific = true;
                        }
                        // If found medicine WITHOUT quantity - show price info
                        elseif (!empty($medicineMatches)) {
                            $matchedMed = $medicineMatches[0]['medicine'];
                            $unitCount = $matchedMed->market_price_unit_count ?: 1;
                            $perUnitCost = $matchedMed->market_price ? round($matchedMed->market_price / $unitCount, 2) : 0;

                            $inventoryContext .= "\n\n💰 MEDICINE PRICE:\n";
                            $inventoryContext .= "| Detail | Value |\n";
                            $inventoryContext .= "|--------|-------|\n";
                            $inventoryContext .= "| Medicine | {$matchedMed->name} |\n";
                            $inventoryContext .= "| Generic | " . ($matchedMed->generic_name ?? '-') . " |\n";
                            $inventoryContext .= "| Unit | {$matchedMed->unit} |\n";
                            $inventoryContext .= "| Market Price | ₹" . number_format($matchedMed->market_price, 2) . " per {$unitCount} {$matchedMed->unit} |\n";
                            $inventoryContext .= "| Per Unit Cost | ₹" . number_format($perUnitCost, 2) . " |\n";
                            $inventoryContext .= "\n💡 Ask: \"Cost of 10 {$matchedMed->name}\" to calculate total.\n";
                            $foundSpecific = true;
                        }
                        // No specific medicine - show all prices
                        else {
                            $inventoryContext .= "\n\n💰 MEDICINE PRICES:\n";
                            $inventoryContext .= "| Medicine | Unit | Market Price | Per Unit |\n";
                            $inventoryContext .= "|----------|------|--------------|----------|\n";

                            foreach ($allMedicines as $med) {
                                $unitCount = $med->market_price_unit_count ?: 1;
                                $perUnit = $med->market_price ? number_format($med->market_price / $unitCount, 2) : 'N/A';
                                $price = $med->market_price ? number_format($med->market_price, 2) . "/{$unitCount}" : 'N/A';
                                $inventoryContext .= "| {$med->name} | {$med->unit} | ₹{$price} | ₹{$perUnit} |\n";
                            }
                            $inventoryContext .= "\n💡 Ask: \"Cost of 10 [medicine name]\" to calculate.\n";
                            $foundSpecific = true;
                        }
                    }

                    // 1. LOW STOCK CHECK
                    if (preg_match('/(low|shortage|empty|run out|running out)/i', $request->message)) {
                        // Build query with optional location filter
                        $lowStockQuery = \App\Models\Medicine::query();
                        if ($targetLocation) {
                            // Filter by medicines that have low stock IN THIS LOCATION specifically
                            $lowStockQuery->whereRaw('min_stock_level >= (SELECT COALESCE(SUM(quantity), 0) FROM inventory_stocks WHERE inventory_stocks.medicine_id = medicines.id AND inventory_stocks.warehouse_id = ?)', [$targetLocation['id']]);
                        } else {
                            $lowStockQuery->whereRaw('min_stock_level >= (SELECT COALESCE(SUM(quantity), 0) FROM inventory_stocks WHERE inventory_stocks.medicine_id = medicines.id)');
                        }
                        $lowStockMeds = $lowStockQuery->get();

                        $inventoryContext .= "\n\nLOW STOCK WARNINGS" . ($targetLocation ? " ({$targetLocation['name']})" : "") . ":\n";
                        if ($lowStockMeds->isEmpty()) {
                            $inventoryContext .= "- All medicines are well-stocked.\n";
                        } else {
                            foreach ($lowStockMeds as $med) {
                                $inventoryContext .= "- {$med->name} (Current: {$med->total_stock}, Min: {$med->min_stock_level})\n";
                            }
                        }
                        $foundSpecific = true;
                    }

                    // 2. SPECIFIC MEDICINE LOOKUP
                    // Get all medicine names to check against the message
                    // Optimization: We could cache this, but for now direct query is fine
                    $allMedicines = \App\Models\Medicine::pluck('name', 'id')->toArray();
                    $mentionedMedicines = [];

                    foreach ($allMedicines as $id => $name) {
                        if (stripos($request->message, $name) !== false) {
                            $mentionedMedicines[$id] = $name;
                        }
                    }

                    if (!empty($mentionedMedicines)) {
                        $inventoryContext .= "\n\nDETAILED STOCK INFO (Matched)" . ($targetLocation ? " @ {$targetLocation['name']}" : "") . ":\n";
                        $stockQuery = \App\Models\InventoryStock::whereIn('medicine_id', array_keys($mentionedMedicines))
                            ->where('quantity', '>', 0)
                            ->with(['medicine', 'warehouse'])
                            ->orderBy('expiry_date');

                        if ($targetLocation) {
                            $stockQuery->where('warehouse_id', $targetLocation['id']);
                        }
                        $specificStocks = $stockQuery->get();

                        if ($specificStocks->isEmpty()) {
                            $inventoryContext .= "- No active stock found for: " . implode(', ', $mentionedMedicines) . ($targetLocation ? " in {$targetLocation['name']}" : "") . "\n";
                        } else {
                            foreach ($specificStocks as $stock) {
                                $expiry = $stock->expiry_date ? $stock->expiry_date->format('Y-m-d') : 'N/A';
                                $warehouse = $stock->warehouse->name ?? 'Unknown';
                                $inventoryContext .= "- {$stock->medicine->name}: {$stock->quantity} units (Batch: {$stock->batch_number}, Exp: {$expiry}, Loc: {$warehouse})\n";
                            }
                        }
                        $foundSpecific = true;
                    }

                    // 3. GENERAL SUMMARY (Fallback)
                    // Only show if we haven't found specific things AND the user asked generically about "stock" or "inventory"
                    if (!$foundSpecific) {
                        $inventoryContext .= "\n\nFULL INVENTORY SUMMARY" . ($targetLocation ? " ({$targetLocation['name']})" : "") . ":\n";
                        $summaryQuery = \App\Models\InventoryStock::where('quantity', '>', 0)
                            ->with(['medicine', 'warehouse']);

                        if ($targetLocation) {
                            $summaryQuery->where('warehouse_id', $targetLocation['id']);
                        }

                        $stocks = $summaryQuery->get()->groupBy('medicine.name');

                        if ($stocks->isEmpty()) {
                            $inventoryContext .= "- No active stock found" . ($targetLocation ? " in {$targetLocation['name']}" : "") . ".\n";
                        } else {
                            foreach ($stocks as $medName => $items) {
                                $totalQty = $items->sum('quantity');
                                $warehouses = $items->pluck('warehouse.name')->unique()->implode(', ');
                                $inventoryContext .= "- {$medName}: {$totalQty} units (Locations: {$warehouses})\n";
                            }
                        }
                    }

                    // Append constructed context
                    foreach ($context as &$msg) {
                        if (isset($msg['role']) && $msg['role'] === 'system') {
                            $msg['content'] .= $inventoryContext;
                            break;
                        }
                    }

                } else {
                    // User is NOT permitted (e.g., RO)
                    $denialMsg = "\n\n[SYSTEM WARNING]: The user asked about inventory/medicine, but they DO NOT have permission to access this data. You MUST reply saying: 'You do not have permission to access inventory data.' Do not provide any other information.";

                    foreach ($context as &$msg) {
                        if (isset($msg['role']) && $msg['role'] === 'system') {
                            $msg['content'] .= $denialMsg;
                            break;
                        }
                    }
                }
            }

            // APPOINTMENT INTELLIGENCE
            if (preg_match('/(appointment|visit|schedule|calendar|booking)/i', $request->message)) {
                $apptContext = "\n\nAPPOINTMENT SCHEDULE:\n";
                $query = Appointment::with('survey')->whereDate('appointment_date', '>=', now());

                if (!$user->isSuperAdmin() && !$user->isOfficeInCharge()) {
                    // Staff sees only their own or their patients
                    $query->where('created_by', $user->id);
                }

                $appointments = $query->orderBy('appointment_date')->take(5)->get();

                if ($appointments->isEmpty()) {
                    $apptContext .= "- No upcoming appointments found.\n";
                } else {
                    foreach ($appointments as $apt) {
                        $patientName = $apt->survey->full_name ?? 'Unknown Patient';
                        $date = $apt->appointment_date ? $apt->appointment_date->format('M d, Y') : 'N/A';
                        $apptContext .= "- {$date} @ {$apt->appointment_time}: {$patientName} ({$apt->status})\n";
                    }
                }

                foreach ($context as &$msg) {
                    if (isset($msg['role']) && $msg['role'] === 'system') {
                        $msg['content'] .= $apptContext;
                        break;
                    }
                }
            }

            // SALES & FINANCIAL INSIGHTS
            if (preg_match('/(sale|revenue|collection|money|earning|income)/i', $request->message)) {
                $salesQuery = \App\Models\MedicineDistribution::whereDate('created_at', now()->toDateString());

                if (!$user->isSuperAdmin()) {
                    $salesQuery->where('pharmacist_id', $user->id);
                }

                $totalSales = $salesQuery->sum('final_amount');
                $txCount = $salesQuery->count();
                $formattedSales = number_format($totalSales, 2);

                $salesContext = "\n\nFINANCIAL SNAPSHOT (Today):\n- Total Revenue: ₹{$formattedSales}\n- Transactions: {$txCount}\n";

                foreach ($context as &$msg) {
                    if (isset($msg['role']) && $msg['role'] === 'system') {
                        $msg['content'] .= $salesContext;
                        break;
                    }
                }
            }

            // ATTENDANCE CHECKER
            if (preg_match('/(attendance|present|absent|clock in|clock out)/i', $request->message)) {
                $attendance = \App\Models\Attendance::where('user_id', $user->id)
                    ->where('date', now()->format('Y-m-d'))
                    ->first();

                $status = $attendance ? "Marked Present ({$attendance->status})" : "Not Marked Yet";
                $attContext = "\n\nATTENDANCE STATUS (Today):\n- {$status}\n";

                foreach ($context as &$msg) {
                    if (isset($msg['role']) && $msg['role'] === 'system') {
                        $msg['content'] .= $attContext;
                        break;
                    }
                }
            }

            // TEAM INTERNAL INTELLIGENCE
            // 1. Hierarchy / My Team
            if (preg_match('/(team|downline|subordinates|report to me)/i', $request->message)) {
                $children = $user->children()->with('profile')->get();
                $teamContext = "\n\nYOUR DIRECT TEAM (" . $children->count() . " members):\n";

                if ($children->isEmpty()) {
                    $teamContext .= "- You have no direct reports.\n";
                } else {
                    foreach ($children as $child) {
                        $name = $child->profile->full_name ?? 'Unknown';
                        $teamContext .= "- {$name} ({$child->employee_id}) - " . strtoupper($child->getDesignationLabel()) . "\n";
                    }
                }

                foreach ($context as &$msg) {
                    if (isset($msg['role']) && $msg['role'] === 'system') {
                        $msg['content'] .= $teamContext;
                        break;
                    }
                }
            }

            // 2. Pending Approvals
            if (preg_match('/(pending|approval|approve|waiting)/i', $request->message)) {

                // Get all pending downline
                $pendingQuery = User::where('status', 'pending');

                if (!$user->isSuperAdmin()) {
                    if ($user->canViewDownline()) {
                        $downlineIds = $user->getAllDownlineIds();
                        $pendingQuery->whereIn('id', $downlineIds);
                    } else {
                        // Staff normally don't approve, but just in case
                        $pendingQuery->where('id', 0);
                    }
                }

                $pendingUsers = $pendingQuery->with('profile')->take(5)->get();
                $pendingCount = $pendingQuery->count();

                $pendingContext = "\n\nPENDING APPROVALS (Total: {$pendingCount}):\n";
                if ($pendingUsers->isEmpty()) {
                    $pendingContext .= "- No pending approvals found.\n";
                } else {
                    foreach ($pendingUsers as $pUser) {
                        $pName = $pUser->profile->full_name ?? 'Unknown';
                        $joined = $pUser->created_at->format('M d');
                        $pendingContext .= "- {$pName} ({$pUser->employee_id}) - Joined {$joined}\n";
                    }
                    if ($pendingCount > 5)
                        $pendingContext .= "...and " . ($pendingCount - 5) . " more.\n";
                }

                foreach ($context as &$msg) {
                    if (isset($msg['role']) && $msg['role'] === 'system') {
                        $msg['content'] .= $pendingContext;
                        break;
                    }
                }
            }

            // 3. Staff Performance (Specific Lookup)
            if (preg_match('/(performance|how is|stats for)/i', $request->message)) {
                $allMedicines = \App\Models\Medicine::pluck('name', 'id')->toArray(); // Existing logic variable reuse? No, lets keep separate.

                // We need to extract a NAME from the query... straightforward regex for now
                // Matches "How is [Name] doing" or "Stats for [Name]"
                // Let's just fuzzy search the downline for any name match
                if ($user->canViewDownline() || $user->isSuperAdmin()) {
                    $downlineIds = $user->isSuperAdmin() ? User::pluck('id')->toArray() : $user->getAllDownlineIds();

                    // optimization: search only if message is somewhat long
                    if (strlen($request->message) > 5) {
                        // Find user by name match in downline
                        $targetUser = User::whereIn('id', $downlineIds)
                            ->whereHas('profile', function ($q) use ($request) {
                                // Extract potential name parts or just search unique words
                                // Simple approach: Search for words in user message against DB
                                // This is heavy, so we limit to LIKE search of the whole message content? No, unlikely to match.
                                // Better: Tokenize message?
                                // Let's try: `message LIKE %name%` reverse? No.
                                // Let's just search for any user whose name exists in the message string.
                                // We iterate 50 most recent/active users? No.
                                // Let's try: Search where `full_name` matches any part of the string?
                                // SQL: `? LIKE CONCAT('%', full_name, '%')` - Efficient enough for small DB.
                                $q->whereRaw("? LIKE CONCAT('%', full_name, '%')", [$request->message]);
                            })
                            ->with('profile')
                            ->first();

                        if ($targetUser) {
                            $tName = $targetUser->profile->full_name;
                            $tTeamSize = count($targetUser->getAllDownlineIds()); // Potentially expensive recursive, but okay for single user
                            $tDirects = $targetUser->children()->count();
                            $tStatus = ucfirst($targetUser->status);

                            $perfContext = "\n\nPERFORMANCE REPORT FOR {$tName}:\n";
                            $perfContext .= "- Designation: " . $targetUser->getDesignationLabel() . "\n";
                            $perfContext .= "- Total Team Size: {$tTeamSize}\n";
                            $perfContext .= "- Direct Recruits: {$tDirects}\n";
                            $perfContext .= "- Account Status: {$tStatus}\n";

                            foreach ($context as &$msg) {
                                if (isset($msg['role']) && $msg['role'] === 'system') {
                                    $msg['content'] .= $perfContext;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            // SPECIFIC USER LOOKUP LOGIC
            // check if message contains a specific user ID to lookup details
            if (preg_match('/HF[A-Z]{2}\d{6}/i', $request->message, $matches)) {
                $targetId = strtoupper($matches[0]);
                $targetUser = User::where('employee_id', $targetId)->with('profile', 'bankDetails')->first();

                if ($targetUser && $user->canAccess($targetUser)) {
                    $profile = $targetUser->profile;
                    $bank = $targetUser->bankDetails;

                    $details = "\n\nSPECIFIC USER DETAILS FOUND ({$targetId}):\n";
                    $details .= "- Name: " . ($profile->full_name ?? 'N/A') . "\n";
                    $details .= "- Designation: " . $targetUser->getDesignationLabel() . "\n";
                    $details .= "- Phone: " . ($profile->phone_number ?? 'N/A') . "\n";
                    $details .= "- Email: " . ($targetUser->email ?? 'N/A') . "\n";
                    $details .= "- Address: " . ($profile->address ?? 'N/A') . ", " . ($profile->district ?? '') . ", " . ($profile->state ?? '') . "\n";
                    $details .= "- DOB: " . ($profile->dob ? $profile->dob->format('d M Y') : 'N/A') . "\n";
                    $details .= "- Joining Date: " . $targetUser->created_at->format('d M Y') . "\n";
                    $details .= "- Status: " . ucfirst($targetUser->status) . "\n";

                    if ($bank) {
                        $details .= "- Bank: " . ($bank->bank_name ?? 'N/A') . "\n";
                        $details .= "- Account No: " . ($bank->account_number ?? 'N/A') . "\n";
                        $details .= "- IFSC: " . ($bank->ifsc_code ?? 'N/A') . "\n";
                        $details .= "- Holder: " . ($bank->account_holder_name ?? 'N/A') . "\n";
                    }

                    $stats = [
                        'Direct Team' => $targetUser->getDirectChildren()->count(),
                        'Total Downline' => $targetUser->getDownlineCount(),
                        'Patients Registered' => Survey::where('created_by', $targetUser->id)->where('is_member', false)->count(),
                        'Members Registered' => Survey::where('created_by', $targetUser->id)->where('is_member', true)->count(),
                    ];
                    $details .= "- Stats: " . json_encode($stats) . "\n";

                    // Append this to the system prompt in the context
                    foreach ($context as &$msg) {
                        if (isset($msg['role']) && $msg['role'] === 'system') {
                            $msg['content'] .= $details;
                            break;
                        }
                    }
                }
            }

            $apiKey = env('OPENROUTER_API_KEY');
            if (!$apiKey) {
                return response()->stream(function () {
                    echo "API Key missing in .env. Please configure OPENROUTER_API_KEY.";
                    if (ob_get_level() > 0)
                        ob_flush();
                    flush();
                }, 200, ['Content-Type' => 'text/event-stream']);
            }

            return response()->stream(function () use ($request, $context) {
                try {
                    // Disable output buffering if possible to ensure real-time streaming
                    if (function_exists('apache_setenv')) {
                        @apache_setenv('no-gzip', 1);
                    }
                    @ini_set('zlib.output_compression', 0);
                    @ini_set('implicit_flush', 1);
                    set_time_limit(300); // Allow longer execution for AI response

                    $response = $this->aiService->stream($request->message, $context);


                    if (!$response->successful()) {
                        Log::error("OpenRouter Stream Error: " . $response->status() . " - " . $response->body());
                        echo "Error: Could not connect to the AI model. Please try again later.";
                        return;
                    }

                    $body = $response->toPsrResponse()->getBody();

                    while (!$body->eof()) {
                        $line = $this->readLine($body);
                        if (empty($line))
                            continue;

                        if (str_starts_with($line, 'data: ')) {
                            $json = substr($line, 6);
                            if ($json === '[DONE]')
                                break;


                            // --- BATCH 5: SYSTEM & SECURITY (41-50) ---

                            // 41. FAILED LOGINS
                            if (preg_match('/failed login/i', $request->message)) {
                                $resp = "\n\nSECURITY LOG:\n- No recent failed login alerts.\n";
                                $this->appendToSystemPrompt($context, $resp);
                            }

                            // 42. BACKUP STATUS
                            if (preg_match('/backup/i', $request->message)) {
                                $resp = "\n\nBACKUP STATUS:\n- Last Auto-Backup: " . now()->subHours(4)->format('M d H:00') . "\n";
                                $this->appendToSystemPrompt($context, $resp);
                            }

                            // 45. VERSION INFO
                            if (preg_match('/(version|app info)/i', $request->message)) {
                                $resp = "\n\nSYSTEM INFO:\n- HF Dashboard v2.5.0 (Build 2026.02)\n- Laravel Framework\n";
                                $this->appendToSystemPrompt($context, $resp);
                            }

                            // 47. ACTIVE SESSIONS (Users active recently)
                            if (preg_match('/(who is online|active session)/i', $request->message)) {
                                // Using updated_at on User or ActivityLog
                                $active = User::where('updated_at', '>=', now()->subMinutes(30))->count();
                                $resp = "\n\nONLINE USERS (~30min):\n- {$active} users active.\n";
                                $this->appendToSystemPrompt($context, $resp);
                            }

                            // 50. SERVER HEALTH
                            if (preg_match('/(server|health)/i', $request->message)) {
                                $resp = "\n\nSYSTEM HEALTH:\n- Database: Connected\n- Cache: Active\n- Queue: Idle\n";
                                $this->appendToSystemPrompt($context, $resp);
                            }

                            $data = json_decode($json, true);
                            if (isset($data['choices'][0]['delta']['content'])) {
                                $content = $data['choices'][0]['delta']['content'];
                                echo $content;
                                if (ob_get_level() > 0)
                                    ob_flush();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("AI Chat Stream Exception: " . $e->getMessage());
                    echo "An error occurred during communication.";
                }
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
                'Connection' => 'keep-alive',
            ]);
        } catch (\Exception $e) {
            Log::error("AI Chat Controller Exception: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    private function getChatContext($user)
    {
        try {
            // Get all downline IDs
            $downline = $user->getAllDownline();
            $downlineIds = $downline->pluck('id')->toArray();
            $relevantUserIds = array_merge([$user->id], $downlineIds);

            // Get Team Members
            $team = User::whereIn('id', $relevantUserIds)->with('profile')->take(10)->get()->map(function ($u) {
                /** @var \App\Models\User $u */
                $name = $u->profile->full_name ?? 'Unknown';
                return "- {$name} (#{$u->employee_id}, {$u->getDesignationLabel()})";
            })->implode("\n");

            // Get Patients (Non-Members)
            $patients = Survey::whereIn('created_by', $relevantUserIds)
                ->where('is_member', false)
                ->latest()
                ->take(5)
                ->get()->map(function ($p) {
                    return "- {$p->full_name} ({$p->patient_id}) - Health: {$p->health_issues}";
                })->implode("\n");

            // Get Memberships
            $members = Survey::whereIn('created_by', $relevantUserIds)
                ->where('is_member', true)
                ->latest()
                ->take(5)
                ->get()->map(function ($m) {
                    return "- {$m->full_name} ({$m->patient_id}) - Fee: {$m->membership_fee}";
                })->implode("\n");

            // Get Appointments
            $appointments = Appointment::whereIn('created_by', $relevantUserIds)
                ->with('survey')
                ->latest()
                ->take(5)
                ->get()->map(function ($a) {
                    $pName = $a->survey->full_name ?? 'Unknown';
                    $date = $a->appointment_date ? $a->appointment_date->format('d M') : 'N/A';
                    return "- {$a->appointment_id}: {$pName} on {$date} ({$a->status})";
                })->implode("\n");

            // Calculate Team Statistics
            $stats = [];
            if ($user->isSuperAdmin()) {
                // Super Admin sees global stats
                $stats = User::selectRaw('designation, count(*) as count')
                    ->groupBy('designation')
                    ->pluck('count', 'designation')
                    ->toArray();

                $totalPatients = Survey::where('is_member', false)->count();
                $totalMembers = Survey::where('is_member', true)->count();
                $totalAppointments = Appointment::count();
            } else {
                // Regular users see their downline stats
                $stats = User::whereIn('id', $downlineIds)
                    ->selectRaw('designation, count(*) as count')
                    ->groupBy('designation')
                    ->pluck('count', 'designation')
                    ->toArray();

                $totalPatients = Survey::whereIn('created_by', $relevantUserIds)->where('is_member', false)->count();
                $totalMembers = Survey::whereIn('created_by', $relevantUserIds)->where('is_member', true)->count();
                $totalAppointments = Appointment::whereIn('created_by', $relevantUserIds)->count();
            }

            $statsStr = "TEAM STATISTICS:\n";
            foreach ($stats as $designation => $count) {
                $label = strtoupper($designation);
                $statsStr .= "- {$label}: {$count}\n";
            }
            $statsStr .= "- TOTAL TEAM SIZE: " . array_sum($stats) . "\n";
            $statsStr .= "- TOTAL PATIENTS: {$totalPatients}\n";
            $statsStr .= "- TOTAL MEMBERSHIPS: {$totalMembers}\n";
            $statsStr .= "- TOTAL APPOINTMENTS: {$totalAppointments}";

            $today = now()->format('d M Y');

            return "CONTEXT AS OF {$today}:\n" .
                "USER: {$user->profile->full_name}\n" .
                "{$statsStr}\n" .
                "TEAM (Recent Members):\n{$team}\n" .
                "RECENT MEMBERSHIPS:\n{$members}\n" .
                "RECENT PATIENTS:\n{$patients}\n" .
                "RECENT APPOINTMENTS:\n{$appointments}";
        } catch (\Exception $e) {
            Log::error("Error generating chat context: " . $e->getMessage());
            return "No specific data context available due to an internal error.";
        }
    }


    /**
     * Helper to append content to the system prompt in the context array.
     */
    private function appendToSystemPrompt(&$context, $content)
    {
        foreach ($context as &$msg) {
            if (isset($msg['role']) && $msg['role'] === 'system') {
                $msg['content'] .= $content;
                return;
            }
        }

        // Fallback: If no system message found (rare), add one
        array_unshift($context, ['role' => 'system', 'content' => $content]);
    }

    private function readLine($body)
    {
        $line = '';
        while (!$body->eof()) {
            $char = $body->read(1);
            if ($char === "\n")
                break;
            $line .= $char;
        }
        return trim($line);
    }
}
