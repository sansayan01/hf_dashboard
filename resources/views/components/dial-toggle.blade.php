@props(['name' => null, 'checked' => false, 'onchange' => null, 'value' => 1, 'id' => null, 'class' => ''])

@php
    $inputId = $id ?? 'dial_' . Str::random(8);
    $radioName = 'radio_' . ($id ?? Str::random(8));
@endphp

<div class="dial-container {{ $class }}">
    <label class="dial-label">
        <input type="radio" name="{{ $radioName }}" class="dial-input dial-input-off" {{ !$checked ? 'checked' : '' }}
            onchange="document.getElementById('{{ $inputId }}').checked = false; document.getElementById('{{ $inputId }}').dispatchEvent(new Event('change'));">
        <div class="dial-btn dial-btn-off">OFF</div>
    </label>
    <label class="dial-label">
        <input type="radio" name="{{ $radioName }}" class="dial-input dial-input-on" {{ $checked ? 'checked' : '' }}
            onchange="document.getElementById('{{ $inputId }}').checked = true; document.getElementById('{{ $inputId }}').dispatchEvent(new Event('change'));">
        <div class="dial-btn dial-btn-on">ON</div>
    </label>

    {{-- Hidden checkbox that actually holds the form value --}}
    <input type="checkbox" @if($name) name="{{ $name }}" @endif id="{{ $inputId }}" value="{{ $value }}"
        class="hidden role-permission-checkbox" {{ $checked ? 'checked' : '' }} @if($onchange)
        onchange="{{ $onchange }}" @endif>
</div>