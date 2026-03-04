@forelse($expenses as $expense)
    <tr>
        <td>
            <span class="font-semibold text-slate-800 dark:text-white">{{ $expense->expense_date->format('d M') }}</span>
            <span class="block text-[10px] text-slate-400">{{ $expense->expense_date->format('Y') }}</span>
        </td>
        <td>
            <p class="font-semibold text-slate-800 dark:text-white">{{ $expense->title }}</p>
            @if($expense->description)
                <p class="text-[11px] text-slate-400 truncate max-w-[200px]">{{ $expense->description }}</p>
            @endif
        </td>
        <td>
            @php
                $catStyles = [
                    'Office Supplies' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                    'Travel' => 'bg-violet-50 text-violet-600 border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20',
                    'Event/Camp' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                    'Salary/Stipend' => 'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                    'Utilities' => 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                    'Medical Supplies' => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                    'Printing' => 'bg-cyan-50 text-cyan-600 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20',
                    'Food & Refreshments' => 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                ];
                $cs = $catStyles[$expense->category] ?? 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/20';
            @endphp
            <span class="cat-badge {{ $cs }}">{{ $expense->category }}</span>
        </td>
        <td class="text-right">
            <span class="font-bold text-slate-900 dark:text-white"
                style="font-variant-numeric:tabular-nums">₹{{ number_format($expense->amount, 2) }}</span>
        </td>
        <td>
            <span
                class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ \App\Models\Expense::PAYMENT_METHODS[$expense->payment_method] ?? $expense->payment_method }}</span>
            @if($expense->reference_number)
                <span class="block text-[10px] text-slate-400">Ref: {{ $expense->reference_number }}</span>
            @endif
        </td>
        <td class="text-center">
            @if($expense->receipt_path)
                <a href="{{ route('storage.bridge', ['path' => $expense->receipt_path]) }}" target="_blank"
                    class="icon-btn bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </a>
            @else
                <span class="text-slate-300 dark:text-slate-600">—</span>
            @endif
        </td>
        <td class="text-right">
            <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('expenses.edit', $expense) }}"
                    class="icon-btn bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                    onsubmit="return confirm('Delete this expense?');">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="icon-btn bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center" style="padding:48px 20px">
            <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-400">No expenses found with selected filters</p>
        </td>
    </tr>
@endforelse