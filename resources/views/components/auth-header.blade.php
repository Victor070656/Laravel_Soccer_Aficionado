@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center lg:text-left mb-8">
    <flux:heading size="xl" class="!text-3xl !font-extrabold !text-white !font-display uppercase tracking-tight">{{ $title }}</flux:heading>
    <flux:subheading class="mt-2 !text-on-surface-variant !text-base !font-sans">{{ $description }}</flux:subheading>
</div>
