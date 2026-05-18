@props([
    'live' => false,
    'class' => '',
])

<div 
    {{ $attributes->merge([
        'class' => 'glass-card rounded-lg border transition-all duration-300 ' . ($live ? 'card-live' : 'border-white/10') . ' ' . $class
    ]) }}
>
    {{ $slot }}
</div>
