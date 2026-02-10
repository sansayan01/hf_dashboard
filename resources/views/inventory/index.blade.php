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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-darkbg/40 p-4 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ auth()->user()->name }}</h2>
                <div class="flex items-center space-x-2 text-xs font-bold text-slate-500">
                    <span class="bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 px-2 py-0.5 rounded uppercase tracking-wider">{{ auth()->user()->designation ?? 'User' }}</span>
                    <span>•</span>
                    <span>{{ auth()->user()->camp ? auth()->user()->camp->name : 'Head Office' }}</span>
                    <span>•</span>
                    <span>{{ now()->format('l, d M Y') }}</span>
                </div>
            </div>
            
            <!-- Quick Navigation -->
            <div class="flex flex-wrap items-center gap-2">
                 @if(auth()->user()->designation !== 'staff')
                <a href="{{ route('inventory.warehouses.index') }}"
                    class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Warehouses</a>
                <a href="{{ route('inventory.camps.index') }}"
                    class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Camps</a>
                <a href="{{ route('inventory.sponsors.index') }}"
                    class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Sponsors</a>
                @endif
                <a href="{{ route('inventory.transactions') }}"
                    class="px-3 py-2 text-xs font-bold bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition">Logs</a>
            </div>
        </div>

        <!-- 2. KPI Summary Cards (Grid Layout) -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
             <!-- Card: Total Stock Value -->
             <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Total Stock Value</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">₹{{ number_format($totalValue, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-blue-500 z-10">
                    <span class="bg-blue-50 dark:bg-blue-500/10 px-1.5 py-0.5 rounded mr-2">+0%</span> vs last month
                </div>
            </div>

            <!-- Card: Today's Sales -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Today's Sales</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">₹{{ number_format($todaySales, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-emerald-500 z-10">
                    Revenue Collected
                </div>
            </div>

            <!-- Card: Today's Purchases -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Today's Value In</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">₹{{ number_format($todayPurchases, 0) }}</div>
                </div>
                <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-purple-500 z-10">
                    Stock Received
                </div>
            </div>

            <!-- Card: Receivables -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full">
                <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Receivables / Dues</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">₹{{ number_format($receivables ?? 0, 0) }}</div>
                </div>
                 <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-amber-500 z-10">
                    Pending Collection
                </div>
            </div>

             <!-- Card: Low Stock -->
            <a href="#inventory-section" onclick="event.preventDefault(); document.getElementById('status-filter').value='low_stock'; document.getElementById('inventory-filter-form').submit();" class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full hover:border-red-500/50 transition-all cursor-pointer">
                <div class="absolute right-0 top-0 w-24 h-24 bg-red-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Low Stock Items</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">{{ $lowStockCount }}</div>
                </div>
                 <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-red-500 z-10">
                    Needs Attention
                </div>
            </a>
            
             <!-- Card: Expiry Risk -->
            <a href="#inventory-section" onclick="event.preventDefault(); document.getElementById('status-filter').value='near_expiry'; document.getElementById('inventory-filter-form').submit();" class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-5 flex flex-col items-center justify-center text-center relative overflow-hidden group h-full hover:border-orange-500/50 transition-all cursor-pointer">
                <div class="absolute right-0 top-0 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="z-10">
                     <h4 class="text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Near Expiry (< 90 Days)</h4>
                     <div class="text-2xl font-black text-slate-800 dark:text-white truncate">{{ $nearExpiryCount }}</div>
                </div>
                 <div class="mt-3 flex items-center justify-center text-[10px] font-bold text-orange-500 z-10">
                    {{ $expiredCount }} Already Expired
                </div>
            </a>
        </div>

        <!-- 3. Sales Trend & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sales Trend Chart (Takes 3 cols) -->
            <div class="lg:col-span-3 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                 <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Transaction Trends</h3>
                    <div class="flex space-x-2">
                        <span class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded text-[10px] font-bold">Daily</span>
                        <span class="px-2 py-1 hover:bg-slate-50 dark:hover:bg-white/5 text-slate-400 rounded text-[10px] font-bold cursor-pointer">Weekly</span>
                    </div>
                </div>
                 <div class="relative h-64 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions (Takes 1 col) -->
            <div class="lg:col-span-1 flex flex-col gap-4">
                 <a href="{{ route('inventory.create') }}"
                    class="group bg-accent text-white rounded-3xl p-6 flex flex-col items-center justify-center text-center shadow-xl shadow-accent/20 hover:opacity-90 transition relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span class="font-bold">Stock In</span>
                    <span class="text-[10px] opacity-80 mt-1">Add new purchase</span>
                </a>
                
                 <div class="grid grid-cols-2 gap-4 flex-1">
                    <a href="{{ route('inventory.dispense') }}"
                        class="bg-emerald-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-emerald-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="font-bold text-xs">New Bill</span>
                    </a>
                     <a href="{{ route('inventory.transfer') }}"
                        class="bg-amber-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-amber-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        <span class="font-bold text-xs">Transfer</span>
                    </a>
                    <a href="{{ route('inventory.medicines.create') }}"
                        class="bg-indigo-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-indigo-500/20 hover:opacity-90 transition">
                         <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        <span class="font-bold text-xs">Add Med</span>
                    </a>
                     <a href="#" onclick="alert('Use Transactions Log to identify returns.')"
                        class="bg-rose-500 text-white rounded-3xl p-4 flex flex-col items-center justify-center text-center shadow-lg shadow-rose-500/20 hover:opacity-90 transition">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                        <span class="font-bold text-xs">Return</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Inventory Health, Top Performance, Payment Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Top Medicines by Value (Was Inventory Health) -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex flex-col">
                 <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Top Medicines (By Value)</h3>
                 <div class="flex-1 flex flex-col justify-center">
                      <div class="relative h-48 w-full mb-4">
                        <canvas id="medicineValueChart"></canvas>
                     </div>
                     <div class="grid grid-cols-2 gap-4 mt-4">
                         <div class="bg-slate-50 dark:bg-white/5 rounded-2xl p-3 text-center">
                             <div class="text-xs font-bold text-slate-400 uppercase">Fast Moving</div>
                             <div class="font-black text-slate-800 dark:text-white">{{ $topMovers->count() }} Items</div>
                         </div>
                          <div class="bg-slate-50 dark:bg-white/5 rounded-2xl p-3 text-center">
                             <div class="text-xs font-bold text-slate-400 uppercase">Dead Stock</div>
                             <div class="font-black text-slate-800 dark:text-white">{{ $deadStockCount }}</div>
                         </div>
                     </div>
                 </div>
            </div>

            <!-- Top Performance List -->
            <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Top Performance</h3>
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded text-[10px] font-bold uppercase">Medicines</span>
                </div>
                 <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[300px]">
                    @forelse($topMovers as $item)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->medicine->name ?? 'Unknown' }}</div>
                                    <div class="text-[10px] text-slate-500 font-bold">
                                        {{ $item->medicine->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-slate-800 dark:text-white">{{ number_format($item->total_qty) }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Dispensed</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 font-bold text-xs">No enough data regarding moving items.</div>
                    @endforelse
                </div>
            </div>

            <!-- Payment Overview & Warehouse Value -->
            <div class="flex flex-col gap-6">
                 <!-- Payment Overview Widget -->
                <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6">
                     <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Payment Overview</h3>
                     <div class="space-y-3">
                        @foreach($paymentMethods as $method => $amount)
                        <div class="flex items-center justify-between">
                             <div class="flex items-center space-x-2">
                                 <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                 <span class="text-sm font-bold text-slate-600 dark:text-slate-300 capitalize">{{ $method ?: 'Cash' }}</span>
                             </div>
                             <span class="text-sm font-black text-slate-800 dark:text-white">₹{{ number_format($amount) }}</span>
                        </div>
                        @endforeach
                        @if(empty($paymentMethods))
                             <div class="text-center text-slate-400 text-xs font-bold py-2">No payments recorded today</div>
                        @endif
                     </div>
                </div>

                <!-- Warehouse Value Mini Chart -->
                <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-6 flex-1">
                     <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-2">Warehouse Value</h3>
                     <div class="relative h-32 w-full">
                        <canvas id="warehouseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Main Inventory Table & Recent Activity -->
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
             <!-- Recent Activity Feed (1 col) -->
             <div class="lg:col-span-1 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col h-full">
                 <div class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Recent Activity</h3>
                    <a href="{{ route('inventory.transactions') }}" class="text-xs font-bold text-accent hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[500px]">
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
            <div id="inventory-section" class="lg:col-span-2 bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="font-bold text-lg whitespace-nowrap">Batch-wise Inventory</h3>
                    <form id="inventory-filter-form" action="{{ route('inventory.index') }}" method="GET"
                        class="flex flex-wrap items-center gap-3 no-loader flex-1 justify-end">
                        
                        <!-- Status Filter -->
                        <div class="relative w-full md:w-auto">
                            <select name="status" id="status-filter"
                                class="h-10 pl-3 pr-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 outline-none transition-all appearance-none cursor-pointer shadow-sm w-full md:w-[130px]">
                                <option value="">All Status</option>
                                <option value="healthy" {{ request('status') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="near_expiry" {{ request('status') == 'near_expiry' ? 'selected' : '' }}>Near Expiry</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
                        <div class="relative group w-full md:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search medicine, batch..."
                                class="h-10 w-full pl-11 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-bold focus:ring-2 focus:ring-accent/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all shadow-sm">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <button type="submit"
                            class="h-10 px-4 rounded-2xl bg-accent text-white text-xs font-bold hover:bg-opacity-90 transition-all shadow-md shadow-accent/20 flex items-center space-x-2">
                             <span>Filter</span>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-white/5">
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine & Location</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Qty</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($stocks as $stock)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-sm">{{ $stock->medicine?->name ?? 'Unknown' }}</span>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded text-[9px]">#{{ $stock->batch_number }}</code>
                                                <span class="text-slate-200 dark:text-white/10 text-[10px]">•</span>
                                                <span class="text-[10px] text-slate-500">{{ $stock->warehouse?->name ?? 'Main' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col items-start gap-1">
                                             @if($stock->expiry_date->isPast())
                                                <span class="px-2 py-1 bg-red-100 text-red-600 text-[9px] font-black rounded uppercase tracking-tight">Expired</span>
                                            @elseif($stock->expiry_date->diffInMonths(now()) < 3)
                                                <span class="px-2 py-1 bg-amber-100 text-amber-600 text-[9px] font-black rounded uppercase tracking-tight">Expiring</span>
                                            @else
                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-600 text-[9px] font-black rounded uppercase tracking-tight">Healthy</span>
                                            @endif
                                            <span class="text-[9px] text-slate-400 font-bold">{{ $stock->expiry_date->format('M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ $stock->quantity }}</span>
                                        <span class="text-[9px] text-slate-400 block">{{ $stock->medicine?->unit }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('inventory.transfer', ['stock_id' => $stock->id]) }}"
                                            class="text-slate-400 hover:text-accent transition">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </a>
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
                <div class="p-4 border-t border-slate-100 dark:border-white/5">
                    {{ $stocks->links() }}
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

        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.scale.grid.color = 'rgba(148, 163, 184, 0.05)';

        const vibrantColors = [
            '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6', 
            '#EF4444', '#06B6D4', '#F97316', '#84CC16', '#A855F7'
        ];

        // 1. Medicine Value Chart (Doughnut)
        if (document.getElementById('medicineValueChart')) {
            new Chart(document.getElementById('medicineValueChart'), {
                type: 'doughnut',
                data: {
                    labels: medicineValueData.map(d => d.name),
                    datasets: [{
                        data: medicineValueData.map(d => d.value),
                        backgroundColor: vibrantColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumSignificantDigits: 3 }).format(context.parsed);
                                }
                            }
                        }
                    },
                    cutout: '75%',
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
                        legend: { position: 'top', align: 'end', labels: { boxWidth: 8, usePointStyle: true, font: {size: 10} } },
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: true, borderDash: [2, 2] } },
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: {size: 9} } }
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
    </script>
@endsection