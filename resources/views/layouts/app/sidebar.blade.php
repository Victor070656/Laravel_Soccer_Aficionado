<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-surface">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-outline-variant/40 bg-surface-container-low">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="newspaper" :href="route('posts.index')" :current="request()->routeIs('posts.*')" wire:navigate>
                        {{ __('Feed') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="trophy" :href="route('matches.index')" :current="request()->routeIs('matches.*')" wire:navigate>
                        {{ __('Matches') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="shield-check" :href="route('clubs.index')" :current="request()->routeIs('clubs.*')" wire:navigate>
                        {{ __('Clubs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="flag" :href="route('competitions.index')" :current="request()->routeIs('competitions.*')" wire:navigate>
                        {{ __('Competitions') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Community')" class="grid">
                    <flux:sidebar.item icon="users" :href="route('communities.index')" :current="request()->routeIs('communities.*')" wire:navigate>
                        {{ __('Communities') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('polls.index')" :current="request()->routeIs('polls.*')" wire:navigate>
                        {{ __('Polls') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="star" :href="route('leaderboard')" :current="request()->routeIs('leaderboard')" wire:navigate>
                        {{ __('Leaderboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.*')" wire:navigate>
                        {{ __('Notifications') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @if(auth()->user()?->isAdmin())
                <flux:sidebar.group :heading="__('Admin')" class="grid">
                    <flux:sidebar.item icon="chart-pie" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Admin Panel') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('admin.users.index')" :current="request()->routeIs('admin.users.*')" wire:navigate>
                        {{ __('Manage Users') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="user-group" :href="route('admin.communities.index')" :current="request()->routeIs('admin.communities.*')" wire:navigate>
                        {{ __('Communities') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('admin.polls.index')" :current="request()->routeIs('admin.polls.*')" wire:navigate>
                        {{ __('Polls') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="exclamation-triangle" :href="route('admin.moderation.reports')" :current="request()->routeIs('admin.moderation.*')" wire:navigate>
                        {{ __('Moderation') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="trophy" :href="route('admin.matches.index')" :current="request()->routeIs('admin.matches.*')" wire:navigate>
                        {{ __('Matches') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="shield-check" :href="route('admin.clubs.index')" :current="request()->routeIs('admin.clubs.*')" wire:navigate>
                        {{ __('Clubs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="flag" :href="route('admin.competitions.index')" :current="request()->routeIs('admin.competitions.*')" wire:navigate>
                        {{ __('Competitions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="presentation-chart-line" :href="route('admin.analytics')" :current="request()->routeIs('admin.analytics')" wire:navigate>
                        {{ __('Analytics') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="megaphone" :href="route('admin.ads.index')" :current="request()->routeIs('admin.ads.*')" wire:navigate>
                        {{ __('Ads') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="magnifying-glass" :href="route('search')" wire:navigate>
                    {{ __('Search') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            @auth
            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name ?? 'Guest'" />
        @else
            <flux:sidebar.nav>
                <flux:sidebar.item icon="arrow-right-start-on-rectangle" :href="route('login')" wire:navigate>
                    {{ __('Login') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        @endauth
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    :src="auth()->user()->avatar_url"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                    :src="auth()->user()->avatar_url"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-on-surface hover:text-primary-container">Login</a>
            @endauth
        </flux:header>

        {{ $slot }}

        <!-- Bottom Navigation (Mobile Only) -->
        <livewire:match.nav-count />
        <x-bottom-nav />

        @fluxScripts
    </body>
</html>
