<!-- Bottom Navigation - Mobile First (5 Tabs) -->
<flux:navlist class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface-container border-t border-outline-variant/40 backdrop-blur-md">
    <div class="flex items-center justify-around px-2 py-1.5 max-w-screen-sm mx-auto">
        <!-- Home -->
        <a href="{{ route('feed') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-all
                {{ request()->routeIs('feed') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <flux:icon home class="h-5 w-5 {{ request()->routeIs('feed') ? 'text-primary-container' : '' }}" />
            <span class="text-label-sm font-medium">Home</span>
        </a>

        <!-- Trending -->
        <a href="{{ route('trending') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-all
                {{ request()->routeIs('trending') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <flux:icon hashtag class="h-5 w-5 {{ request()->routeIs('trending') ? 'text-primary-container' : '' }}" />
            <span class="text-label-sm font-medium">Trending</span>
        </a>

        <!-- Communities -->
        <a href="{{ route('communities.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-all
                {{ request()->routeIs('communities.*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <flux:icon user-group class="h-5 w-5 {{ request()->routeIs('communities.*') ? 'text-primary-container' : '' }}" />
            <span class="text-label-sm font-medium">Communities</span>
        </a>

        <!-- Match Rooms -->
        <a href="{{ route('matches.live') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-all relative
                {{ request()->routeIs('matches.*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            <flux:icon bolt class="h-5 w-5 {{ request()->routeIs('matches.*') ? 'text-primary-container' : '' }}" />
            @if($liveMatchCount > 0)
                <span class="absolute -top-0.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-error text-[10px] font-bold text-white">
                    {{ min($liveMatchCount, 9) }}
                </span>
            @endif
            <span class="text-label-sm font-medium">Rooms</span>
        </a>

        <!-- Profile -->
        <a href="{{ auth()->check() ? route('profiles.show', auth()->user()) : route('login') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg transition-all
                {{ request()->routeIs('profiles.*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
            @if(auth()->check() && auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}"
                     alt=""
                     class="h-5 w-5 rounded-lg object-cover {{ request()->routeIs('profiles.*') ? 'ring-2 ring-primary-container' : '' }}">
            @else
                <flux:icon user class="h-5 w-5 {{ request()->routeIs('profiles.*') ? 'text-primary-container' : '' }}" />
            @endif
            <span class="text-label-sm font-medium">Profile</span>
        </a>
    </div>
</flux:navlist>
