@props(['emoji', 'label', 'count' => 0, 'active' => false, 'action' => null])

<button type="button" {{ $action ? $attributes->merge(['wire:click' => $action]) : $attributes }}
    class="focus-ring reaction-button inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold transition-all duration-200 {{ $active ? 'text-on-primary bg-gradient-to-r from-primary/80 to-primary/60 glow-primary scale-105' : 'text-on-surface-variant hover:text-primary hover:bg-primary/15 hover:glow-primary hover:scale-105' }}">
    <span class="text-base">{{ $emoji }}</span>
    <span>{{ $count }}</span>
    <span class="sr-only">{{ $label }}</span>
</button>
