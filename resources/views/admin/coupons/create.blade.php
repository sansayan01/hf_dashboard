@extends('layouts.app')

@section('content')
<div class="container-fluid py-6 animate-in fade-in slide-in-from-bottom-6 duration-700">
    <!-- Header Section -->
    <div class="relative mb-10">
        <!-- Background Glow -->
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-accent/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                    <span class="p-3 bg-accent/10 rounded-2xl">
                        <i class="fas fa-magic text-accent scale-110"></i>
                    </span>
                    Generate vouchers
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 ml-14 max-w-md">
                    Configure and mint new registration coupon codes for field distribution
                </p>
            </div>
            
            <div class="flex items-center gap-3 ml-14 md:ml-0">
                <a href="{{ route('coupons.index') }}" class="group px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Back to Registry
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Generation Form -->
        <div class="lg:col-span-8">
            <div class="relative group">
                <!-- Outer Glow -->
                <div class="absolute -inset-1 bg-gradient-to-r from-accent/20 to-purple-500/20 rounded-[3rem] blur opacity-25 group-hover:opacity-60 transition duration-700"></div>
                
                <div class="relative bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-10 overflow-hidden shadow-2xl shadow-slate-200/50 dark:shadow-none">
                    <!-- Background Illustration / Pattern -->
                    <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
                        <i class="fas fa-ticket-alt text-[120px] -rotate-12"></i>
                    </div>

                    <form action="{{ route('coupons.store') }}" method="POST" id="couponGenerationForm">
                        @csrf

                        <!-- Quantity Selector -->
                        <div class="mb-10 relative">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-black text-xs shadow-lg shadow-accent/20">1</div>
                                <label for="quantity" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Issuance Volume</label>
                            </div>
                            <div class="relative">
                                <i class="fas fa-boxes absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                                <input type="number" 
                                       class="w-full bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-2xl pl-16 pr-6 py-5 text-lg font-black text-slate-800 dark:text-white focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none" 
                                       id="quantity" 
                                       name="quantity" 
                                       min="1" 
                                       max="100" 
                                       value="{{ old('quantity', 1) }}" 
                                       required>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 flex gap-4">
                                    <span class="px-3 py-1 bg-accent/10 text-accent rounded-lg text-[10px] font-black uppercase tracking-tighter self-center">Single to Bulk</span>
                                </div>
                            </div>
                            <p class="mt-3 ml-12 text-[9px] font-bold text-slate-400 uppercase tracking-tighter leading-relaxed italic">
                                * Maximum issuance per request is 100 uniquely hashed vouchers.
                            </p>
                        </div>

                        <!-- Designation Restriction -->
                        <div class="mb-10 relative">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-black text-xs shadow-lg shadow-accent/20">2</div>
                                <label for="designation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Eligibility Filtering (Tier Control)</label>
                            </div>
                            <div class="relative">
                                <i class="fas fa-user-shield absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                                <select class="w-full bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-2xl pl-16 pr-12 py-5 text-sm font-black text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all appearance-none outline-none cursor-pointer" 
                                        id="designation" 
                                        name="designation" 
                                        required>
                                    <option value="" disabled selected>— Select Target Designation —</option>
                                    <option value="ro" {{ old('designation') == 'ro' ? 'selected' : '' }}>RO • Relationship Officer (₹199 Value)</option>
                                    <option value="rm" {{ old('designation') == 'rm' ? 'selected' : '' }}>RM • Relationship Manager (₹499 Value)</option>
                                    <option value="bm" {{ old('designation') == 'bm' ? 'selected' : '' }}>BM • Block Manager (₹999 Value)</option>
                                    <option value="dm" {{ old('designation') == 'dm' ? 'selected' : '' }}>DM • District Manager (₹999 Value)</option>
                                    <option value="any" {{ old('designation') == 'any' ? 'selected' : '' }}>ANY • Universal Voucher Access</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                            <!-- Expiration -->
                            <div class="relative">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-black text-xs shadow-lg shadow-accent/20">3</div>
                                    <label for="expires_at" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lifecycle Expiry</label>
                                </div>
                                <div class="relative">
                                    <i class="far fa-calendar-times absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                                    <input type="date" 
                                           class="w-full bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-2xl pl-16 pr-6 py-5 text-sm font-black text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none" 
                                           id="expires_at" 
                                           name="expires_at" 
                                           min="{{ date('Y-m-d') }}"
                                           value="{{ old('expires_at') }}">
                                </div>
                                <p class="mt-2 ml-4 text-[8px] font-bold text-slate-300 uppercase italic">* Leave blank for permanent validity.</p>
                            </div>

                            <!-- Notes -->
                            <div class="relative">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-black text-xs shadow-lg shadow-accent/20">4</div>
                                    <label for="notes" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Administrative Context</label>
                                </div>
                                <div class="relative">
                                    <i class="fas fa-pen-nib absolute left-6 top-[28px] text-slate-300 pointer-events-none"></i>
                                    <textarea class="w-full bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-2xl pl-16 pr-6 py-5 text-sm font-black text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none min-h-[66px] max-h-[120px]" 
                                              id="notes" 
                                              name="notes" 
                                              placeholder="Internal memo or distribution details...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-12">
                            <button type="submit" class="w-full group relative py-6 overflow-hidden rounded-[2rem] bg-slate-900 border border-white/10 shadow-2xl transition-all hover:scale-[1.01] active:scale-95">
                                <div class="absolute inset-0 bg-gradient-to-r from-accent to-purple-600 opacity-90 group-hover:opacity-100 transition-opacity"></div>
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-from)_0%,_transparent_70%)] from-white/20 to-transparent"></div>
                                
                                <span class="relative z-10 flex items-center justify-center gap-4 text-white font-black text-sm uppercase tracking-[0.2em]">
                                    Execute Generation
                                    <i class="fas fa-bolt group-hover:rotate-12 transition-transform"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informational Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <!-- How it works card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-[2rem] blur opacity-25 group-hover:opacity-40 transition duration-700"></div>
                <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 shadow-xl">
                    <h5 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-5 h-5 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-stream text-[8px]"></i>
                        </span>
                        Operational Flow
                    </h5>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                <i class="fas fa-microchip text-[10px]"></i>
                            </div>
                            <div>
                                <h6 class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-1">Minter Engine</h6>
                                <p class="text-[9px] font-bold text-slate-400 leading-relaxed uppercase tracking-tighter">System spawns cryptographically unique hashes starting with HF-CASH prefix.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                <i class="fas fa-paper-plane text-[10px]"></i>
                            </div>
                            <div>
                                <h6 class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-1">Secure Dispatch</h6>
                                <p class="text-[9px] font-bold text-slate-400 leading-relaxed uppercase tracking-tighter">Export codes to encrypted CSV for physical printing or area manager dispatch.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                <i class="fas fa-lock text-[10px]"></i>
                            </div>
                            <div>
                                <h6 class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest mb-1">Redeem Gate</h6>
                                <p class="text-[9px] font-bold text-slate-400 leading-relaxed uppercase tracking-tighter">Once validated on registration, the hash is burned and permanently logged.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Format Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-br from-accent/20 to-indigo-500/20 rounded-[2rem] blur opacity-20 group-hover:opacity-40 transition duration-700"></div>
                <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 shadow-xl text-center overflow-hidden">
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-accent/5 rounded-full blur-2xl"></div>
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Generated Payload Structure</h5>
                    
                    <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5 mb-4">
                        <code class="text-xl font-black text-accent tracking-widest">HF-CASH-A7K9M</code>
                    </div>
                    
                    <p class="text-[8px] font-bold text-slate-300 uppercase tracking-widest leading-loose">
                        Randomized Alpha-Numeric Entropy <br> 
                        <span class="text-accent underline decoration-dotted">HF-CASH</span> [Minter ID] • <span class="text-indigo-400 underline decoration-dotted">XXXXX</span> [Auth Key]
                    </p>
                </div>
            </div>

            <!-- Value Tier Info -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-[2rem] blur opacity-10 group-hover:opacity-30 transition"></div>
                <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 shadow-xl">
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Monetary Tier Mapping</h5>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Officer (RO)</span>
                            <span class="text-xs font-black text-slate-800 dark:text-white">₹199.00</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Manager (RM)</span>
                            <span class="text-xs font-black text-slate-800 dark:text-white">₹499.00</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Block/Dist (BM/DM)</span>
                            <span class="text-xs font-black text-slate-800 dark:text-white">₹999.00</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-500 text-[10px]"></i>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter italic">Vouchers grant 100% discount on joining fees.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom input styling for modern browsers */
input[type="date"]::-webkit-calendar-picker-indicator {
    @apply opacity-20 hover:opacity-100 transition-opacity cursor-pointer dark:invert;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    @apply opacity-10 flex;
}
</style>
@endsection
