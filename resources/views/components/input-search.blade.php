@props([
    'label' => '',
    'placeholder' => '',
    'icon' => 'search', // material icon name
    'class' => '',
])

@php
    $id = $attributes->get('id') ?? 'search_' . uniqid();
@endphp

<div class="relative">
    @if($label)
        <label for="{{ $id }}" class="block text-label-bold text-on-surface mb-2">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <span class="absolute left-3 top-3 text-on-surface-variant material-symbols-outlined text-xl pointer-events-none">
            {{ $icon }}
        </span>
        
        <input 
            type="search"
            id="{{ $id }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'input-recessed w-full rounded-md pl-10 ' . $class
            ]) }}
        />
    </div>
</div>
