<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="ui-page">
        <div class="ui-page-inner">
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts::app.sidebar>
