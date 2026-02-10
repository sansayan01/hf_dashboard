@extends('layouts.app')

@section('title', 'Inventory Overview')
@section('header_title', 'NGO Pharmacy Inventory')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 1rem !important;
            height: 2.5rem !important;
            padding: 0 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            border: 1px solid #e2e8f0 !important;
            background-color: rgba(248, 250, 252, 0.5) !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            width: 200px !important;
        }

        .dark .ts-control {
            background-color: rgba(30, 41, 59, 0.5) !important;
            border-color: #334155 !important;
            color: white !important;
        }

        .ts-dropdown {
            border-radius: 1rem !important;
            margin-top: 5px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            font-family: 'Outfit', sans-serif !important;
            background-color: white !important;
            border: 1px solid #e2e8f0 !important;
        }

        .dark .ts-dropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: white !important;
        }

        .ts-dropdown .active {
            background-color: #3C50E0 !important;
            color: white !important;
        }
    </style>
@endsection

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

        <!-- Dashboard Summary Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Inventory Value -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-blue-500/20"></div>
                <div>
                    <h4 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Total Inventory Value</h4>
                    <div class="text-3xl font-black text-slate-800 dark:text-white">₹{{ number_format($totalValue, 2) }}</div>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Asset Valuation
                </div>
            </div>

            <!-- Total Medicines -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                 <div class="absolute right-0 top-0 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-purple-500/20"></div>
                <div>
                    <h4 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Total Medicines</h4>
                    <div class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalMedicines) }}</div>
                </div>
                 <div class="mt-4 flex items-center text-xs font-bold text-purple-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Active Stock Batches
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                 <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-amber-500/20"></div>
                <div>
                    <h4 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Low Stock Alerts</h4>
                    <div class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($lowStockCount) }}</div>
                </div>
                 <div class="mt-4 flex items-center text-xs font-bold text-amber-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Below Minimum Level
                </div>
            </div>

             <!-- Expiry Alerts -->
             <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                 <div class="absolute right-0 top-0 w-32 h-32 bg-red-500/10 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-red-500/20"></div>
                <div>
                    <h4 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Expiry Issues</h4>
                    <div class="flex items-baseline space-x-2">
                        <div class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($expiredCount) }}</div>
                        <div class="text-xs font-bold text-red-500">Expired</div>
                    </div>
                </div>
                 <div class="mt-4 flex items-center text-xs font-bold text-slate-500">
                    <span class="text-amber-500 font-bold mr-1">{{ $nearExpiryCount }}</span> Expiring Soon (&lt; 3 Months)
                </div>
            </div>
        </div>

        <!-- Dashboard Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Category Distribution -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Value by Category</h3>
                <div class="relative h-64 w-full">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Middle: Warehouse Distribution -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                 <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Warehouse Value</h3>
                 <div class="relative h-64 w-full">
                    <canvas id="warehouseChart"></canvas>
                </div>
            </div>

            <!-- Right: Activity Trend -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                 <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">30-Day Activity</h3>
                 <div class="relative h-64 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Advanced Tracking Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Moving Items -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Top 5 Moving Items (30 Days)</h3>
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded text-[10px] font-bold uppercase">Most Dispensed</span>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($topMovers as $item)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->medicine->name ?? 'Unknown' }}</div>
                                    <div class="text-[10px] text-slate-500 font-bold">
                                        {{ $item->medicine->category->name ?? 'Medicine' }}
                                        <span class="text-slate-300 dark:text-slate-600 mx-1">•</span>
                                        <span class="{{ $item->coverage_days < 10 ? 'text-red-500' : ($item->coverage_days < 30 ? 'text-amber-500' : 'text-emerald-500') }}">
                                            {{ $item->coverage_days > 90 ? '90+ Days Stock' : $item->coverage_days . ' Days Stock' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-slate-800 dark:text-white">{{ number_format($item->total_qty) }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Dispensed</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 font-bold text-xs">No dispensing activity in the last 30 days.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
                 <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Recent Activity Log</h3>
                    <a href="{{ route('inventory.transactions') }}" class="text-xs font-bold text-accent hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($recentActivity as $activity)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition">
                             <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs
                                    {{ $activity->type === 'in' ? 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400' : 
                                       ($activity->type === 'out' ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400' : 
                                       'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400') }}">
                                    {{ substr(strtoupper($activity->type), 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">
                                        {{ ucfirst($activity->type) }} 
                                        <span class="font-normal text-slate-500">
                                            {{ $activity->quantity }} x {{ $activity->stock->medicine->name ?? 'Unknown item' }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold">
                                        by {{ $activity->user->profile->full_name ?? $activity->user->employee_id }} • {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                         <div class="p-8 text-center text-slate-500 font-bold text-xs">No recent items found.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div id="inventory-section"
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
            <div
                class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="font-bold text-lg whitespace-nowrap">Batch-wise Inventory</h3>
                <form id="inventory-filter-form" action="{{ route('inventory.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 no-loader">
                    <!-- Search Input -->
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search medicine, batch..."
                            class="h-10 w-full md:w-64 pl-11 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all shadow-sm">
                        <div
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-accent transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('inventory.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-full transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- Warehouse Selector -->
                    <div class="relative">
                        <select name="warehouse_id" id="warehouse_id"
                            class="h-10 pl-3 pr-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exclusive Checkbox -->
                    @if(request('warehouse_id') || ((auth()->user()->designation === 'staff' || auth()->user()->isOfficeInCharge()) && auth()->user()->camp_id))
                        <label
                            class="flex items-center space-x-2.5 cursor-pointer bg-slate-50/50 dark:bg-slate-800/50 h-10 px-4 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-accent/30 transition-all shadow-sm">
                            <input type="checkbox" name="exclusive" value="1" {{ request('exclusive') == '1' ? 'checked' : '' }}
                                onchange="this.form.submit()"
                                class="w-4 h-4 rounded-lg border-slate-300 dark:border-slate-600 text-accent focus:ring-accent transition shadow-inner">
                            <span class="text-[10px] font-black uppercase text-slate-500 tracking-tight select-none">Show Only
                                Exclusive Stock</span>
                        </label>
                    @endif

                    <button type="submit"
                        class="h-10 px-6 rounded-2xl bg-accent text-white text-xs font-bold hover:bg-opacity-90 transition-all shadow-md shadow-accent/20 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <span>Filter</span>
                    </button>
                </form>
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
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // Data from Controller
        const categoryData = @json($categoryChartData);
        const warehouseData = @json($warehouseChartData);
        const trendData = @json($trendChartData);

        // Chart Configuration Defaults
        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#64748b';
        Chart.defaults.scale.grid.color = 'rgba(148, 163, 184, 0.1)';

        const vibrantColors = [
            '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6', 
            '#EF4444', '#06B6D4', '#F97316', '#84CC16', '#A855F7'
        ];

        // 1. Category Value Chart (Doughnut)
        if (document.getElementById('categoryChart')) {
            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(d => d.name),
                    datasets: [{
                        data: categoryData.map(d => d.value),
                        backgroundColor: vibrantColors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed !== null) {
                                        label += '₹' + new Intl.NumberFormat('en-IN').format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    cutout: '65%',
                }
            });
        }

        // 2. Warehouse Value Chart (Bar)
        if (document.getElementById('warehouseChart')) {
            new Chart(document.getElementById('warehouseChart'), {
                type: 'bar',
                data: {
                    labels: warehouseData.map(d => d.name),
                    datasets: [{
                        label: 'Stock Value',
                        data: warehouseData.map(d => d.value),
                        backgroundColor: '#3B82F6',
                        borderRadius: 8,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Value: ₹' + new Intl.NumberFormat('en-IN').format(context.parsed.x);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: false }, // Hide X axis labels for cleaner look
                        y: { 
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }

        // 3. Activity Trend Chart (Line)
        if (document.getElementById('trendChart')) {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: trendData.labels,
                    datasets: [
                        {
                            label: 'Dispensed',
                            data: trendData.dispense,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'Stock In',
                            data: trendData.in,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { boxWidth: 8, usePointStyle: true } },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false },
                    scales: {
                        y: { beginAtZero: true, grid: { display: true, borderDash: [4, 4] } },
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } }
                    }
                }
            });
        }

        // AJAX Filtering for Inventory
        const filterForm = document.getElementById('inventory-filter-form');
        const inventorySection = document.getElementById('inventory-section');

        if (filterForm && inventorySection) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;

                // Add a subtle loading state to the table
                const tableBody = inventorySection.querySelector('tbody');
                if (tableBody) tableBody.style.opacity = '0.5';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newSection = doc.getElementById('inventory-section');

                        if (newSection) {
                            inventorySection.innerHTML = newSection.innerHTML;
                            // Re-bind the event listener to the NEW form (since we replaced its parent)
                            bindFilterEvents();
                            // Update URL without reloading
                            window.history.pushState({}, '', url);
                        }
                    })
                    .catch(err => {
                        console.error('Filtering failed:', err);
                        filterForm.submit(); // Fallback to normal reload
                    });
            });

            function bindFilterEvents() {
                const newForm = document.getElementById('inventory-filter-form');

                // Re-initialize Tom Select on the new element
                initTomSelect();

                const interactiveEls = newForm.querySelectorAll('input[type="checkbox"]');
                interactiveEls.forEach(el => {
                    el.addEventListener('change', () => {
                        newForm.dispatchEvent(new Event('submit', { cancelable: true }));
                    });
                });

                const clearBtn = newForm.querySelector('a[href*="inventory"]');
                if (clearBtn) {
                    clearBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const searchInput = newForm.querySelector('input[name="search"]');
                        if (searchInput) searchInput.value = '';
                        newForm.dispatchEvent(new Event('submit', { cancelable: true }));
                    });
                }
            }

            function initTomSelect() {
                const el = document.getElementById('warehouse_id');
                if (!el) return;

                // Destroy existing instance if it exists
                if (window.warehouseSelect) {
                    window.warehouseSelect.destroy();
                }

                window.warehouseSelect = new TomSelect(el, {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: "Search warehouse...",
                    allowEmptyOption: true
                });

                window.warehouseSelect.on('change', () => {
                    const currentForm = document.getElementById('inventory-filter-form');
                    currentForm.dispatchEvent(new Event('submit', { cancelable: true }));
                });
            }

            initTomSelect();
            bindFilterEvents();
        }
    </script>
@endsection