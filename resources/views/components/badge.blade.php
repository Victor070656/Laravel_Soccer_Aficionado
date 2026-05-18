@props([
    'text' => '',
    'active' => false,
    'variant' => 'default', // default, active, success
])

@php
    $variantClasses = match($variant) {
        'active' => 'bg-primary-container/20 border-primary-container text-primary-container',
        'success' => 'bg-primary-container/15 border-primary-container text-primary-fixed',
        default => 'bg-white/10 border-white/20 text-on-surface',
    };
    
    $baseClasses = 'badge-pill ' . $variantClasses;
@endphp

<span 
    {{ $attributes->merge([
        'class' => $baseClasses . ($active ? ' active' : '')
    ]) }}
>
    {{ $text ?? $slot }}
</span>
