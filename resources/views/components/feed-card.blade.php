@props([
    'author' => null,
    'club' => null,
    'timestamp' => null,
    'content' => '',
    'type' => 'post', // post, poll, match-reaction, debate
    'engagement' => null, // likes, comments, shares
    'isPinned' => false,
    'isSponsored' => false,
])

@php
    $typeClasses = match($type) {
        'poll' => 'border-l-4 border-l-tertiary',
        'match-reaction' => 'border-l-4 border-l-primary-container',
        'debate' => 'border-l-4 border-l-secondary',
        default => '',
    };
    
    $typeLabel = match($type) {
        'poll' => '📊 Poll',
        'match-reaction' => '⚡ Live',
        'debate' => '💬 Debate',
        default => '📝 Post',
    };
@endphp

<x-card variant="default" class="group {{ $typeClasses }} space-y-3">
    
    <!-- Header: Author & Metadata -->
    <div class="flex items-start justify-between">
        <div class="flex gap-3 flex-1">
            <!-- Avatar -->
            <div class="w-10 h-10 rounded-full bg-surface-bright flex-shrink-0"></div>
            
            <!-- Author Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="font-label-bold truncate">{{ $author?->name ?? 'Anonymous' }}</span>
                    @if($author?->is_verified)
                        <x-icon name="verified" size="sm" class="text-primary-container flex-shrink-0" />
                    @endif
                    @if($club)
                        <span class="text-label-sm px-2 py-1 rounded-full bg-surface-bright text-on-surface-variant">
                            {{ $club }}
                        </span>
                    @endif
                </div>
                <p class="text-label-sm text-on-surface-variant truncate">
                    {{ $timestamp ?? now()->diffForHumans() }}
                </p>
            </div>
        </div>
        
        <!-- Pins & Type Badges -->
        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            @if($isPinned)
                <span class="text-xs px-2 py-1 rounded bg-primary-container/20 text-primary-container">Pinned</span>
            @endif
            @if($isSponsored)
                <span class="text-xs px-2 py-1 rounded bg-tertiary/20 text-on-tertiary">Sponsored</span>
            @endif
            @if($type !== 'post')
                <span class="text-xs px-2 py-1 rounded bg-surface-bright text-on-surface-variant">
                    {{ $typeLabel }}
                </span>
            @endif
        </div>
    </div>
    
    <!-- Content -->
    <div class="text-body-md text-on-surface leading-relaxed">
        {{ $content }}
    </div>
    
    <!-- Type-Specific Content Slot -->
    @if($type === 'poll')
        <div class="space-y-2">
            {{ $slot }}
        </div>
    @elseif($type === 'match-reaction')
        <div class="bg-surface-bright/50 rounded-lg p-3 border-l-4 border-l-primary-container">
            {{ $slot }}
        </div>
    @endif
    
    <!-- Engagement Footer -->
    <div class="flex items-center justify-between pt-2 border-t border-outline-variant text-on-surface-variant">
        
        <!-- Engagement Stats -->
        @if($engagement)
            <div class="flex gap-4 text-label-sm">
                @if($engagement['likes'])
                    <button class="flex items-center gap-1 hover:text-primary-container transition-colors">
                        <x-icon name="heart" size="sm" />
                        <span>{{ $engagement['likes'] }}</span>
                    </button>
                @endif
                
                @if($engagement['comments'])
                    <button class="flex items-center gap-1 hover:text-primary-container transition-colors">
                        <x-icon name="comment" size="sm" />
                        <span>{{ $engagement['comments'] }}</span>
                    </button>
                @endif
                
                @if($engagement['shares'])
                    <button class="flex items-center gap-1 hover:text-primary-container transition-colors">
                        <x-icon name="share" size="sm" />
                        <span>{{ $engagement['shares'] }}</span>
                    </button>
                @endif
            </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors" aria-label="Like">
                <x-icon name="heart" size="sm" />
            </button>
            <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors" aria-label="Comment">
                <x-icon name="comment" size="sm" />
            </button>
            <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors" aria-label="Share">
                <x-icon name="share" size="sm" />
            </button>
        </div>
    </div>
</x-card>
