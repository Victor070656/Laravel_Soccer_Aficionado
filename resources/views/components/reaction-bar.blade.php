@props([
    'buttons' => [],
    'totalCount' => 0,
    'showTotal' => true,
])

<div class="flex items-center gap-2 flex-wrap">
    @foreach ($buttons as $button)
        <x-reaction-button :emoji="$button['emoji']" :label="$button['label']" :count="$button['count']" :active="$button['active']" :action="$button['action'] ?? null" />
    @endforeach

    @if ($showTotal)
        <span class="ml-1 text-xs font-semibold text-on-surface-variant">
            {{ $totalCount }}
        </span>
    @endif
</div>
