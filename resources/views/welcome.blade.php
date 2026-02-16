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
<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-white antialiased">
    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-bold text-lg text-green-600">
                <span class="text-2xl">⚽</span> Soccer Aficionado
            </a>
            <div class="flex items-center gap-4">
                @auth
                <a href="{{ url('/dashboard') }}" class="rounded-lg bg-green-600 px-5 py-2 text-sm text-white font-medium hover:bg-green-700 transition">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-green-600 transition">Log in</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="rounded-lg bg-green-600 px-5 py-2 text-sm text-white font-medium hover:bg-green-700 transition">Join Free</a>
                @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/30 px-4 py-1.5 text-sm text-green-700 dark:text-green-400 mb-6">
                🏆 The #1 Platform for Football Fans
            </div>
            <h1 class="text-5xl md:text-6xl font-bold tracking-tight leading-tight">
                Where Football Fans
                <span class="text-green-600"> Come Together</span>
            </h1>
            <p class="mt-6 text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                Join thousands of passionate fans. Follow your favorite clubs, discuss live matches, vote in polls, earn badges, and connect with a global football community.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                @auth
                <a href="{{ url('/dashboard') }}" class="rounded-xl bg-green-600 px-8 py-3 text-white font-semibold hover:bg-green-700 transition shadow-lg shadow-green-600/25">Go to Dashboard</a>
                @else
                <a href="{{ route('register') }}" class="rounded-xl bg-green-600 px-8 py-3 text-white font-semibold hover:bg-green-700 transition shadow-lg shadow-green-600/25">Get Started Free</a>
                <a href="{{ route('login') }}" class="rounded-xl border border-zinc-300 dark:border-zinc-700 px-8 py-3 font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">Sign In</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 border-y border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="max-w-4xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-green-600">{{ \App\Models\User::count() }}+</div>
                <div class="text-sm text-zinc-500 mt-1">Fans</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-green-600">{{ \App\Models\Club::count() }}+</div>
                <div class="text-sm text-zinc-500 mt-1">Clubs</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-green-600">{{ \App\Models\Community::count() }}+</div>
                <div class="text-sm text-zinc-500 mt-1">Communities</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-green-600">{{ \App\Models\Post::count() }}+</div>
                <div class="text-sm text-zinc-500 mt-1">Posts</div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold">Everything You Need as a Fan</h2>
                <p class="mt-3 text-zinc-500 max-w-xl mx-auto">From live match tracking to community discussions, we've built every feature you need to enjoy the beautiful game.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">🏟️</div>
                    <h3 class="font-bold text-lg">Live Matches</h3>
                    <p class="text-sm text-zinc-500 mt-2">Track live scores, match events, and real-time updates for all your favorite teams and competitions.</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">👥</div>
                    <h3 class="font-bold text-lg">Fan Communities</h3>
                    <p class="text-sm text-zinc-500 mt-2">Join or create communities around your favorite clubs. Share posts, discuss tactics, and connect with fellow fans.</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">📊</div>
                    <h3 class="font-bold text-lg">Polls &amp; Voting</h3>
                    <p class="text-sm text-zinc-500 mt-2">Vote on match predictions, player ratings, and hot takes. See how your opinions compare to the community.</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">🏅</div>
                    <h3 class="font-bold text-lg">Gamification</h3>
                    <p class="text-sm text-zinc-500 mt-2">Earn points for engaging, unlock badges, and climb the leaderboard. Show off your dedication!</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">⚽</div>
                    <h3 class="font-bold text-lg">Club &amp; Player Data</h3>
                    <p class="text-sm text-zinc-500 mt-2">Browse detailed club profiles, player stats, competition standings, and comprehensive football data.</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                    <div class="text-3xl mb-3">📱</div>
                    <h3 class="font-bold text-lg">REST API</h3>
                    <p class="text-sm text-zinc-500 mt-2">Full-featured API with token authentication. Build mobile apps or integrations with our platform.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-6">
        <div class="max-w-3xl mx-auto text-center rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 p-12 text-white">
            <h2 class="text-3xl font-bold">Ready to Join the Community?</h2>
            <p class="mt-3 text-green-100">Sign up for free and start connecting with football fans worldwide.</p>
            @guest
            <a href="{{ route('register') }}" class="inline-block mt-6 rounded-xl bg-white text-green-700 px-8 py-3 font-bold hover:bg-green-50 transition">Create Account</a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 dark:border-zinc-800 py-8 px-6">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-zinc-500">
                <span class="text-lg">⚽</span>
                <span>Soccer Aficionado &copy; {{ date('Y') }}</span>
            </div>
            <div class="flex items-center gap-6 text-sm text-zinc-500">
                <a href="{{ route('matches.index') }}" class="hover:text-green-600 transition">Matches</a>
                <a href="{{ route('clubs.index') }}" class="hover:text-green-600 transition">Clubs</a>
                <a href="{{ route('competitions.index') }}" class="hover:text-green-600 transition">Competitions</a>
            </div>
        </div>
    </footer>
</body>
</html>
