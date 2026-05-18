@props([
    'label' => '',
    'placeholder' => '',
    'rows' => 4,
    'error' => false,
    'success' => false,
    'maxLength' => null,
    'class' => '',
])

@php
    $id = $attributes->get('id') ?? 'textarea_' . uniqid();
    $charCount = $maxLength ? (strlen($attributes->get('value') ?? '') . '/' . $maxLength) : null;
@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $id }}" class="block text-label-bold text-on-surface">
            {{ $label }}
        </label>
    @endif

    <textarea 
        id="{{ $id }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($maxLength) maxlength="{{ $maxLength }}" @endif
        {{ $attributes->merge([
            'class' => 'input-recessed w-full rounded-md resize-none ' . $class
        ]) }}
    ></textarea>
    
    @if($maxLength)
        <p class="text-label-sm text-on-surface-variant text-right">{{ $charCount ?? '0/' . $maxLength }}</p>
    @endif
</div>
