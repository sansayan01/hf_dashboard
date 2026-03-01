@extends('layouts.app')

@section('title', 'Add Camp Record')
@section('header_title', 'New Camp Record')

@section('content')
    <div class="max-w-5xl mx-auto pb-12">
        <!-- Header Section -->
        <div
            class="relative bg-gradient-to-r from-accent to-indigo-600 rounded-3xl p-8 sm:p-10 mb-8 overflow-hidden shadow-2xl shadow-accent/20">
            <!-- Abstract Background Vectors -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl mix-blend-overlay">
            </div>
            <div
                class="absolute bottom-0 right-32 -mb-20 w-80 h-80 rounded-full bg-indigo-500/30 blur-3xl mix-blend-overlay">
            </div>
            <svg class="absolute inset-0 w-full h-full opacity-10 pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>

            <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div
                        class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white/90 text-[10px] font-black uppercase tracking-widest mb-3 border border-white/10 shadow-inner">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Finances</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2 drop-shadow-md">New Camp
                        Record</h2>
                    <p class="text-indigo-100 font-medium max-w-xl text-sm md:text-base">Document comprehensive details of
                        the health camp including logistics, medical staff, patient demographics, and financial summaries.
                    </p>
                </div>

                <a href="{{ route('camp_records.index') }}"
                    class="group inline-flex items-center justify-center space-x-2 bg-white text-accent px-6 py-3 rounded-xl font-bold text-sm hover:bg-slate-50 hover:shadow-xl hover:shadow-white/20 transition-all active:scale-95 duration-200 shrink-0">
                    <svg class="w-4 h-4 text-accent/70 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>All Records</span>
                </a>
            </div>
        </div>

        <!-- Main Form Background Container -->
        <div
            class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl rounded-3xl border border-white/50 dark:border-white/5 shadow-xl p-6 sm:p-10 relative z-10">

            <form action="{{ route('camp_records.store') }}" method="POST" class="space-y-12">
                @csrf

                <!-- 1. Camp Logistics & Info -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 mb-2">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-500/10 dark:to-blue-500/10 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shadow-sm text-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-none">Camp Logistics</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Primary details regarding
                                location and timing.</p>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 dark:bg-slate-800/30 p-6 rounded-2xl border border-slate-100 dark:border-white/5">

                        <div class="space-y-1.5 md:col-span-2">
                            <label
                                class="flex items-center space-x-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span>Camp Name</span> <span class="text-red-500 text-lg leading-none">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <input type="text" name="camp_name" value="{{ old('camp_name') }}" required
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 text-sm focus:border-accent focus:ring-accent/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="e.g. Free Eye Checkup Camp, District Clinic...">
                            </div>
                            @error('camp_name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="flex items-center space-x-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span>Date of Camp</span> <span class="text-red-500 text-lg leading-none">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 text-sm focus:border-accent focus:ring-accent/20 outline-none transition-all shadow-sm text-slate-700 dark:text-white">
                            </div>
                            @error('date') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5 group">
                            <label
                                class="flex items-center space-x-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span>Regional Manager (RM)</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" name="rm" value="{{ old('rm') }}"
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 text-sm focus:border-accent focus:ring-accent/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="Enter RM name...">
                            </div>
                            @error('rm') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1.5 md:col-span-2 group">
                            <label
                                class="flex items-center space-x-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span>Exact Location / Address</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute top-3.5 left-0 pl-4 flex items-start pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <input type="text" name="location" value="{{ old('location') }}"
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 text-sm focus:border-accent focus:ring-accent/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="Street, Village, Post Office...">
                            </div>
                            @error('location') <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent">
                </div>

                <!-- 2. Medical Staff Details -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 mb-2">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-500/10 dark:to-teal-500/10 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 shadow-sm text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-none">Medical Team</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Record the attending
                                professionals.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Consulting
                                Doctor</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                    <i class="fas fa-user-md text-sm"></i>
                                </div>
                                <input type="text" name="doctor_name" value="{{ old('doctor_name') }}"
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-emerald-500 focus:ring-emerald-500/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="Dr. Name">
                            </div>
                        </div>

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pathologist</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                    <i class="fas fa-microscope text-sm"></i>
                                </div>
                                <input type="text" name="pathologist" value="{{ old('pathologist') }}"
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-emerald-500 focus:ring-emerald-500/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="Pathologist Name">
                            </div>
                        </div>

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pharmacist</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                    <i class="fas fa-pills text-sm"></i>
                                </div>
                                <input type="text" name="pharmacists_name" value="{{ old('pharmacists_name') }}"
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-emerald-500 focus:ring-emerald-500/20 outline-none transition-all shadow-sm dark:text-white"
                                    placeholder="Pharmacist Name">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 dark:via-slate-700 to-transparent">
                </div>

                <!-- 3. Finances & Statistics -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-500/10 dark:to-orange-500/10 flex items-center justify-center border border-amber-100 dark:border-amber-500/20 shadow-sm text-amber-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-none">Financial
                                    Breakdown</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Record sales,
                                    expenses, and calculate net profit.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-50/50 dark:bg-slate-800/30 p-6 rounded-2xl border border-slate-100 dark:border-white/5">

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Total
                                Patients</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                    <i class="fas fa-users text-sm"></i>
                                </div>
                                <input type="number" name="patients_count" value="{{ old('patients_count', 0) }}" min="0"
                                    step="1"
                                    class="w-full h-11 pl-12 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-amber-500 focus:ring-amber-500/20 outline-none transition-all shadow-sm dark:text-white font-mono font-bold text-slate-700">
                            </div>
                        </div>

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine
                                MRP</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold group-focus-within:text-amber-500 transition-colors">
                                    ₹</div>
                                <input type="number" name="medicine_mrp" value="{{ old('medicine_mrp', 0) }}" min="0"
                                    step="0.01"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-amber-500 focus:ring-amber-500/20 outline-none transition-all shadow-sm dark:text-white font-mono">
                            </div>
                        </div>

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine
                                Discount</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold group-focus-within:text-amber-500 transition-colors">
                                    ₹</div>
                                <input type="number" name="medicine_discount" value="{{ old('medicine_discount', 0) }}"
                                    min="0" step="0.01"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-amber-500 focus:ring-amber-500/20 outline-none transition-all shadow-sm dark:text-white font-mono text-red-400 font-medium">
                            </div>
                        </div>

                        <div class="space-y-1.5 focus-within:-translate-y-1 transition-transform duration-300">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Billing
                                Price</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold group-focus-within:text-amber-500 transition-colors">
                                    ₹</div>
                                <input type="number" name="billing_price" value="{{ old('billing_price', 0) }}" min="0"
                                    step="0.01"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-amber-500 focus:ring-amber-500/20 outline-none transition-all shadow-sm dark:text-white font-mono font-bold">
                            </div>
                        </div>
                    </div>

                    <!-- Final Calculations Sub-Section -->
                    <div
                        class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700/50 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none p-6">

                        <!-- Decorative highlight -->
                        <div
                            class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-400 via-accent to-indigo-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                            <div class="space-y-2">
                                <label
                                    class="flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-slate-500">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]">
                                    </div>
                                    <span>Gross Profit</span>
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500 font-black text-lg">
                                        ₹</div>
                                    <input type="number" id="profit" name="profit" value="{{ old('profit', 0) }}"
                                        step="0.01"
                                        class="w-full h-14 pl-10 pr-4 rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-emerald-50/30 dark:bg-emerald-500/5 text-lg focus:border-emerald-500 focus:ring-0 outline-none transition-all dark:text-emerald-400 font-mono font-black text-emerald-600 shadow-inner">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-slate-500">
                                    <div class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                                    <span>Camp Expenses</span>
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-red-500 font-black text-lg">
                                        ₹</div>
                                    <input type="number" id="expenses" name="expenses" value="{{ old('expenses', 0) }}"
                                        min="0" step="0.01"
                                        class="w-full h-14 pl-10 pr-4 rounded-xl border-2 border-slate-100 dark:border-slate-800 bg-red-50/30 dark:bg-red-500/5 text-lg focus:border-red-500 focus:ring-0 outline-none transition-all dark:text-red-400 font-mono font-black text-red-600 shadow-inner">
                                </div>
                            </div>

                            <div class="space-y-2 lg:border-l lg:border-slate-100 dark:lg:border-white/5 lg:pl-8">
                                <label
                                    class="flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-slate-500">
                                    <span>Net Profit / Loss</span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[8px] tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-400">AUTO</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-slate-400 font-black text-xl"
                                        id="net_currency_symbol">₹</div>
                                    <input type="number" id="net_profit_loss" name="net_profit_loss"
                                        value="{{ old('net_profit_loss', 0) }}" step="0.01" readonly
                                        class="w-full h-14 pl-8 pr-4 bg-transparent border-0 text-3xl focus:ring-0 outline-none font-mono font-black"
                                        tabindex="-1">
                                </div>
                                <p class="text-[10px] font-medium text-slate-400" id="net_helper_text">Calculated: Profit -
                                    Expenses</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <button type="reset"
                        class="w-full sm:w-auto px-6 py-3.5 text-sm font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                        Reset Form
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-accent to-indigo-600 hover:from-indigo-600 hover:to-accent text-white rounded-xl text-sm font-black tracking-wide shadow-xl shadow-accent/30 hover:shadow-2xl hover:shadow-accent/40 active:scale-95 transition-all flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 -ml-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span>Save Camp Record</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profitInput = document.getElementById('profit');
            const expensesInput = document.getElementById('expenses');
            const netInput = document.getElementById('net_profit_loss');
            const netSymbol = document.getElementById('net_currency_symbol');

            function calculateNet() {
                const profit = parseFloat(profitInput.value) || 0;
                const expenses = parseFloat(expensesInput.value) || 0;
                const net = profit - expenses;

                // Format number nicely, removing negative sign since we'll handle colors
                netInput.value = Math.abs(net).toFixed(2);

                if (net >= 0) {
                    // Profit Mode
                    netInput.classList.remove('text-red-500', 'dark:text-red-400');
                    netInput.classList.add('text-emerald-600', 'dark:text-emerald-400');
                    netSymbol.classList.remove('text-red-500', 'text-slate-400');
                    netSymbol.classList.add('text-emerald-600');
                    netSymbol.innerText = '+₹';
                } else {
                    // Loss Mode
                    netInput.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                    netInput.classList.add('text-red-500', 'dark:text-red-400');
                    netSymbol.classList.remove('text-emerald-600', 'text-slate-400');
                    netSymbol.classList.add('text-red-500');
                    netSymbol.innerText = '-₹';
                }
            }

            profitInput.addEventListener('input', calculateNet);
            expensesInput.addEventListener('input', calculateNet);

            // Initial calculation
            calculateNet();
        });
    </script>
@endsection