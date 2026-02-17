<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soccer Aficionado - The Ultimate Football Fan Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-white antialiased overflow-x-hidden">

    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300" id="main-nav">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex h-18 items-center justify-between rounded-b-2xl bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-t-0 border-zinc-200/50 dark:border-zinc-800/50 px-6 shadow-lg shadow-zinc-900/5 dark:shadow-black/20">
                <a href="/" class="flex items-center gap-3 font-bold text-lg group">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/25 group-hover:shadow-green-500/40 transition-all duration-300 group-hover:scale-110">
                        <span class="text-xl">⚽</span>
                    </div>
                    <span class="text-zinc-900 dark:text-white tracking-tight hidden sm:inline">Soccer <span class="gradient-text">Aficionado</span></span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('matches.index') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">Matches</a>
                    <a href="{{ route('clubs.index') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">Clubs</a>
                    <a href="{{ route('competitions.index') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">Competitions</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="relative inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm text-white font-semibold hover:from-green-500 hover:to-emerald-400 transition-all duration-300 shadow-lg shadow-green-600/25 hover:shadow-green-500/40 hover:scale-105">
                            Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="relative inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm text-white font-semibold hover:from-green-500 hover:to-emerald-400 transition-all duration-300 shadow-lg shadow-green-600/25 hover:shadow-green-500/40 hover:scale-105">
                                Join Free
                            </a>
                        @endif
                    @endauth

                    {{-- Mobile menu toggle --}}
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden md:hidden mt-2 rounded-2xl bg-white/90 dark:bg-zinc-900/90 backdrop-blur-xl border border-zinc-200/50 dark:border-zinc-800/50 p-4 shadow-xl">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('matches.index') }}" class="px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-green-600 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition">Matches</a>
                    <a href="{{ route('clubs.index') }}" class="px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-green-600 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition">Clubs</a>
                    <a href="{{ route('competitions.index') }}" class="px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-green-600 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition">Competitions</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center overflow-hidden">
        {{-- Animated Background --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-green-50 via-emerald-50/50 to-zinc-50 dark:from-zinc-950 dark:via-emerald-950/20 dark:to-zinc-950"></div>
            <div class="absolute top-20 left-[10%] text-6xl opacity-10 dark:opacity-5 animate-float">⚽</div>
            <div class="absolute top-40 right-[15%] text-4xl opacity-10 dark:opacity-5 animate-float-reverse animation-delay-200">🏆</div>
            <div class="absolute bottom-32 left-[20%] text-5xl opacity-10 dark:opacity-5 animate-float animation-delay-500">🥅</div>
            <div class="absolute bottom-20 right-[25%] text-3xl opacity-10 dark:opacity-5 animate-float-reverse animation-delay-700">⭐</div>
            <div class="absolute top-60 left-[60%] text-4xl opacity-10 dark:opacity-5 animate-float-slow animation-delay-300">🏟️</div>
            <div class="absolute top-1/4 -left-32 w-96 h-96 bg-green-400/20 dark:bg-green-500/10 rounded-full blur-3xl animate-morph"></div>
            <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-emerald-400/20 dark:bg-emerald-500/10 rounded-full blur-3xl animate-morph animation-delay-400"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-green-300/10 dark:bg-green-500/5 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.03]" style="background-image: radial-gradient(circle, #16a34a 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 pt-32 pb-20 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Left: Text Content --}}
                <div class="text-center lg:text-left">
                    <div class="animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 rounded-full bg-green-100/80 dark:bg-green-900/30 backdrop-blur-sm px-5 py-2 text-sm font-medium text-green-700 dark:text-green-400 mb-8 border border-green-200/50 dark:border-green-800/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            The #1 Platform for Football Fans
                        </div>
                    </div>

                    <h1 class="animate-fade-in-up animation-delay-200 text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight leading-[1.1]">
                        Where Football
                        <span class="block mt-2">
                            <span class="relative">
                                <span class="gradient-text">Passion</span>
                                <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none"><path d="M2 8C30 3 70 2 100 5C130 8 170 9 198 4" stroke="url(#paint)" stroke-width="3" stroke-linecap="round"/><defs><linearGradient id="paint" x1="0" y1="0" x2="200" y2="0"><stop stop-color="#16a34a"/><stop offset="1" stop-color="#059669"/></linearGradient></defs></svg>
                            </span>
                            Lives
                        </span>
                    </h1>

                    <p class="animate-fade-in-up animation-delay-300 mt-8 text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Join thousands of passionate fans. Follow your favorite clubs, discuss live matches, vote in polls, earn badges, and connect with a global football community.
                    </p>

                    <div class="animate-fade-in-up animation-delay-400 mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-500 px-8 py-4 text-white font-bold text-lg hover:from-green-500 hover:to-emerald-400 transition-all duration-300 shadow-xl shadow-green-600/25 hover:shadow-green-500/40 hover:scale-105">
                                Go to Dashboard
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-500 px-8 py-4 text-white font-bold text-lg hover:from-green-500 hover:to-emerald-400 transition-all duration-300 shadow-xl shadow-green-600/25 hover:shadow-green-500/40 hover:scale-105">
                                Get Started Free
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-zinc-200 dark:border-zinc-700 px-8 py-4 font-bold text-lg text-zinc-700 dark:text-zinc-300 hover:border-green-300 dark:hover:border-green-700 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Sign In
                            </a>
                        @endauth
                    </div>

                    {{-- Trust Indicators --}}
                    <div class="animate-fade-in-up animation-delay-500 mt-12 flex items-center gap-6 justify-center lg:justify-start">
                        <div class="flex -space-x-3">
                            @foreach(['A','B','C','D','E'] as $i => $letter)
                            <div class="w-10 h-10 rounded-full border-2 border-white dark:border-zinc-900 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-xs shadow-md" style="z-index: {{ 5 - $i }}">
                                {{ $letter }}
                            </div>
                            @endforeach
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                            <span class="font-bold text-zinc-900 dark:text-white">{{ number_format(\App\Models\User::count()) }}+</span> fans already joined
                        </div>
                    </div>
                </div>

                {{-- Right: Visual Card Stack --}}
                <div class="relative hidden lg:block">
                    <div class="animate-scale-in animation-delay-300">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-zinc-900/20 dark:shadow-black/40 border border-zinc-200/50 dark:border-zinc-700/50">
                            <img fetchpriority="high" decoding="async" src="https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=700&h=500&fit=crop&q=80" alt="Football Stadium" class="w-full h-[420px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-8">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center gap-1.5 bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                                        LIVE
                                    </span>
                                    <span class="text-white/80 text-sm">Premier League</span>
                                </div>
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-lg">⚽</div>
                                        <span class="font-bold text-lg">Arsenal</span>
                                    </div>
                                    <span class="text-3xl font-bold">2 - 1</span>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-lg">Chelsea</span>
                                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-lg">⚽</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-6 -right-6 animate-float animation-delay-200">
                            <div class="glass-card rounded-2xl p-4 shadow-xl w-48">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-sm">🏆</div>
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Leaderboard</span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs"><span class="text-zinc-600 dark:text-zinc-400">1. Alex M.</span><span class="font-bold text-green-600">2,450 pts</span></div>
                                    <div class="flex items-center justify-between text-xs"><span class="text-zinc-600 dark:text-zinc-400">2. Sarah K.</span><span class="font-bold text-green-600">2,180 pts</span></div>
                                    <div class="flex items-center justify-between text-xs"><span class="text-zinc-600 dark:text-zinc-400">3. James W.</span><span class="font-bold text-green-600">1,920 pts</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -left-6 animate-float-reverse animation-delay-500">
                            <div class="glass-card rounded-2xl p-4 shadow-xl w-56">
                                <div class="flex items-center gap-2 mb-3"><span class="text-sm">📊</span><span class="text-sm font-bold text-zinc-900 dark:text-white">Active Poll</span></div>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Who'll win the Champions League?</p>
                                <div class="space-y-1.5">
                                    <div class="relative h-6 bg-zinc-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-green-500 to-emerald-400 rounded-full" style="width:45%"></div>
                                        <span class="absolute inset-0 flex items-center px-3 text-xs font-medium">Man City — 45%</span>
                                    </div>
                                    <div class="relative h-6 bg-zinc-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-green-500/60 to-emerald-400/60 rounded-full" style="width:30%"></div>
                                        <span class="absolute inset-0 flex items-center px-3 text-xs font-medium">Real Madrid — 30%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-1/2 -right-10 animate-bounce-subtle animation-delay-300">
                            <div class="glass-card rounded-xl px-4 py-3 shadow-lg flex items-center gap-2">
                                <span class="text-lg">🏅</span>
                                <div>
                                    <div class="text-xs font-bold text-zinc-900 dark:text-white">Badge Earned!</div>
                                    <div class="text-[10px] text-zinc-500">Super Fan Unlocked</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-zinc-300 dark:border-zinc-600 flex justify-center pt-2">
                <div class="w-1.5 h-3 rounded-full bg-zinc-400 dark:bg-zinc-500 animate-fade-in-down"></div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-600 via-emerald-600 to-green-700"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=30'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-green-600/90 via-emerald-600/90 to-green-700/90"></div>
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @php
                    $stats = [
                        ['count' => \App\Models\User::count(), 'label' => 'Active Fans', 'icon' => '👥', 'suffix' => '+'],
                        ['count' => \App\Models\Club::count(), 'label' => 'Clubs Tracked', 'icon' => '🛡️', 'suffix' => '+'],
                        ['count' => \App\Models\Community::count(), 'label' => 'Communities', 'icon' => '💬', 'suffix' => '+'],
                        ['count' => \App\Models\Post::count(), 'label' => 'Fan Posts', 'icon' => '📝', 'suffix' => '+'],
                    ];
                @endphp
                @foreach($stats as $stat)
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm mb-4 text-3xl group-hover:scale-110 transition-transform duration-300">{{ $stat['icon'] }}</div>
                    <div class="text-4xl sm:text-5xl font-bold text-white mb-1">{{ number_format($stat['count']) }}{{ $stat['suffix'] }}</div>
                    <div class="text-green-100 text-sm font-medium">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-24 px-4 sm:px-6 relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-green-100/30 dark:bg-green-900/10 rounded-full blur-3xl -z-10"></div>
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/30 px-4 py-1.5 text-sm font-medium text-green-700 dark:text-green-400 mb-6">✨ Features</div>
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Everything You Need<br><span class="gradient-text">As a Football Fan</span></h2>
                <p class="mt-6 text-lg text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto">From live match tracking to community discussions, we've built every feature you need to enjoy the beautiful game.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $features = [
                        ['icon' => '🏟️', 'title' => 'Live Matches', 'description' => 'Track live scores, match events, and real-time updates for all your favorite teams and competitions.', 'color' => 'from-red-500 to-orange-500', 'image' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=400&h=200&fit=crop&q=70'],
                        ['icon' => '👥', 'title' => 'Fan Communities', 'description' => 'Join or create communities around your favorite clubs. Share posts, discuss tactics, and connect with fellow fans.', 'color' => 'from-blue-500 to-cyan-500', 'image' => 'https://images.unsplash.com/photo-1459865264687-595d652de67e?w=400&h=200&fit=crop&q=70'],
                        ['icon' => '📊', 'title' => 'Polls & Voting', 'description' => 'Vote on match predictions, player ratings, and hot takes. See how your opinions compare to the community.', 'color' => 'from-purple-500 to-pink-500', 'image' => 'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=400&h=200&fit=crop&q=70'],
                        ['icon' => '🏅', 'title' => 'Gamification', 'description' => 'Earn points for engaging, unlock badges, and climb the leaderboard. Show off your dedication!', 'color' => 'from-amber-500 to-yellow-500', 'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400&h=200&fit=crop&q=70'],
                        ['icon' => '⚽', 'title' => 'Club & Player Data', 'description' => 'Browse detailed club profiles, player stats, competition standings, and comprehensive football data.', 'color' => 'from-green-500 to-emerald-500', 'image' => 'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=400&h=200&fit=crop&q=70'],
                        ['icon' => '📱', 'title' => 'Mobile App', 'description' => 'Full-featured Mobile App integration for you, with a modern interface.', 'color' => 'from-indigo-500 to-violet-500', 'image' => 'https://images.unsplash.com/photo-1461896836934-bd45ba25e57d?w=400&h=200&fit=crop&q=70'],
                    ];
                @endphp
                @foreach($features as $feature)
                <div class="group relative rounded-2xl overflow-hidden border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800/80 hover:border-green-300 dark:hover:border-green-700/60 transition-all duration-500 hover-lift">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $feature['image'] }}" alt="{{ $feature['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-zinc-800 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $feature['color'] }} flex items-center justify-center text-2xl shadow-lg">{{ $feature['icon'] }}</div>
                        </div>
                    </div>
                    <div class="p-6 pt-2">
                        <h3 class="font-bold text-xl text-zinc-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 px-4 sm:px-6 bg-zinc-100/50 dark:bg-zinc-900/50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/30 px-4 py-1.5 text-sm font-medium text-green-700 dark:text-green-400 mb-6">🚀 Get Started</div>
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Three Steps to<br><span class="gradient-text">Football Heaven</span></h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-16 left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-green-300 via-emerald-400 to-green-300 dark:from-green-700 dark:via-emerald-600 dark:to-green-700"></div>
                @php $steps = [['num'=>'01','title'=>'Create Account','desc'=>'Sign up in seconds. Set your favorite clubs and customize your profile.','icon'=>'✍️'],['num'=>'02','title'=>'Join Communities','desc'=>'Find and join fan communities. Follow matches, clubs, and fellow fans.','icon'=>'🤝'],['num'=>'03','title'=>'Engage & Earn','desc'=>'Post, comment, vote in polls, and earn points and badges for your passion.','icon'=>'🏆']]; @endphp
                @foreach($steps as $step)
                <div class="relative text-center group">
                    <div class="relative z-10 inline-flex items-center justify-center w-32 h-32 rounded-3xl bg-white dark:bg-zinc-800 border-2 border-green-200 dark:border-green-800 shadow-xl shadow-green-500/10 mb-6 group-hover:scale-110 group-hover:border-green-400 dark:group-hover:border-green-600 transition-all duration-300">
                        <span class="text-5xl">{{ $step['icon'] }}</span>
                    </div>
                    <div class="absolute -top-2 -right-2 z-20 w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-white text-sm font-bold flex items-center justify-center shadow-lg md:relative md:top-0 md:right-0 md:mx-auto md:-mt-12 md:mb-4">{{ $step['num'] }}</div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Live Experience --}}
    <section class="py-24 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-green-200/30 dark:bg-green-900/20 rounded-full blur-3xl"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-emerald-200/30 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
        <div class="relative max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/30 px-4 py-1.5 text-sm font-medium text-green-700 dark:text-green-400 mb-6">🔥 Live Experience</div>
                    <h2 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6">Never Miss a<br><span class="gradient-text">Moment</span></h2>
                    <p class="text-lg text-zinc-500 dark:text-zinc-400 mb-8 leading-relaxed">Get real-time match updates, goal alerts, and instant notifications. Our live match center brings you closer to the action than ever before.</p>
                    <div class="space-y-4">
                        @foreach([['⚡','Real-time score updates & match events'],['📈','Detailed match statistics & analytics'],['💬','Live match discussion threads'],['🔔','Custom notification preferences']] as $item)
                        <div class="flex items-center gap-4 group">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">{{ $item[0] }}</div>
                            <span class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $item[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl shadow-zinc-900/20 dark:shadow-black/40 border border-zinc-200/50 dark:border-zinc-700/50">
                        <img src="https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?w=700&h=500&fit=crop&q=80" alt="Match Experience" class="w-full h-[400px] object-cover" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent rounded-3xl"></div>
                    </div>
                    <div class="absolute -bottom-6 -left-6 sm:left-auto sm:-right-6 animate-float">
                        <div class="glass-card rounded-2xl p-4 shadow-xl flex items-center gap-3 max-w-xs">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white text-lg flex-shrink-0">⚽</div>
                            <div>
                                <div class="text-sm font-bold text-zinc-900 dark:text-white">GOAL! 🎉</div>
                                <div class="text-xs text-zinc-500">Haaland scores in the 89th minute!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-24 px-4 sm:px-6 bg-zinc-100/50 dark:bg-zinc-900/50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Loved by <span class="gradient-text">Football Fans</span></h2>
                <p class="mt-4 text-lg text-zinc-500 dark:text-zinc-400">See what our community members are saying.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php $testimonials = [['name'=>'Marcus R.','role'=>'Arsenal Fan','text'=>"Best football community platform I've ever used. The live match tracking and community discussions are incredible!",'avatar'=>'M'],['name'=>'Sofia L.','role'=>'Barcelona Fan','text'=>'I love the gamification system! Earning badges and climbing the leaderboard keeps me engaged. The polls are my favorite feature.','avatar'=>'S'],['name'=>'James K.','role'=>'Liverpool Fan','text'=>'Finally a platform where real football discussions happen. The communities are vibrant and the match data is always up to date.','avatar'=>'J']]; @endphp
                @foreach($testimonials as $t)
                <div class="rounded-2xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-8 hover-lift">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)<svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">{{ $t['text'] }}</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm">{{ $t['avatar'] }}</div>
                        <div><div class="font-semibold text-sm text-zinc-900 dark:text-white">{{ $t['name'] }}</div><div class="text-xs text-zinc-500">{{ $t['role'] }}</div></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 px-4 sm:px-6 relative overflow-hidden">
        <div class="relative max-w-5xl mx-auto">
            <div class="relative rounded-3xl overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1200&h=600&fit=crop&q=70" alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-900/95 via-emerald-800/90 to-green-900/95"></div>
                </div>
                <div class="relative z-10 px-8 sm:px-16 py-16 sm:py-20 text-center">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur px-4 py-1.5 text-sm font-medium text-green-200 mb-6">⚡ Join the Movement</div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-white mb-4 tracking-tight">Ready to Join the<br><span class="text-green-300">Global Football Community?</span></h2>
                    <p class="text-lg text-green-100/80 mb-10 max-w-xl mx-auto">Sign up for free and start connecting with football fans worldwide. No credit card required.</p>
                    @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-white text-green-700 px-10 py-4 font-bold text-lg hover:bg-green-50 transition-all duration-300 shadow-2xl shadow-black/20 hover:scale-105">
                            Create Free Account
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('matches.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-white/30 text-white px-10 py-4 font-bold text-lg hover:bg-white/10 transition-all duration-300">Browse Matches</a>
                    </div>
                    @endguest
                    @auth
                    <a href="{{ url('/dashboard') }}" class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-white text-green-700 px-10 py-4 font-bold text-lg hover:bg-green-50 transition-all duration-300 shadow-2xl shadow-black/20 hover:scale-105">
                        Go to Dashboard
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2 md:col-span-1">
                    <a href="/" class="flex items-center gap-3 font-bold text-lg mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/20"><span class="text-xl">⚽</span></div>
                        <span class="text-zinc-900 dark:text-white">Soccer Aficionado</span>
                    </a>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">The ultimate platform for football fans worldwide. Track, discuss, and celebrate the beautiful game.</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-zinc-900 dark:text-white mb-4 uppercase tracking-wider">Platform</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('matches.index') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Matches</a></li>
                        <li><a href="{{ route('clubs.index') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Clubs</a></li>
                        <li><a href="{{ route('competitions.index') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Competitions</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-zinc-900 dark:text-white mb-4 uppercase tracking-wider">Community</h4>
                    <ul class="space-y-3">
                        @auth
                        <li><a href="{{ route('communities.index') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Communities</a></li>
                        <li><a href="{{ route('polls.index') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Polls</a></li>
                        <li><a href="{{ route('leaderboard') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Leaderboard</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Sign In to Explore</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-zinc-900 dark:text-white mb-4 uppercase tracking-wider">Account</h4>
                    <ul class="space-y-3">
                        @auth
                        <li><a href="{{ url('/dashboard') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Dashboard</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm text-zinc-500 hover:text-green-600 dark:hover:text-green-400 transition-colors">Create Account</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-zinc-200 dark:border-zinc-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-zinc-500">&copy; {{ date('Y') }} Soccer Aficionado. All rights reserved.</div>
                <div class="flex items-center gap-2 text-sm text-zinc-400">Made with <span class="text-red-500">❤️</span> for football fans. By <a href="https://royalsolutions.com.ng" class="underline">Royal Solutions Technologies</a></div>
            </div>
        </div>
    </footer>

    <script>
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('py-2', window.scrollY > 50);
        });
    </script>
</body>
</html>
