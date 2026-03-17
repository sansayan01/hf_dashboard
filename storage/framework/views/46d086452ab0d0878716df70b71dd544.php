<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Action - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 900px;
            padding: 60px;
            position: relative;
            z-index: 1;
        }

        .option-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 30px;
            padding: 40px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-align: center;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .option-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .option-card.id-card:hover {
            border-color: #f43f5e;
            background: linear-gradient(135deg, rgba(244, 63, 94, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }

        .option-card.offer-letter:hover {
            border-color: #0ea5e9;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            transition: all 0.3s;
        }

        .option-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .bg-blur-blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: #0ea5e9;
            filter: blur(120px);
            opacity: 0.15;
            border-radius: 50%;
            z-index: 0;
        }

        .blob-1 {
            top: -100px;
            left: -100px;
            background: #f43f5e;
        }

        .blob-2 {
            bottom: -100px;
            right: -100px;
            background: #0ea5e9;
        }
    </style>
</head>

<body>
    <div class="bg-blur-blob blob-1"></div>
    <div class="bg-blur-blob blob-2"></div>

    <div class="glass-card">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black text-white mb-4 tracking-tight">Bulk Actions</h1>
            <p class="text-slate-400 text-lg font-medium">Select the document type you wish to generate for <span
                    class="text-white"><?php echo e(count($users)); ?> selected members</span>.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- ID Card Option -->
            <form action="<?php echo e(route('users.print-all-id-cards')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php $__currentLoopData = $selected_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="selected_users[]" value="<?php echo e($id); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="submit" class="w-full h-full text-left focus:outline-none">
                    <div class="option-card id-card group">
                        <div class="icon-box bg-rose-500/20 text-rose-500 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.333 0 4 1 4 3H6c0-2 2.667-3 4-3z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">Print ID Cards</h3>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed">Generate printable identity cards
                            in A4 grid format for all selected members.</p>
                    </div>
                </button>
            </form>

            <!-- Offer Letter Option -->
            <form action="<?php echo e(route('users.bulk-offer-letters')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php $__currentLoopData = $selected_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="selected_users[]" value="<?php echo e($id); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="submit" class="w-full h-full text-left focus:outline-none">
                    <div class="option-card offer-letter group">
                        <div class="icon-box bg-sky-500/20 text-sky-400 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 10h.01M9 14h.01M12 10h.01M12 14h.01M15 10h.01M15 14h.01" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">Offer Letters ZIP</h3>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed">Generate and download all offer
                            letters bundled in a single high-quality ZIP file.</p>
                    </div>
                </button>
            </form>
        </div>

        <div class="mt-16 text-center">
            <a href="javascript:history.back()"
                class="text-slate-500 hover:text-white transition-colors text-sm font-black uppercase tracking-widest flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Go Back</span>
            </a>
        </div>
    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\users\bulk_print_selection.blade.php ENDPATH**/ ?>