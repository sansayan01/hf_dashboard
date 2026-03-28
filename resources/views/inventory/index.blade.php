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

        /* Custom Scrollbar for horizontal scrolling cards */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection

@section('content')
    <div class="space-y-8">
        <!-- 1. Top Bar: User & Store Info -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-darkbg/40 p-4 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ auth()->user()->name }}</h2>
                <div class="flex items-center space-x-2 text-xs font-bold text-slate-500">
                    <span
                        class="bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 px-2 py-0.5 rounded uppercase tracking-wider">{{ auth()->user()->designation ?? 'User' }}</span>
                    <span>•</span>
                    <span>{{ auth()->user()->camp ? auth()->user()->camp->name : 'Head Office' }}</span>
                    <span>•</span>
                    <span>{{ now()->format('l, d M Y') }}</span>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="flex flex-wrap items-center gap-2">
                @if(auth()->user()->hasPermission('inventory.manage_warehouses'))
                    <a href="{{ route('inventory.warehouses.index') }}"
                        class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Warehouses</a>
                    <a href="{{ route('inventory.camps.index') }}"
                        class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Camps</a>
                @endif
                @if(auth()->user()->hasPermission('inventory.manage_sponsors'))
                    <a href="{{ route('inventory.sponsors.index') }}"
                        class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Sponsors</a>
                @endif
                @if(auth()->user()->hasPermission('inventory.manage_medicines'))
                    <a href="{{ route('inventory.medicines.index') }}"
                        class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Medicine
                        Registry</a>
                @endif
                @if(auth()->user()->hasPermission('inventory.view_transactions'))
                <a href="{{ route('inventory.transactions') }}"
                    class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Logs</a>
                @endif
            </div>
        </div>

        <!-- 2. KPI Summary Cards (Grid Layout) -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Card: Total Stock Value -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Total
                        Stock Value</h4>
                    <div class="text-2xl font-black text-slate-800 dark:text-white truncate">
                        ₹{{ number_format($totalValue, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-blue-500 z-10">
                    <span class="bg-blue-50 dark:bg-blue-500/10 px-1.5 py-0.5 rounded mr-2">+0%</span> vs last month
                </div>
            </div>

            <!-- Card: Today's Sales -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                        Today's Sales</h4>
                    <div class="text-2xl font-black text-slate-800 dark:text-white truncate">
                        ₹{{ number_format($todaySales, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-emerald-500 z-10">
                    Revenue Collected
                </div>
            </div>

            <!-- Card: Today's Purchases -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                        Today's Value In</h4>
                    <div class="text-2xl font-black text-slate-800 dark:text-white truncate">
                        ₹{{ number_format($todayPurchases, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-purple-500 z-10">
                    Stock Received
                </div>
            </div>

            <!-- Card: Receivables -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                        Receivables / Dues</h4>
                    <div class="text-2xl font-black text-slate-800 dark:text-white truncate">
                        ₹{{ number_format($receivables ?? 0, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-amber-500 z-10">
                    Pending Collection
                </div>
            </div>

            <!-- Card: Low Stock -->
            <a href="#inventory-section"
                onclick="event.preventDefault(); document.getElementById('status-filter').value='low_stock'; document.getElementById('inventory-filter-form').submit();"
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full hover:border-red-500/50 transition-all cursor-pointer">
                <div class="absolute right-0 top-0 w-24 h-24 bg-red-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Low
                        Stock Items</h4>
                    <div class="text-2xl font-black text-slate-800 dark:text-white truncate">{{ $lowStockCount }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-red-500 z-10">
                    Needs Attention
                </div>
            </a>

            <!-- Card: Expiry Risk -->
            <a href="#inventory-section"
                onclick="event.preventDefault(); document.getElementById('status-filter').value='near_expiry'; document.getElementById('inventory-filter-form').submit();"
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full hover:border-orange-500/50 transition-all cursor-pointer">
                <div class="absolute right-0 top-0 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                    <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Near
                        Expiry (< 90 Days)</h4>
                            <div class="text-2xl font-black text-slate-800 dark:text-white truncate">{{ $nearExpiryCount }}
                            </div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-orange-500 z-10">
                    {{ $expiredCount }} Already Expired
                </div>
            </a>
        </div>

        <!-- 3. Sales Trend & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sales Trend Chart (Takes 3 cols) -->
            <div
                class="lg:col-span-3 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Transaction Trends</h3>
                    <div class="flex space-x-2">
                        <span
                            class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded text-[10px] font-bold">Daily</span>
                        <span
                            class="px-2 py-1 hover:bg-slate-50 dark:hover:bg-white/5 text-slate-400 rounded text-[10px] font-bold cursor-pointer">Weekly</span>
                    </div>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions (Takes 1 col) -->
            <div class="lg:col-span-1 flex flex-col gap-4">
                @if(auth()->user()->hasPermission('inventory.add_stock'))
                <a href="{{ route('inventory.create') }}"
                    class="group bg-accent text-white rounded-3xl p-6 flex flex-col items-center justify-center text-center shadow-xl shadow-accent/20 hover:opacity-90 transition relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                    </div>
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-bold">Stock In</span>
                    <span class="text-[10px] opacity-80 mt-1">Add new purchase</span>
                </a>
                @endif

                <div class="grid grid-cols-2 gap-4 flex-1">
                    @if(auth()->user()->hasPermission('inventory.dispense'))
                    <a href="{{ route('inventory.dispense') }}"
                        class="bg-emerald-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-emerald-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-bold text-xs">New Bill</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('inventory.transfer'))
                    <a href="{{ route('inventory.transfer') }}"
                        class="bg-amber-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-amber-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="font-bold text-xs">Transfer</span>
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('inventory.manage_medicines'))
                    <a href="{{ route('inventory.medicines.create') }}"
                        class="bg-indigo-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-indigo-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <span class="font-bold text-xs">Add Med</span>
                    </a>
                    @endif
                    <a href="#" onclick="alert('Use Transactions Log to identify returns.')"
                        class="bg-rose-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-rose-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        <span class="font-bold text-xs">Return</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Advanced Analytics & Trackers -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- 1. All Medicines by Value (Span 2 cols) -->
            <div
                class="md:col-span-2 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col">
                <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6 uppercase tracking-wider">Medicine Stock
                    Value</h3>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="relative w-full mb-4 overflow-x-auto pb-4">
                        <div id="medicineChartContainer" class="relative h-[350px]">
                            <canvas id="medicineValueChart"></canvas>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="text-[10px] font-bold text-slate-400">Total Asset Value:
                            ₹{{ number_format($totalValue, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Category Distribution (Quantity) -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col">
                <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6 uppercase tracking-wider">Category Mix
                    (Qty)</h3>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="relative h-[300px] w-full">
                        <canvas id="categoryQtyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4. Expiry Timeline -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col">
                <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6 uppercase tracking-wider">Expiry Timeline
                </h3>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="relative h-52 w-full">
                        <canvas id="expiryBreakdownChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 5. Sponsor Insights (New) -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col">
                <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6 uppercase tracking-wider">Top Sponsors
                    (Value)</h3>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="relative h-52 w-full">
                        <canvas id="sponsorChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 6. Top Performance List -->
            <div
                class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-white uppercase tracking-wider">Top Moving</h3>
                    <span
                        class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded text-[10px] font-bold uppercase">30
                        Days</span>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto h-52 scrollbar-none">
                    @forelse($topMovers as $item)
                        <div
                            class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-[10px]">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-[11px] truncate w-24">
                                        {{ $item->medicine->name ?? 'Unknown' }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase">
                                        {{ $item->medicine->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-slate-800 dark:text-white text-xs">
                                    {{ number_format($item->total_qty) }}</div>
                                <div
                                    class="text-[8px] font-bold {{ $item->coverage_days < 7 ? 'text-red-500' : 'text-slate-400' }} uppercase">
                                    {{ $item->coverage_days > 90 ? 'Safe' : ($item->coverage_days . ' Days Left') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 font-bold text-[10px]">No data.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. Secondary Row: Financials & Warehouses -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Methods Distribution -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-white uppercase tracking-wider">Income Streams
                    </h3>
                    <span
                        class="text-[10px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded">Today</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative h-32 w-32 shrink-0">
                        <canvas id="paymentPieChart"></canvas>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach($paymentMethods as $method => $amount)
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-500 capitalize">{{ $method ?: 'Cash' }}</span>
                                <span class="font-black text-slate-800 dark:text-white">₹{{ number_format($amount) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Warehouse Value Comparison -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6 uppercase tracking-wider">Stock
                    Concentration</h3>
                <div class="relative h-32 w-full">
                    <canvas id="warehouseChart"></canvas>
                </div>
            </div>

            <!-- Recent Patients Dues -->
            <div
                class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/20 flex flex-col h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-sm uppercase tracking-wider opacity-80">Recent Dues</h3>
                    <a href="{{ route('inventory.transactions', ['view' => 'dispenses', 'payment_method' => 'due']) }}"
                        class="text-[10px] font-bold bg-white/20 hover:bg-white/30 px-2 py-1 rounded transition">View
                        All</a>
                </div>

                <div class="flex-1 overflow-y-auto pr-1 custom-scrollbar" style="max-height: 200px;">
                    @forelse($recentDues as $due)
                        <div
                            class="flex justify-between items-center border-b border-white/10 pb-2 mb-2 last:border-0 last:mb-0 last:pb-0 group">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm">{{ $due->patient->full_name ?? 'Unknown' }}</span>
                                <span class="text-[10px] opacity-70">{{ $due->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="font-black text-lg">₹{{ number_format($due->final_amount, 0) }}</span>
                                <a href="{{ route('patients.show', $due->patient_id) }}"
                                    class="text-[10px] font-bold bg-white text-indigo-600 px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full opacity-70">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs font-bold">No recent dues found.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. Main Inventory Table & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity Feed (1 col) -->
            <div
                class="lg:col-span-1 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Recent Activity</h3>
                    <a href="{{ route('inventory.transactions') }}"
                        class="text-xs font-bold text-accent hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[400px]">
                    @forelse($recentActivity as $activity)
                                <div
                                    class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs
                                                    {{ $activity->type === 'in' ? 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400' :
                        ($activity->type === 'out' ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400' :
                            'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400') }}">
                                            {{ substr(strtoupper($activity->type), 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white text-sm">
                                                {{ ucfirst($activity->type) }}
                                                <span class="font-normal text-slate-500">
                                                    {{ $activity->stock->medicine->name ?? 'Item' }}
                                                </span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-bold">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 font-bold text-xs">No recent items found.</div>
                    @endforelse
                </div>
            </div>

            <!-- Main Inventory Table (2 cols) -->
            <div id="inventory-section"
                class="lg:col-span-2 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white flex flex-col h-[750px] lg:h-[450px]">
                <div class="border-b border-slate-100 dark:border-white/5 shrink-0">
                    {{-- Title Bar with Toggle --}}
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <h3 class="font-bold text-lg whitespace-nowrap">Batch-wise Inventory</h3>
                            @php
                                $activeFilterCount = collect([request('status'), request('category_id'), request('warehouse_id'), request('search')])->filter()->count();
                            @endphp
                            @if($activeFilterCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-accent text-white text-[9px] font-black">{{ $activeFilterCount }}</span>
                            @endif
                        </div>
                        <button type="button" id="toggle-inventory-filters"
                            class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold transition-all flex items-center gap-2 group"
                            aria-expanded="false" aria-controls="inventory-filters-panel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filters</span>
                            <svg id="filter-chevron" class="w-3.5 h-3.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    {{-- Collapsible Filter Panel --}}
                    <div id="inventory-filters-panel"
                        class="overflow-hidden transition-all duration-300 ease-in-out"
                        style="max-height: 0; opacity: 0;">
                        <div class="px-6 pb-5">
                            <form id="inventory-filter-form" action="{{ route('inventory.index') }}" method="GET"
                                class="flex flex-wrap items-center gap-3 no-loader">

                                <!-- Status Filter -->
                                <div class="relative w-full md:w-auto">
                                    <select name="status" id="status-filter"
                                        class="h-10 pl-3 pr-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition-all appearance-none cursor-pointer shadow-sm w-full md:w-[130px]">
                                        <option value="">All Status</option>
                                        <option value="healthy" {{ request('status') == 'healthy' ? 'selected' : '' }}>Healthy
                                        </option>
                                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock
                                        </option>
                                        <option value="near_expiry" {{ request('status') == 'near_expiry' ? 'selected' : '' }}>Near
                                            Expiry</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                                        </option>
                                    </select>
                                </div>

                                <!-- Category Selector -->
                                <div class="relative w-full md:w-auto">
                                    <select name="category_id" id="category_id"
                                        class="h-10 pl-3 pr-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition-all appearance-none cursor-pointer shadow-sm w-full md:w-[150px]">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(!auth()->user()->camp_id)
                                    <!-- Warehouse Selector -->
                                    <div class="relative w-full md:w-auto">
                                        <select name="warehouse_id" id="warehouse_id"
                                            class="h-10 pl-3 pr-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition-all appearance-none cursor-pointer shadow-sm w-full md:w-[150px]">
                                            <option value="">All Warehouses</option>
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                                    {{ $wh->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Search Input -->
                                <div class="relative group w-full md:w-auto flex-1 min-w-[200px]">
                                    <input type="text" name="search" id="inventory-search-input" value="{{ request('search') }}"
                                        placeholder="Search medicine, batch..."
                                        class="h-10 w-full pl-11 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all shadow-sm">
                                    <div
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-accent transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="h-10 px-4 rounded-2xl bg-accent text-white text-xs font-bold hover:bg-opacity-90 transition-all shadow-md shadow-accent/20 flex items-center space-x-2">
                                    <span>Filter</span>
                                </button>

                                @if(auth()->user()->hasPermission('inventory.export'))
                                <a href="#" id="download-batch-csv"
                                    onclick="event.preventDefault(); downloadBatchCSV();"
                                    class="h-10 px-4 rounded-2xl bg-emerald-500 text-white text-xs font-bold hover:bg-opacity-90 transition-all shadow-md shadow-emerald-500/20 flex items-center space-x-2"
                                    title="Download CSV">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>CSV</span>
                                </a>
                                @endif

                                @if($activeFilterCount > 0)
                                    <a href="{{ route('inventory.index') }}"
                                        class="h-10 px-3 rounded-2xl border border-red-200 dark:border-red-500/30 text-red-500 text-xs font-bold hover:bg-red-50 dark:hover:bg-red-500/10 transition-all flex items-center space-x-1"
                                        title="Clear all filters">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Clear</span>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="sticky top-0 bg-white dark:bg-slate-800/90 z-10 border-b border-slate-100 dark:border-white/5">
                            <tr>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine &
                                    Location</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Qty</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table-body" class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($stocks as $stock)
                                <tr class="inventory-row hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors"
                                    data-search="{{ strtolower($stock->medicine?->name . ' ' . $stock->batch_number) }}">
                                    <td class="p-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-sm medicine-name">{{ $stock->medicine?->name ?? 'Unknown' }}</span>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <code
                                                    class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded text-[9px] batch-number">#{{ $stock->batch_number }}</code>
                                                <span class="text-slate-200 dark:text-white/10 text-[10px]">•</span>
                                                <span
                                                    class="text-[10px] text-slate-500">{{ $stock->warehouse?->name ?? 'Main' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col items-start gap-1">
                                            @if($stock->expiry_date->isPast())
                                                <span
                                                    class="px-2 py-1 bg-red-100 dark:bg-red-500/15 text-red-600 dark:text-red-400 text-[9px] font-black rounded uppercase tracking-tight">Expired</span>
                                            @elseif($stock->expiry_date->diffInMonths(now()) < 3)
                                                <span
                                                    class="px-2 py-1 bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[9px] font-black rounded uppercase tracking-tight">Expiring</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-emerald-100 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 text-[9px] font-black rounded uppercase tracking-tight">Healthy</span>
                                            @endif
                                            <span
                                                class="text-[9px] text-slate-400 font-bold">{{ $stock->expiry_date->format('M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <span
                                            class="text-sm font-black text-slate-800 dark:text-white">{{ $stock->quantity }}</span>
                                        <span class="text-[9px] text-slate-400 block">{{ $stock->medicine?->unit }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if(auth()->user()->hasPermission('inventory.adjust'))
                                            <a href="{{ route('inventory.adjust', $stock->id) }}"
                                                class="text-slate-400 hover:text-accent transition" title="Manual Adjustment">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('inventory.transfer'))
                                            <a href="{{ route('inventory.transfer', ['stock_id' => $stock->id]) }}"
                                                class="text-slate-400 hover:text-accent transition" title="Transfer Stock">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center">
                                        <p class="text-slate-500 font-bold text-xs">No active stock matches your filter.</p>
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
        const medicineValueData = @json($medicineValueChartData);
        const warehouseData = @json($warehouseChartData);
        const trendData = @json($trendChartData);
        const expiryData = @json($expiryBreakdown);
        const categoryQtyData = @json($categoryQtyChartData);
        const paymentData = @json($paymentMethods);
        const sponsorData = @json($sponsorChartData);

        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.scale.grid.color = 'rgba(148, 163, 184, 0.05)';

        const vibrantColors = [
            '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6',
            '#EF4444', '#06B6D4', '#F97316', '#84CC16', '#A855F7'
        ];

        // 1. Medicine Value Chart (Vertical Bar - Horizontal Scroll)
        if (document.getElementById('medicineValueChart')) {
            // Calculate dynamic width: 40px per bar or min 100%
            const medCount = medicineValueData.length;
            const barWidth = 40;
            const neededWidth = medCount * barWidth;
            // Ensure meaningful width, prevent squishing
            const finalWidth = Math.max(document.getElementById('medicineChartContainer').parentElement.offsetWidth, neededWidth);

            const chartContainer = document.getElementById('medicineChartContainer');
            if (chartContainer) {
                chartContainer.style.width = finalWidth + 'px';
                chartContainer.style.height = '350px'; // Ensure height is maintained
            }

            new Chart(document.getElementById('medicineValueChart'), {
                type: 'bar',
                data: {
                    labels: medicineValueData.map(d => d.name),
                    datasets: [{
                        label: 'Value (₹)',
                        data: medicineValueData.map(d => d.value),
                        backgroundColor: medicineValueData.map((_, i) => vibrantColors[i % vibrantColors.length]),
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumSignificantDigits: 3 }).format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { display: true, borderDash: [2, 2] },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                callback: function (value) {
                                    if (value >= 1000) {
                                        return '₹' + (value / 1000).toFixed(0) + 'k';
                                    }
                                    return '₹' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // --- NEW CHARTS ---

        // 2. Expiry Breakdown Chart (Horizontal Bar)
        if (document.getElementById('expiryBreakdownChart')) {
            new Chart(document.getElementById('expiryBreakdownChart'), {
                type: 'bar',
                data: {
                    labels: ['Next 30 Days', '31-60 Days', '61-90 Days'],
                    datasets: [{
                        data: [expiryData['30_days'], expiryData['60_days'], expiryData['90_days']],
                        backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6'],
                        borderRadius: 4,
                        barThickness: 20
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        }

        // 3. Category Mix by Quantity (Doughnut)
        if (document.getElementById('categoryQtyChart')) {
            new Chart(document.getElementById('categoryQtyChart'), {
                type: 'doughnut',
                data: {
                    labels: categoryQtyData.map(d => d.name),
                    datasets: [{
                        data: categoryQtyData.map(d => d.value),
                        backgroundColor: vibrantColors.slice().reverse(),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    cutout: '60%',
                }
            });
        }

        // 4. Payment Pie Chart (Pie)
        if (document.getElementById('paymentPieChart')) {
            const labels = Object.keys(paymentData).map(k => k || 'Cash');
            const data = Object.values(paymentData);

            new Chart(document.getElementById('paymentPieChart'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }

        // 5. Sponsor Chart (Bar)
        if (document.getElementById('sponsorChart')) {
            new Chart(document.getElementById('sponsorChart'), {
                type: 'bar',
                data: {
                    labels: sponsorData.map(d => d.name),
                    datasets: [{
                        label: 'Value',
                        data: sponsorData.map(d => d.value),
                        backgroundColor: '#8B5CF6',
                        borderRadius: 4,
                        barThickness: 16
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' } } },
                        y: { display: false }
                    }
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
                        label: 'Value',
                        data: warehouseData.map(d => d.value),
                        backgroundColor: '#3B82F6',
                        borderRadius: 4,
                        barThickness: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        }

        // 3. Activity Trend Chart (Line)
        if (document.getElementById('trendChart')) {
            new Chart(document.getElementById('trendChart'), {
                type: 'bar', // Changed to Bar for better daily comparison visual
                data: {
                    labels: trendData.labels,
                    datasets: [
                        {
                            label: 'Dispensed',
                            data: trendData.dispense,
                            backgroundColor: '#10B981',
                            borderRadius: 2,
                        },
                        {
                            label: 'Stock In',
                            data: trendData.in,
                            backgroundColor: '#3B82F6',
                            borderRadius: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { boxWidth: 8, usePointStyle: true, font: { size: 10 } } },
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: true, borderDash: [2, 2] } },
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 9 } } }
                    }
                }
            });
        }

        // AJAX Filtering for Inventory
        const filterForm = document.getElementById('inventory-filter-form');
        const inventorySection = document.getElementById('inventory-section');

        if (filterForm && inventorySection) {
            filterForm.addEventListener('submit', function (e) {
                // e.preventDefault(); 
                // Using standard submit to Ensure simple reliability. 
                // AJAX replacement logic can be brittle if markup changes significantly.
            });

            function initTomSelect() {
                const wh = document.getElementById('warehouse_id');
                if (wh) {
                    new TomSelect(wh, {
                        create: false,
                        sortField: { field: "text", direction: "asc" },
                        placeholder: "Store...",
                        allowEmptyOption: true
                    });
                }

                const cat = document.getElementById('category_id');
                if (cat) {
                    new TomSelect(cat, {
                        create: false,
                        sortField: { field: "text", direction: "asc" },
                        placeholder: "Category...",
                        allowEmptyOption: true
                    });
                }
            }
            initTomSelect();
        }

        // --- Live Search Implementation ---
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('inventory-search-input');
            const tableRows = document.querySelectorAll('.inventory-row');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();

                    tableRows.forEach(row => {
                        const searchText = row.getAttribute('data-search') || '';
                        if (searchText.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });

                // Prevent form from reloading page when pressing enter in search if wanted
                // searchInput.addEventListener('keypress', function(e) { if(e.which == 13) e.preventDefault(); });
            }
        });

        // Download Batch Inventory CSV with current filters
        function downloadBatchCSV() {
            const params = new URLSearchParams();
            const status = document.getElementById('status-filter')?.value;
            const category = document.getElementById('category_id')?.value;
            const warehouse = document.getElementById('warehouse_id')?.value;
            const search = document.getElementById('inventory-search-input')?.value;

            if (status) params.set('status', status);
            if (category) params.set('category_id', category);
            if (warehouse) params.set('warehouse_id', warehouse);
            if (search) params.set('search', search);

            const url = "{{ route('inventory.export-batch') }}" + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        }

        // --- Collapsible Filter Panel ---
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggle-inventory-filters');
            const panel = document.getElementById('inventory-filters-panel');
            const chevron = document.getElementById('filter-chevron');

            if (toggleBtn && panel) {
                let isOpen = false;

                function openFilters() {
                    isOpen = true;
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    panel.style.opacity = '1';
                    chevron?.classList.add('rotate-180');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                    toggleBtn.classList.add('bg-accent/10', 'text-accent', 'dark:bg-accent/20', 'dark:text-accent');
                }

                function closeFilters() {
                    isOpen = false;
                    panel.style.maxHeight = '0';
                    panel.style.opacity = '0';
                    chevron?.classList.remove('rotate-180');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    toggleBtn.classList.remove('bg-accent/10', 'text-accent', 'dark:bg-accent/20', 'dark:text-accent');
                }

                toggleBtn.addEventListener('click', function () {
                    if (isOpen) {
                        closeFilters();
                    } else {
                        openFilters();
                    }
                });

                // Auto-expand if there are active filters
                const hasActiveFilters = {{ $activeFilterCount > 0 ? 'true' : 'false' }};
                if (hasActiveFilters) {
                    openFilters();
                }
            }
        });
    </script>
@endsection