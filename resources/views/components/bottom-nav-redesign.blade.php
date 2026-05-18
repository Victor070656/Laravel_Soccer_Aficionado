@props([
    'activeTab' => 'home',
])

<nav {{ $attributes->merge(['class' => 'fixed bottom-0 left-0 right-0 bg-surface-container-high border-t border-outline-variant z-40']) }}>
    <div class="flex justify-around items-center h-16 max-w-full">
        
        <!-- Home Tab -->
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ $activeTab === 'home' ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <x-icon name="home" size="md" filled="{{ $activeTab === 'home' }}" />
            <span class="text-label-sm">Home</span>
        </a>
        
        <!-- Trending Tab -->
        <a href="{{ route('trending') }}" 
           class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ $activeTab === 'trending' ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <x-icon name="trending" size="md" filled="{{ $activeTab === 'trending' }}" />
            <span class="text-label-sm">Trending</span>
        </a>
        
        <!-- Communities Tab -->
        <a href="{{ route('communities') }}" 
           class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ $activeTab === 'communities' ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <x-icon name="communities" size="md" filled="{{ $activeTab === 'communities' }}" />
            <span class="text-label-sm">Communities</span>
        </a>
        
        <!-- Match Rooms Tab -->
        <a href="{{ route('match-rooms') }}" 
           class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ $activeTab === 'match-rooms' ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <x-icon name="match-room" size="md" filled="{{ $activeTab === 'match-rooms' }}" />
            <span class="text-label-sm">Matches</span>
        </a>
        
        <!-- Profile Tab -->
        <a href="{{ route('profile.show') }}" 
           class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ $activeTab === 'profile' ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <x-icon name="profile" size="md" filled="{{ $activeTab === 'profile' }}" />
            <span class="text-label-sm">Profile</span>
        </a>
        
    </div>
</nav>
