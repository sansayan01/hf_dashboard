

<?php $__env->startSection('title', 'Incentive Configurations'); ?>
<?php $__env->startSection('header_title', 'Incentive Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-6 max-w-2xl mx-auto h-full pb-20">
        <div class="bg-white dark:bg-darkcard rounded-3xl shadow-xl p-8 border border-slate-200 dark:border-white/5">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center justify-center">
                <svg class="w-6 h-6 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
                Incentive Configuration
            </h3>

            <form action="<?php echo e(route('admin.incentive-configs.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Target
                        User</label>
                    <select name="designation" id="designation_select" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent transition-all font-bold">
                        <option value="">-- Select Designation --</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="hs">Head of State (HS)</option>
                        <option value="dm">District Manager (DM)</option>
                        <option value="bm">Block Manager (BM)</option>
                        <option value="rm">Relationship Manager (RM)</option>
                        <option value="ro">Relationship Officer (RO)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Medicines
                            (%)</label>
                        <input type="number" name="medicines_amount" id="medicines_amount" step="0.01" required
                            placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pathology
                            (%)</label>
                        <input type="number" name="pathology_amount" id="pathology_amount" step="0.01" required
                            placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Membership
                            (Fixed)</label>
                        <input type="number" name="membership_amount" id="membership_amount" step="0.01" required
                            placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">OTs
                            (%)</label>
                        <input type="number" name="ots_amount" id="ots_amount" step="0.01" required placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                    <div id="ta_field_wrapper" style="display: none;">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">TA
                            (Rupees)</label>
                        <input type="number" name="ta_amount" id="ta_amount" step="0.01" required placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-800 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-accent hover:bg-accent/90 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl shadow-lg shadow-accent/20 transition-all active:scale-95">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const configs = <?php echo json_encode($globalConfig->keyBy('designation'), 15, 512) ?>;

        document.getElementById('designation_select').addEventListener('change', function () {
            const designation = this.value;
            const fields = ['medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount', 'ta_amount'];
            const config = configs[designation];

            // Show TA field only for RO
            const taWrapper = document.getElementById('ta_field_wrapper');
            const taInput = document.getElementById('ta_amount');
            if (designation === 'ro') {
                taWrapper.style.display = 'block';
                taInput.required = true;
            } else {
                taWrapper.style.display = 'none';
                taInput.required = false;
                taInput.value = '0.00'; // Reset if not RO
            }

            fields.forEach(field => {
                const input = document.getElementById(field);
                if (config && config[field] !== undefined) {
                    input.value = parseFloat(config[field]).toFixed(2);
                } else if (field !== 'ta_amount' || designation === 'ro') {
                    input.value = '';
                }
            });
        });

        // Trigger change on load to set initial state
        document.getElementById('designation_select').dispatchEvent(new Event('change'));
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/admin/incentive_configs/index.blade.php ENDPATH**/ ?>