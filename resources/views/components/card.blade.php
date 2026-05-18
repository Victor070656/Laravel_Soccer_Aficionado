@props([
    'class' => '',
    'variant' => 'default', // default, elevated, live
    'padding' => 'md', // sm, md, lg
])

@php
    $paddingClasses = match($padding) {
        'sm' => 'p-4',
        'lg' => 'p-8',
        default => 'p-6',
    };
    
    $variantClasses = match($variant) {
        'elevated' => 'glass-card-active',
        'live' => 'glass-card card-live',
        default => 'glass-card border border-white/10',
    };
    
    $baseClasses = $variantClasses . ' rounded-lg ' . $paddingClasses;
@endphp

<div 
    {{ $attributes->merge([
        'class' => $baseClasses . ' ' . $class
    ]) }}
>
    {{ $slot }}
</div>
