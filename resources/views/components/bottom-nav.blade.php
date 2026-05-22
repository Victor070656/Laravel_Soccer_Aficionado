<!-- Bottom Navigation - Mobile First (5 Tabs) -->
<nav
    class="lg:hidden fixed inset-x-0 bottom-0 z-50 bg-gradient-to-t from-surface-container via-surface-container/95 to-surface-container/90 border-t border-outline-variant/30 shadow-[0_-8px_32px_rgba(0,0,0,0.4)] backdrop-blur-lg safe-area-inset-bottom">
    <div class="mx-auto max-w-full px-2 py-2 sm:py-3">
        <div
            class="flex items-center justify-around gap-1 rounded-3xl bg-surface-container-high/50 border border-white/10 px-2 py-2 sm:px-3 sm:py-3 shadow-[0_8px_24px_rgba(0,0,0,0.32)] backdrop-blur-xl">
            <!-- Home -->
            <a href="{{ route('feed') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center flex-1 gap-1 rounded-3xl px-3 py-2 sm:py-3 transition-all duration-300 min-h-[3.5rem] sm:min-h-[4rem]
                    {{ request()->routeIs('feed') ? 'bg-primary/20 text-primary shadow-[0_0_0_2px_rgba(74,255,153,0.25)]' : 'text-on-surface-variant hover:bg-white/8 hover:text-on-surface' }}">
                <flux:icon.home
                    class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:scale-110 {{ request()->routeIs('feed') ? 'text-primary' : '' }}" />
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wide truncate">Feed</span>
                <span
                    class="absolute bottom-0 h-1 rounded-full bg-primary transition-all duration-300 {{ request()->routeIs('feed') ? 'w-8 opacity-100' : 'w-0 opacity-0' }}"></span>
            </a>

            <!-- Trending -->
            <a href="{{ route('trending') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center flex-1 gap-1 rounded-3xl px-3 py-2 sm:py-3 transition-all duration-300 min-h-[3.5rem] sm:min-h-[4rem]
                    {{ request()->routeIs('trending') ? 'bg-primary/20 text-primary shadow-[0_0_0_2px_rgba(74,255,153,0.25)]' : 'text-on-surface-variant hover:bg-white/8 hover:text-on-surface' }}">
                <flux:icon.fire
                    class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:scale-110 {{ request()->routeIs('trending') ? 'text-primary' : '' }}" />
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wide truncate">Trending</span>
                <span
                    class="absolute bottom-0 h-1 rounded-full bg-primary transition-all duration-300 {{ request()->routeIs('trending') ? 'w-8 opacity-100' : 'w-0 opacity-0' }}"></span>
            </a>

            <!-- Communities -->
            <a href="{{ route('communities.index') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center flex-1 gap-1 rounded-3xl px-3 py-2 sm:py-3 transition-all duration-300 min-h-[3.5rem] sm:min-h-[4rem]
                    {{ request()->routeIs('communities.*') ? 'bg-primary/20 text-primary shadow-[0_0_0_2px_rgba(74,255,153,0.25)]' : 'text-on-surface-variant hover:bg-white/8 hover:text-on-surface' }}">
                <flux:icon.user-group
                    class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:scale-110 {{ request()->routeIs('communities.*') ? 'text-primary' : '' }}" />
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wide truncate">Groups</span>
                <span
                    class="absolute bottom-0 h-1 rounded-full bg-primary transition-all duration-300 {{ request()->routeIs('communities.*') ? 'w-8 opacity-100' : 'w-0 opacity-0' }}"></span>
            </a>

            <!-- Match Rooms -->
            <a href="{{ route('matches.live') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center flex-1 gap-1 rounded-3xl px-3 py-2 sm:py-3 transition-all duration-300 min-h-[3.5rem] sm:min-h-[4rem]
                    {{ request()->routeIs('matches.*') ? 'bg-primary/20 text-primary shadow-[0_0_0_2px_rgba(74,255,153,0.25)]' : 'text-on-surface-variant hover:bg-white/8 hover:text-on-surface' }}">
                <div class="relative">
                    <flux:icon.bolt
                        class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:scale-110 {{ request()->routeIs('matches.*') ? 'text-primary' : '' }}" />
                    @if (($liveMatchCount ?? 0) > 0)
                        <span
                            class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-error text-[10px] font-black text-white shadow-lg shadow-error/50 ring-2 ring-surface-container-high">
                            {{ min($liveMatchCount ?? 0, 9) }}
                        </span>
                    @endif
                </div>
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wide truncate">Live</span>
                <span
                    class="absolute bottom-0 h-1 rounded-full bg-primary transition-all duration-300 {{ request()->routeIs('matches.*') ? 'w-8 opacity-100' : 'w-0 opacity-0' }}"></span>
            </a>

            <!-- Profile -->
            <a href="{{ auth()->check() ? (auth()->user()->username ? route('profiles.show', auth()->user()) : route('profile.edit')) : route('login') }}"
                wire:navigate
                class="group relative flex flex-col items-center justify-center flex-1 gap-1 rounded-3xl px-3 py-2 sm:py-3 transition-all duration-300 min-h-[3.5rem] sm:min-h-[4rem]
                    {{ request()->routeIs('profiles.*') ? 'bg-primary/20 text-primary shadow-[0_0_0_2px_rgba(74,255,153,0.25)]' : 'text-on-surface-variant hover:bg-white/8 hover:text-on-surface' }}">
                @if (auth()->check() && auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="Profile"
                        class="h-6 w-6 sm:h-7 sm:w-7 rounded-lg object-cover ring-2 ring-transparent transition-transform group-hover:scale-110 {{ request()->routeIs('profiles.*') ? 'ring-primary' : '' }}">
                @else
                    <flux:icon.user
                        class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:scale-110 {{ request()->routeIs('profiles.*') ? 'text-primary' : '' }}" />
                @endif
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wide truncate">Profile</span>
                <span
                    class="absolute bottom-0 h-1 rounded-full bg-primary transition-all duration-300 {{ request()->routeIs('profiles.*') ? 'w-8 opacity-100' : 'w-0 opacity-0' }}"></span>
            </a>
        </div>
    </div>
</nav>
