@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Soccer Aficionado" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/25">
            <x-app-logo-icon class="size-5 fill-current text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Soccer Aficionado" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/25">
            <x-app-logo-icon class="size-5 fill-current text-white" />
        </x-slot>
    </flux:brand>
@endif
