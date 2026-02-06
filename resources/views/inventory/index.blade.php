@extends('layouts.app')

@section('title', 'Inventory Overview')
@section('header_title', 'NGO Pharmacy Inventory')

@section('content')
    <div class="space-y-8">
        <!-- Navigation Links -->
        <div class="flex flex-wrap items-center gap-4 justify-center">
            @if(auth()->user()->designation !== 'staff')
                <a href="{{ route('inventory.warehouses.index') }}"
                    class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-accent transition">Warehouses</a>
                <a href="{{ route('inventory.camps.index') }}"
                    class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-accent transition">Camps</a>
                <a href="{{ route('inventory.sponsors.index') }}"
                    class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-accent transition">Sponsors</a>
                <a href="{{ route('inventory.medicines.index') }}"
                    class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-accent transition">Medicine
                    Registry</a>
            @endif
            <a href="{{ route('inventory.transactions') }}"
                class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-accent transition">Transaction
                Logs</a>
        </div>

        <!-- Quick Actions --->
        <div class="flex flex-col space-y-4">
            <a href="{{ route('inventory.create') }}"
                class="bg-accent text-white rounded-2xl flex items-center justify-center space-x-3 font-bold text-sm shadow-xl shadow-accent/20 hover:opacity-90 transition py-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Stock In (Recieve)</span>
            </a>
            <div class="flex space-x-4">
                <a href="{{ route('inventory.dispense') }}"
                    class="flex-1 bg-emerald-500 text-white rounded-2xl flex items-center justify-center space-x-3 font-bold text-sm shadow-xl shadow-emerald-500/20 hover:opacity-90 transition py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Dispense</span>
                </a>
                <a href="{{ route('inventory.transfer') }}"
                    class="flex-1 bg-amber-500 text-white rounded-2xl flex items-center justify-center space-x-3 font-bold text-sm shadow-xl shadow-amber-500/20 hover:opacity-90 transition py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Transfer</span>
                </a>
            </div>
        </div>

        <!-- Medicine Quantity Chart -->
        <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-8">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Top 10 Medicines by Stock Quantity</h3>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8">
                <div class="w-full md:w-1/2 max-w-md">
                    <canvas id="medicineChart"></canvas>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="space-y-3">
                        @foreach($medicineData as $index => $medicine)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full chart-color-{{ $index }}"></div>
                                    <span
                                        class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $medicine['name'] }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-slate-800 dark:text-white">
                                        {{ number_format($medicine['quantity']) }}
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $medicine['unit'] }}s</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="font-bold text-lg">Batch-wise Inventory</h3>
                    <form action="{{ route('inventory.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <div class="relative flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medicine, batch..."
                                class="h-10 w-64 pl-10 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            @if(request('search'))
                                <a href="{{ route('inventory.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <select name="warehouse_id" onchange="this.form.submit()"
                            class="h-9 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                </option>
                            @endforeach
                        </select>

                        @if(request('warehouse_id') || ((auth()->user()->designation === 'staff' || auth()->user()->isOfficeInCharge()) && auth()->user()->camp_id))
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="exclusive" value="1" 
                                    {{ request('exclusive') == '1' ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                    class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent transition">
                                <span class="text-[10px] font-black uppercase text-slate-500 tracking-tight">Show Only Exclusive Stock</span>
                            </label>
                        @endif
                        
                        <button type="submit" class="hidden">Search</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-white/5">
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine &
                                Location</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Batch Info</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Expiry</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Quantity</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Sponsor</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($stocks as $stock)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-sm">{{ $stock->medicine?->name ?? 'Unknown Medicine' }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="text-[10px] text-slate-700 dark:text-slate-300 font-bold">{{ $stock->medicine?->generic_name ?? 'N/A' }}</span>
                                            <span class="text-slate-200 dark:text-white/10 text-[10px]">•</span>
                                            <span
                                                class="text-[10px] text-accent font-black uppercase tracking-widest">{{ $stock->warehouse?->name ?? 'Main Warehouse' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <code
                                        class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded text-[10px] font-bold">
                                                                                                                            #{{ $stock->batch_number }}
                                                                                                                        </code>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="text-xs font-bold {{ $stock->expiry_date->isPast() ? 'text-red-500' : ($stock->expiry_date->diffInMonths(now()) < 3 ? 'text-amber-500' : 'text-slate-600 dark:text-slate-300') }}">
                                        {{ $stock->expiry_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-sm font-black text-slate-800 dark:text-white">{{ $stock->quantity }}
                                        {{ $stock->medicine?->unit ?? 'Units' }}</span>
                                </td>
                                <td class="p-4">
                                    @if($stock->expiry_date->isPast())
                                        <span
                                            class="px-2 py-1 bg-red-100 text-red-600 text-[10px] font-black rounded-lg uppercase tracking-tight">Expired</span>
                                    @elseif($stock->expiry_date->diffInMonths(now()) < 3)
                                        <span
                                            class="px-2 py-1 bg-amber-100 text-amber-600 text-[10px] font-black rounded-lg uppercase tracking-tight">Expiring
                                            Soon</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-tight">Good</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @php
                                        $stockSponsor = $stock->sponsor;
                                        if (!$stockSponsor) {
                                            $inTx = $stock->transactions->where('type', 'in')->first();
                                            $stockSponsor = $inTx?->sponsor;
                                        }
                                    @endphp
                                    <span
                                        class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 text-[10px] font-bold rounded uppercase border border-blue-500/20">
                                        {{ $stockSponsor->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('inventory.transfer', ['stock_id' => $stock->id]) }}"
                                        class="p-2 text-slate-400 hover:text-amber-500 transition inline-block"
                                        title="Move stock to another warehouse">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-20 text-center">
                                    <p class="text-slate-500 font-bold mb-4">You have no active stock.</p>
                                    <a href="{{ route('inventory.create') }}"
                                        class="text-accent font-black uppercase text-[10px]">Stock In Now &rarr;</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const medicineData = @json($medicineData);

        // Generate vibrant colors for each medicine
        const colors = [
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#F59E0B', // Amber
            '#10B981', // Emerald
            '#3B82F6', // Blue
            '#EF4444', // Red
            '#06B6D4', // Cyan
            '#F97316', // Orange
            '#84CC16', // Lime
            '#A855F7', // Violet
        ];

        // Apply colors to legend items
        medicineData.forEach((medicine, index) => {
            const colorElements = document.querySelectorAll(`.chart-color-${index}`);
            colorElements.forEach(el => {
                el.style.backgroundColor = colors[index % colors.length];
            });
        });

        // Create donut chart
        const ctx = document.getElementById('medicineChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: medicineData.map(m => m.name),
                datasets: [{
                    data: medicineData.map(m => m.quantity),
                    backgroundColor: colors.slice(0, medicineData.length),
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                const unit = medicineData[context.dataIndex].unit;
                                return `${label}: ${value.toLocaleString()} ${unit}s (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
    </script>
@endsection