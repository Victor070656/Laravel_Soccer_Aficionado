@props([
    'title' => 'Soccer Aficionado',
    'showSearch' => true,
])

<header {{ $attributes->merge(['class' => 'sticky top-0 z-30 w-full backdrop-blur-lg bg-surface-container-low/80 border-b border-outline-variant']) }}>
    <div class="container-stadium flex items-center justify-between h-16">
        
        <!-- Logo & Title -->
        <div class="flex items-center gap-3">
            <x-icon name="pitch" size="lg" class="text-primary-container" />
            <h1 class="text-headline-md text-neon hidden sm:block">{{ $title }}</h1>
        </div>
        
        <!-- Search Bar (Desktop) -->
        @if($showSearch)
            <div class="hidden md:flex flex-1 max-w-sm mx-8">
                <x-input-search placeholder="Search clubs, players, debates..." />
            </div>
        @endif
        
        <!-- Navigation Actions -->
        <div class="flex items-center gap-4">
            
            <!-- Search Icon (Mobile) -->
            @if($showSearch)
                <button class="md:hidden p-2 hover:bg-surface-bright rounded-lg transition-colors" aria-label="Search">
                    <x-icon name="search" />
                </button>
            @endif
            
            <!-- Notifications -->
            <button class="relative p-2 hover:bg-surface-bright rounded-lg transition-colors group" aria-label="Notifications">
                <x-icon name="bell" class="group-hover:text-primary-container" />
                <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full" aria-label="New notifications"></span>
            </button>
            
            <!-- Settings -->
            <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors" aria-label="Settings">
                <x-icon name="settings" />
            </button>
            
            <!-- User Menu (Livewire component slot) -->
            <div class="ml-2">
                {{ $slot }}
            </div>
            
        </div>
    </div>
    
    <!-- Mobile Search Bar -->
    @if($showSearch)
        <div class="md:hidden px-4 pb-4">
            <x-input-search placeholder="Search..." />
        </div>
    @endif
</header>
