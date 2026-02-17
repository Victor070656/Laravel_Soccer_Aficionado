@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl" class="!text-2xl !font-bold">{{ $title }}</flux:heading>
    <flux:subheading class="mt-2">{{ $description }}</flux:subheading>
</div>
