@props([
    'user' => null,
    'stats' => null,
    'achievements' => [],
])

@php
    $stats = $stats ?? [
        'fanXp' => 1250,
        'rank' => 'Active',
        'streak' => 7,
        'followers' => 342,
        'following' => 128,
    ];
@endphp

<x-card-elevated class="overflow-hidden">
    
    <!-- Background Banner -->
    <div class="h-32 bg-gradient-to-r from-primary-container/20 to-tertiary/10 relative">
        <!-- Decorative pattern -->
        <div class="absolute inset-0 opacity-10 turf-pattern"></div>
    </div>
    
    <!-- Profile Content (overlapped) -->
    <div class="px-6 pb-6">
        
        <!-- Avatar & Header -->
        <div class="flex gap-4 mb-6 -mt-16 relative z-10">
            
            <!-- Avatar -->
            <div class="w-24 h-24 rounded-xl bg-surface-bright border-4 border-surface-container flex-shrink-0 flex items-center justify-center">
                <x-icon name="profile" size="xl" class="text-primary-container" />
            </div>
            
            <!-- User Info -->
            <div class="flex-1 pt-4">
                <h2 class="text-headline-md text-on-surface">{{ $user?->name ?? 'User Profile' }}</h2>
                <div class="flex items-center gap-2 mt-2">
                    <x-badge text="{{ $stats['rank'] }}" variant="active" />
                    @if($user?->verified)
                        <x-icon name="verified" size="sm" class="text-primary-container" />
                    @endif
                </div>
            </div>
            
            <!-- Action Button -->
            <button class="p-3 hover:bg-surface-bright rounded-lg transition-colors self-start">
                <x-icon name="more" />
            </button>
        </div>
        
        <!-- Bio -->
        @if($user?->bio)
            <p class="text-body-md text-on-surface mb-6">{{ $user->bio }}</p>
        @endif
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b border-outline-variant">
            
            <!-- Fan XP -->
            <div class="text-center">
                <p class="text-headline-md text-primary-container font-display">
                    {{ number_format($stats['fanXp']) }}
                </p>
                <p class="text-label-sm text-on-surface-variant">Fan XP</p>
            </div>
            
            <!-- Streak -->
            <div class="text-center">
                <p class="text-headline-md text-primary-container font-display">
                    {{ $stats['streak'] }}🔥
                </p>
                <p class="text-label-sm text-on-surface-variant">Day Streak</p>
            </div>
            
            <!-- Club Loyalty -->
            <div class="text-center">
                <x-badge text="{{ $user?->favorite_club ?? 'No Club' }}" active />
                <p class="text-label-sm text-on-surface-variant mt-1">Loyalty</p>
            </div>
        </div>
        
        <!-- Social Stats -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="#" class="group">
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-md text-on-surface group-hover:text-primary-container transition-colors">
                        {{ number_format($stats['followers']) }}
                    </span>
                    <span class="text-label-sm text-on-surface-variant">Followers</span>
                </div>
            </a>
            
            <a href="#" class="group">
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-md text-on-surface group-hover:text-primary-container transition-colors">
                        {{ number_format($stats['following']) }}
                    </span>
                    <span class="text-label-sm text-on-surface-variant">Following</span>
                </div>
            </a>
        </div>
        
        <!-- Achievements/Badges -->
        @if($achievements)
            <div class="mb-6">
                <p class="text-label-bold text-on-surface mb-3">Achievements</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($achievements as $achievement)
                        <div class="relative group cursor-pointer" title="{{ $achievement['name'] }}">
                            <div class="w-12 h-12 rounded-lg bg-surface-bright flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                {{ $achievement['icon'] ?? '🏆' }}
                            </div>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-surface-container rounded text-label-sm text-on-surface opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                                {{ $achievement['name'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <x-button-primary class="flex-1">
                Follow
            </x-button-primary>
            <x-button-secondary class="flex-1">
                Message
            </x-button-secondary>
        </div>
    </div>
</x-card-elevated>
