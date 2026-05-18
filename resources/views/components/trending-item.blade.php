@props([
    'title' => '',
    'category' => 'Trending', // Club, Debate, Player, Match
    'postCount' => 0,
    'icon' => 'trending',
])

<a {{ $attributes->merge(['class' => 'group block']) }}>
    <x-card class="h-full hover:glass-card-active transition-all cursor-pointer">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <p class="text-label-bold text-on-surface-variant mb-1">{{ $category }}</p>
                <h3 class="text-headline-md text-on-surface group-hover:text-primary-container transition-colors line-clamp-2">
                    {{ $title }}
                </h3>
            </div>
            <x-icon :name="$icon" size="lg" class="text-primary-container flex-shrink-0 ml-2" />
        </div>
        
        <!-- Stats -->
        <div class="flex items-center justify-between pt-3 border-t border-outline-variant">
            <p class="text-label-sm text-on-surface-variant">
                {{ number_format($postCount) }} {{ Str::plural('post', $postCount) }}
            </p>
            <x-icon name="forward" size="sm" class="text-on-surface-variant group-hover:text-primary-container transition-colors" />
        </div>
        
    </x-card>
</a>
