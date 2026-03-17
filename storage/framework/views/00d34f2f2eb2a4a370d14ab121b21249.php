

<?php $__env->startSection('title', 'Incentive Configurations'); ?>
<?php $__env->startSection('header_title', 'Incentive Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-6 max-w-3xl mx-auto h-full pb-20">

        
        <div
            class="flex mb-6 bg-white dark:bg-darkcard rounded-2xl shadow-lg border border-slate-200 dark:border-white/5 p-1.5 gap-2">
            <button onclick="switchTab('ta')" id="tab-ta"
                class="tab-btn flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl font-black text-sm uppercase tracking-widest transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                TA Based
            </button>
            <button onclick="switchTab('da')" id="tab-da"
                class="tab-btn flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl font-black text-sm uppercase tracking-widest transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                DA Based
            </button>
        </div>

        
        <div id="panel-ta" class="tab-panel">
            <div class="bg-white dark:bg-darkcard rounded-3xl shadow-xl p-8 border border-slate-200 dark:border-white/5">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 mb-3">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">TA Based Incentives</h3>
                    <p class="text-xs text-slate-400 mt-1">Travel Allowance — Daily salary given when attendance is marked
                        present</p>
                </div>

                <form action="<?php echo e(route('admin.incentive-configs.store-ta')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Target
                            Designation</label>
                        <select name="designation" id="ta_designation_select" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 transition-all font-bold">
                            <option value="">-- Select Designation --</option>
                            <option value="dm">District Manager (DM)</option>
                            <option value="bm">Block Manager (BM)</option>
                            <option value="rm">Relationship Manager (RM) / District Coordinator</option>
                            <option value="ro">Relationship Officer (RO)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="ta_section">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                    TA (Daily ₹)
                                </span>
                            </label>
                            <input type="number" name="ta_amount" id="ta_ta_amount" step="0.01" required placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 font-bold">
                            <p class="text-[10px] text-slate-400 mt-1">Fixed daily amount credited on attendance</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Medicines
                                (%)</label>
                            <input type="number" name="medicines_amount" id="ta_medicines_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pathology
                                (%)</label>
                            <input type="number" name="pathology_amount" id="ta_pathology_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Membership
                                (Fixed)</label>
                            <input type="number" name="membership_amount" id="ta_membership_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">OTs
                                (%)</label>
                            <input type="number" name="ots_amount" id="ta_ots_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 font-bold">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                            Save TA Configuration
                        </button>
                    </div>
                </form>

                
                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Maintenance</p>
                    <form action="<?php echo e(route('admin.incentive-configs.sync')); ?>" method="POST"
                        onsubmit="return confirm('Sync all past attendance records with current TA configs? This will update TA and Basic Incentives for all historical data.')">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="px-8 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center justify-center mx-auto space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span>Sync Past Attendance Records</span>
                        </button>
                        <p class="mt-4 text-[10px] text-slate-400 font-medium italic">
                            Use this after changing TA values to update existing records.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        
        <div id="panel-da" class="tab-panel hidden">
            <div class="bg-white dark:bg-darkcard rounded-3xl shadow-xl p-8 border border-slate-200 dark:border-white/5">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 mb-3">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">DA Based Incentives</h3>
                    <p class="text-xs text-slate-400 mt-1">Doctor Appointment — Salary earned per successful appointment
                        completion</p>
                </div>

                <form action="<?php echo e(route('admin.incentive-configs.store-da')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Target
                            Designation</label>
                        <select name="designation" id="da_designation_select" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 transition-all font-bold">
                            <option value="">-- Select Designation --</option>
                            <option value="dm">District Manager (DM)</option>
                            <option value="bm">Block Manager (BM)</option>
                            <option value="rm">Relationship Manager (RM) / District Coordinator</option>
                            <option value="ro">Relationship Officer (RO)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                DA Amount (₹ per Appointment)
                            </span>
                        </label>
                        <input type="number" name="da_amount" id="da_da_amount" step="0.01" required placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 font-bold text-2xl text-center">
                        <p class="text-[10px] text-slate-400 mt-1 text-center">Fixed amount credited when a doctor
                            appointment is marked as successful</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Medicines
                                (%)</label>
                            <input type="number" name="medicines_amount" id="da_medicines_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pathology
                                (%)</label>
                            <input type="number" name="pathology_amount" id="da_pathology_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Membership
                                (Fixed)</label>
                            <input type="number" name="membership_amount" id="da_membership_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">OTs
                                (%)</label>
                            <input type="number" name="ots_amount" id="da_ots_amount" step="0.01" required
                                placeholder="0.00"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                    </div>

                    
                    <div
                        class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-2xl p-5">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-blue-700 dark:text-blue-300 mb-1">How DA Incentives Work
                                </p>
                                <p class="text-[11px] text-blue-600/80 dark:text-blue-400/70 leading-relaxed">
                                    When a doctor appointment is successfully completed and marked as "Successful",
                                    the configured DA amount is automatically credited to the RO who created the
                                    appointment, and also to their RM, BM, and DM (each at their own configured rate).
                                    This applies to users in DAB (Doctor Appointment Based) salary mode.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                            Save DA Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const configs = <?php echo json_encode($globalConfig->keyBy('designation'), 15, 512) ?>;

        function switchTab(tab) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-accent', 'text-white', 'shadow-lg', 'shadow-accent/30');
                b.classList.add('text-slate-500', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-700');
            });

            // Show selected panel
            document.getElementById('panel-' + tab).classList.remove('hidden');
            // Activate selected tab
            const activeBtn = document.getElementById('tab-' + tab);
            activeBtn.classList.add('bg-accent', 'text-white', 'shadow-lg', 'shadow-accent/30');
            activeBtn.classList.remove('text-slate-500', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-700');
        }

        // TA tab: populate fields when designation changes
        document.getElementById('ta_designation_select').addEventListener('change', function () {
            const designation = this.value;
            const fields = ['ta_amount', 'medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount'];
            const config = configs[designation];

            fields.forEach(field => {
                const input = document.getElementById('ta_' + field);
                if (config && config[field] !== undefined) {
                    input.value = parseFloat(config[field]).toFixed(2);
                } else {
                    input.value = '';
                }
            });

            // Show TA field only for RO
            const taSection = document.getElementById('ta_section');
            const taInput = document.getElementById('ta_ta_amount');
            if (designation === 'ro') {
                taSection.classList.remove('hidden');
                taInput.setAttribute('required', 'required');
            } else {
                taSection.classList.add('hidden');
                taInput.removeAttribute('required');
                taInput.value = '0.00';
            }
        });

        // DA tab: populate fields when designation changes
        document.getElementById('da_designation_select').addEventListener('change', function () {
            const designation = this.value;
            const fields = ['da_amount', 'medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount'];
            const config = configs[designation];

            fields.forEach(field => {
                const input = document.getElementById('da_' + field);
                if (config && config[field] !== undefined) {
                    input.value = parseFloat(config[field]).toFixed(2);
                } else {
                    input.value = '';
                }
            });
        });

        // Initialize: activate TA tab by default
        switchTab('ta');
        document.getElementById('ta_designation_select').dispatchEvent(new Event('change'));
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\admin\incentive_configs\index.blade.php ENDPATH**/ ?>