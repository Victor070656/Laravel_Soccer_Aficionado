@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-primary dark:text-primary bg-primary/10 border border-primary/20 rounded-xl p-3 mb-4']) }}>
        {{ $status }}
    </div>
@endif
