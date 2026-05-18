@props([
    'disabled' => false,
    'size' => 'md',
    'class' => '',
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-4 py-2 text-sm',
        'lg' => 'px-8 py-4 text-lg',
        default => 'px-6 py-3 text-base',
    };
    
    $baseClasses = 'btn-secondary font-label-bold rounded-lg transition-all duration-200 ' . $sizeClasses;
    $finalClasses = $disabled ? $baseClasses . ' opacity-50 cursor-not-allowed' : $baseClasses;
@endphp

<button 
    @disabled($disabled)
    {{ $attributes->merge(['class' => $finalClasses . ' ' . $class]) }}
>
    {{ $slot }}
</button>
