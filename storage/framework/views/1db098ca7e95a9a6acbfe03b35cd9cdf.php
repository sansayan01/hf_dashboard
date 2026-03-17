<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name' => null, 'checked' => false, 'onchange' => null, 'value' => 1, 'id' => null, 'class' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name' => null, 'checked' => false, 'onchange' => null, 'value' => 1, 'id' => null, 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $inputId = $id ?? 'dial_' . Str::random(8);
    $radioName = 'radio_' . ($id ?? Str::random(8));
?>

<div class="dial-container <?php echo e($class); ?>">
    <label class="dial-label">
        <input type="radio" name="<?php echo e($radioName); ?>" class="dial-input dial-input-off" <?php echo e(!$checked ? 'checked' : ''); ?>

            onchange="document.getElementById('<?php echo e($inputId); ?>').checked = false; document.getElementById('<?php echo e($inputId); ?>').dispatchEvent(new Event('change'));">
        <div class="dial-btn dial-btn-off">OFF</div>
    </label>
    <label class="dial-label">
        <input type="radio" name="<?php echo e($radioName); ?>" class="dial-input dial-input-on" <?php echo e($checked ? 'checked' : ''); ?>

            onchange="document.getElementById('<?php echo e($inputId); ?>').checked = true; document.getElementById('<?php echo e($inputId); ?>').dispatchEvent(new Event('change'));">
        <div class="dial-btn dial-btn-on">ON</div>
    </label>

    
    <input type="checkbox" <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?> id="<?php echo e($inputId); ?>" value="<?php echo e($value); ?>"
        class="hidden role-permission-checkbox" <?php echo e($checked ? 'checked' : ''); ?> <?php if($onchange): ?>
        onchange="<?php echo e($onchange); ?>" <?php endif; ?>>
</div><?php /**PATH C:\xampp\htdocs\HF\resources\views\components\dial-toggle.blade.php ENDPATH**/ ?>