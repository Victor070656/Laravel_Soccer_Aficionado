@props([
    'label' => '',
    'placeholder' => '',
    'type' => 'text',
    'error' => false,
    'success' => false,
    'hint' => '',
    'class' => '',
])

@php
    $id = $attributes->get('id') ?? 'input_' . uniqid();
    
    $stateClass = $error 
        ? 'border-b-error focus:border-b-error'
        : ($success 
            ? 'border-b-primary-container focus:border-b-primary-container'
            : '');
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $id }}" class="block text-label-bold text-on-surface mb-2">
            {{ $label }}
            @if($attributes->get('required'))
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'input-recessed w-full rounded-md ' . $stateClass . ' ' . $class
        ]) }}
    />
    
    @if($error && ($errorMessage = $attributes->get('error-message')))
        <p class="text-label-sm text-error">{{ $errorMessage }}</p>
    @endif
    
    @if($success && ($successMessage = $attributes->get('success-message')))
        <p class="text-label-sm text-primary-container">{{ $successMessage }}</p>
    @endif
    
    @if($hint)
        <p class="text-label-sm text-on-surface-variant">{{ $hint }}</p>
    @endif
</div>
