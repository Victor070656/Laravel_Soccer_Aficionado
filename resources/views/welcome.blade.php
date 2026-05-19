<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soccer Aficionado | Where Football Passion Meets the Digital World</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Lexend:wght@400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background antialiased overflow-x-hidden selection:bg-secondary selection:text-on-secondary">
    <div class="relative min-h-screen flex flex-col turf-pattern bg-[radial-gradient(circle_at_top,rgba(74,225,118,0.12),transparent_35%),radial-gradient(circle_at_80%_20%,rgba(249,189,34,0.08),transparent_28%),linear-gradient(180deg,#0e0e0e_0%,#131313_38%,#0e0e0e_100%)]">
        <div class="pointer-events-none absolute inset-0 opacity-25" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 72px 72px;"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 bg-[radial-gradient(circle_at_top,rgba(74,225,118,0.2),transparent_60%)]" style="height: 520px;"></div>

        <nav class="fixed inset-x-0 top-0 z-50 border-b border-outline-variant/40 bg-surface-container-low/85 backdrop-blur-xl glass-edge">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-secondary/20 bg-secondary/10 text-xl shadow-[0_0_24px_rgba(74,225,118,0.25)] transition-transform group-hover:scale-105">
                        <span class="material-symbols-outlined text-2xl text-secondary" style="font-variation-settings: 'FILL' 1;">sports_soccer</span>
                    </div>
                    <div class="leading-none">
                        <div class="font-['Lexend'] text-lg font-black uppercase tracking-tight text-white">Soccer Aficionado</div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.3em] text-on-surface-variant">Fan platform</div>
                    </div>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('matches.live') }}" class="rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-wide text-on-surface-variant transition hover:bg-white/5 hover:text-secondary">Live Scores</a>
                    <a href="{{ route('clubs.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-wide text-on-surface-variant transition hover:bg-white/5 hover:text-secondary">Clubs</a>
                    <a href="{{ route('communities.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-wide text-on-surface-variant transition hover:bg-white/5 hover:text-secondary">Communities</a>
                    <a href="{{ route('polls.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-wide text-on-surface-variant transition hover:bg-white/5 hover:text-secondary">Polls</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-secondary px-5 py-3 text-sm font-black uppercase tracking-wider text-on-secondary shadow-[0_0_28px_rgba(74,225,118,0.28)] transition hover:scale-[1.02] hover:bg-secondary-fixed-dim">
                            Dashboard
                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden text-sm font-semibold uppercase tracking-wider text-on-surface-variant transition hover:text-white sm:inline-flex">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-secondary px-5 py-3 text-sm font-black uppercase tracking-wider text-on-secondary shadow-[0_0_28px_rgba(74,225,118,0.28)] transition hover:scale-[1.02] hover:bg-secondary-fixed-dim">
                                Join the Club
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <div class="flex-1 flex flex-col">
        @php
            $heroMatch = $liveMatches->first() ?? $upcomingMatches->first();
            $scoreLine = $heroMatch
                ? (data_get($heroMatch, 'status_short') === 'FT'
                    ? (data_get($heroMatch, 'home_score') . ' - ' . data_get($heroMatch, 'away_score'))
                    : ((data_get($heroMatch, 'home_score') ?? 0) . ' - ' . (data_get($heroMatch, 'away_score') ?? 0)))
                : null;
            $scoreStatus = data_get($heroMatch, 'status_short') ?? ($upcomingMatches->isNotEmpty() ? 'UP NEXT' : 'NO FIXTURES');
            $tickerMatches = $liveMatches->isNotEmpty() ? $liveMatches : $upcomingMatches;
        @endphp

        <section class="relative flex min-h-screen items-center overflow-hidden px-4 pt-28 sm:px-6 lg:px-8 lg:pt-36">
            <video autoplay muted loop playsinline class="absolute inset-0 h-full w-full object-cover" style="z-index: 0;">
                <source src="{{ asset('assets/soca.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80" style="z-index: 1;"></div>
            <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 72px 72px; z-index: 2;"></div>
            <div class="mx-auto grid w-full max-w-7xl gap-14 lg:grid-cols-[1.1fr_0.9fr] lg:items-center relative" style="z-index: 3;">
                <div class="relative z-10 max-w-3xl">
                    <div class="mb-6 inline-flex items-center gap-3 rounded-full border border-secondary/20 bg-secondary/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-secondary backdrop-blur-md animate-fade-in-up badge-live">
                        <span class="h-2 w-2 rounded-full bg-secondary shadow-[0_0_14px_rgba(74,225,118,0.8)]"></span>
                        The new season is live
                    </div>

                    <h1 class="font-['Lexend'] text-5xl font-black uppercase tracking-tighter text-white sm:text-6xl lg:text-7xl animate-fade-in-up animation-delay-200 text-neon">
                        Where Football Passion <span class="text-secondary">Meets</span> the Digital World
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-on-surface-variant sm:text-xl animate-fade-in-up animation-delay-300">
                        Follow live matches, join club communities, vote in polls, and climb the leaderboard in a platform built for real football fans.
                    </p>

                    <div class="mt-10 flex flex-col gap-4 sm:flex-row animate-fade-in-up animation-delay-400">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl bg-secondary px-7 py-4 text-base font-black uppercase tracking-wider text-on-secondary shadow-[0_0_32px_rgba(74,225,118,0.3)] transition hover:scale-[1.02] hover:bg-secondary-fixed-dim">
                                Go to Dashboard
                                <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl bg-secondary px-7 py-4 text-base font-black uppercase tracking-wider text-on-secondary shadow-[0_0_32px_rgba(74,225,118,0.3)] transition hover:scale-[1.02] hover:bg-secondary-fixed-dim">
                                Start Free
                                <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                            </a>
                            <a href="{{ route('matches.live') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl border border-outline-variant/60 bg-surface-container-low/80 px-7 py-4 text-base font-bold uppercase tracking-wider text-on-background transition hover:border-secondary/40 hover:bg-surface-container-high">
                                Explore Matches
                            </a>
                        @endauth
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3 animate-fade-in-up animation-delay-500">
                        @php
                            $headlineStats = [
                                ['value' => \App\Models\User::count(), 'label' => 'Active fans'],
                                ['value' => \App\Models\Club::count(), 'label' => 'Clubs tracked'],
                                ['value' => \App\Models\Community::count(), 'label' => 'Communities'],
                            ];
                        @endphp
                        @foreach ($headlineStats as $stat)
                            <div class="rounded-2xl border border-outline-variant/40 bg-surface-container/80 p-5 backdrop-blur-md glass-edge">
                                <div class="font-['Lexend'] text-3xl font-black text-white">{{ number_format($stat['value']) }}<span class="text-secondary">+</span></div>
                                <div class="mt-1 text-xs font-bold uppercase tracking-[0.28em] text-on-surface-variant">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative z-10 animate-scale-in animation-delay-300">
                    <div class="absolute -left-8 top-10 h-28 w-28 rounded-full bg-emerald-400/20 blur-3xl"></div>
                    <div class="absolute -bottom-10 right-4 h-40 w-40 rounded-full bg-amber-400/15 blur-3xl"></div>

                    <div class="overflow-hidden rounded-4xl border border-white/10 bg-[#0f1712]/90 shadow-[0_40px_120px_rgba(0,0,0,0.45)]">
                        <div class="relative" style="height: 500px;">
                            <img src="https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=1400&q=80" alt="Floodlit football stadium" class="h-full w-full object-cover opacity-30 mix-blend-luminosity">
                            <div class="absolute inset-0 bg-linear-to-t from-[#050c08] via-[#050c08]/80 to-transparent"></div>
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(74,225,118,0.22),transparent_55%)]"></div>

                            <div class="absolute left-5 top-5 flex items-center gap-2 rounded-full border border-emerald-400/20 bg-[#07110b]/80 px-3 py-2 text-xs font-black uppercase tracking-[0.24em] text-emerald-300 backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-emerald-300 shadow-[0_0_14px_rgba(74,225,118,0.8)]"></span>
                                {{ $scoreStatus }}
                            </div>

                            <div class="absolute bottom-0 left-0 right-0 space-y-4 p-5 sm:p-6">
                                <div class="rounded-3xl border border-white/10 bg-[#07110b]/85 p-5 backdrop-blur-xl">
                                    <div class="flex items-center justify-between gap-4 text-xs font-bold uppercase tracking-[0.28em] text-white/45">
                                        <span>{{ data_get($heroMatch, 'league_name', 'Live Match Center') }}</span>
                                        <span>{{ data_get($heroMatch, 'status_short', 'UP NEXT') }}</span>
                                    </div>
                                    <div class="mt-5 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-emerald-400/30 bg-emerald-400/10 font-['Lexend'] text-lg font-black text-white shadow-[0_0_16px_rgba(74,225,118,0.18)]">
                                                {{ data_get($heroMatch, 'home_team.code') ?? \Illuminate\Support\Str::of(data_get($heroMatch, 'home_team.name', 'H'))->substr(0, 3)->upper() }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold uppercase tracking-wider text-white/70">{{ data_get($heroMatch, 'home_team.name', 'No match') }}</div>
                                                <div class="text-4xl font-['Lexend'] font-black text-white">{{ $scoreLine ? \Illuminate\Support\Str::before($scoreLine, ' - ') : '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="hidden h-16 w-px bg-white/10 sm:block"></div>
                                        <div class="flex items-center gap-3 text-right">
                                            <div>
                                                <div class="text-sm font-bold uppercase tracking-wider text-white/50">{{ data_get($heroMatch, 'away_team.name', 'No match') }}</div>
                                                <div class="text-4xl font-['Lexend'] font-black text-white/60">{{ $scoreLine ? \Illuminate\Support\Str::after($scoreLine, ' - ') : '—' }}</div>
                                            </div>
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5 font-['Lexend'] text-lg font-black text-white/70">
                                                {{ data_get($heroMatch, 'away_team.code') ?? \Illuminate\Support\Str::of(data_get($heroMatch, 'away_team.name', 'A'))->substr(0, 3)->upper() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/10">
                                        <div class="h-full rounded-full bg-emerald-400" style="width: {{ $heroMatch && filled(data_get($heroMatch, 'home_score')) && filled(data_get($heroMatch, 'away_score')) && ((int) data_get($heroMatch, 'home_score') + (int) data_get($heroMatch, 'away_score')) > 0 ? max(8, min(92, round(((int) data_get($heroMatch, 'home_score') / max(1, ((int) data_get($heroMatch, 'home_score') + (int) data_get($heroMatch, 'away_score')))) * 100))) : 50 }}%;"></div>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="mb-3 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-white">
                                            <span class="material-symbols-outlined text-amber-300" style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                                            Hall of Fame
                                        </div>
                                        <div class="space-y-2 text-sm text-white/70">
                                            <div class="flex justify-between"><span>@GunnerSteve</span><span class="text-emerald-300">45,200 pts</span></div>
                                            <div class="flex justify-between"><span>@BavarianKing</span><span class="text-emerald-300">42,850 pts</span></div>
                                            <div class="flex justify-between"><span>@Milanista99</span><span class="text-emerald-300">39,100 pts</span></div>
                                        </div>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                        <div class="mb-3 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-white">
                                            <span class="material-symbols-outlined text-amber-300" style="font-variation-settings: 'FILL' 1;">forum</span>
                                            Live Banter
                                        </div>
                                        <p class="text-sm leading-6 text-white/70">The press is working. That midfield shape is controlling the match, and the discussion is already flying.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative border-y border-white/8 bg-[#07110b]/85 px-4 py-5 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl overflow-hidden">
                @if ($tickerMatches->isNotEmpty())
                    <div class="flex w-max items-center gap-8 whitespace-nowrap text-sm font-bold uppercase tracking-[0.24em] text-white/65 animate-marquee">
                        @foreach ($tickerMatches->take(6) as $match)
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ data_get($match, 'status_short') === 'FT' ? 'bg-amber-300' : 'bg-emerald-300' }}"></span>
                                {{ \Illuminate\Support\Str::of(data_get($match, 'home_team.code') ?? data_get($match, 'home_team.name', 'H'))->substr(0, 3)->upper() }} {{ data_get($match, 'home_score', 0) }} - {{ data_get($match, 'away_score', 0) }} {{ \Illuminate\Support\Str::of(data_get($match, 'away_team.code') ?? data_get($match, 'away_team.name', 'A'))->substr(0, 3)->upper() }}
                                <span class="{{ data_get($match, 'status_short') === 'FT' ? 'text-amber-300' : 'text-emerald-300' }}">{{ data_get($match, 'status_short', 'LIVE') }}</span>
                            </span>
                            <span class="text-white/20">•</span>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm font-bold uppercase tracking-[0.24em] text-white/45">
                        No fixtures available right now.
                    </div>
                @endif
            </div>
        </section>

        <section class="px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-white/60">Explore tribes</div>
                        <h2 class="font-['Lexend'] text-4xl font-black uppercase tracking-tighter text-white sm:text-5xl">Find your <span class="text-emerald-300">people</span></h2>
                        <p class="mt-4 max-w-2xl text-base leading-7 text-white/65 sm:text-lg">Join dedicated hubs for clubs, leagues, and fan culture. Debate, celebrate, and follow the season with people who care as much as you do.</p>
                    </div>
                    <a href="{{ route('clubs.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white transition hover:border-emerald-300/40 hover:bg-white/10">View all tribes</a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
                    @php
                        $tribes = [
                            ['name' => 'Madrid', 'count' => '14.2k active', 'image' => 'https://images.unsplash.com/photo-1518091043644-c1d4457512c6?auto=format&fit=crop&w=900&q=80'],
                            ['name' => 'Liverpool', 'count' => '28.5k active', 'image' => 'https://images.unsplash.com/photo-1508098682722-e99c643e89c9?auto=format&fit=crop&w=900&q=80', 'highlight' => true],
                            ['name' => 'Milan', 'count' => '8.9k active', 'image' => 'https://images.unsplash.com/photo-1518091043644-c1d4457512c6?auto=format&fit=crop&w=900&q=80'],
                            ['name' => 'Munich', 'count' => '11.4k active', 'image' => 'https://images.unsplash.com/photo-1486286701208-1d58e9338013?auto=format&fit=crop&w=900&q=80'],
                        ];
                    @endphp
                    @foreach ($tribes as $tribe)
                        <div class="group relative aspect-square overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/5">
                            <img src="{{ $tribe['image'] }}" alt="{{ $tribe['name'] }} fans" class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-linear-to-t from-[#050c08] via-[#050c08]/70 to-transparent"></div>
                            @if (!empty($tribe['highlight']))
                                <div class="absolute right-4 top-4 rounded-full bg-emerald-300 px-3 py-1 text-[10px] font-black uppercase tracking-[0.24em] text-[#07110b]">Joined</div>
                            @endif
                            <div class="absolute inset-x-0 bottom-0 p-5">
                                <h3 class="font-['Lexend'] text-2xl font-black uppercase tracking-tight text-white">{{ $tribe['name'] }}</h3>
                                <p class="mt-1 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.24em] text-white/65">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                    {{ $tribe['count'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('register') }}" class="group flex aspect-square flex-col items-center justify-center rounded-[1.75rem] border-2 border-dashed border-white/12 bg-white/3 p-6 text-center transition hover:border-emerald-300/50 hover:bg-white/6">
                        <span class="material-symbols-outlined mb-3 text-5xl text-white/35 transition group-hover:text-emerald-300" style="font-variation-settings: 'FILL' 1;">add_circle</span>
                        <div class="font-['Lexend'] text-xl font-bold uppercase tracking-tight text-white">Discover more</div>
                        <div class="mt-2 text-sm text-white/45">500+ clubs and leagues</div>
                    </a>
                </div>
            </div>
        </section>

        <section class="px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-4xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl sm:p-8">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <div>
                            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-300/20 bg-amber-300/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-amber-200">Hall of fame</div>
                            <h2 class="font-['Lexend'] text-3xl font-black uppercase tracking-tighter text-white sm:text-4xl">Global top fans</h2>
                        </div>
                        <a href="{{ route('leaderboard') }}" class="hidden rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-black uppercase tracking-[0.28em] text-white/70 transition hover:border-emerald-300/40 hover:text-white md:inline-flex">Leaderboard</a>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 rounded-2xl border border-amber-300/20 bg-amber-300/10 p-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-300 text-[#07110b] font-['Lexend'] text-xl font-black">1</div>
                            <div class="flex-1">
                                <div class="font-bold text-white">@GunnerSteve</div>
                                <div class="text-sm text-white/55">Arsenal tribe • Tactician</div>
                            </div>
                            <div class="text-right">
                                <div class="font-['Lexend'] text-xl font-black text-white">45,200</div>
                                <div class="text-[10px] font-bold uppercase tracking-[0.28em] text-emerald-300">pts</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-[#07110b]/65 p-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 font-['Lexend'] text-xl font-black text-white/60">2</div>
                            <div class="flex-1">
                                <div class="font-bold text-white">@BavarianKing</div>
                                <div class="text-sm text-white/55">Munich tribe • Predictor</div>
                            </div>
                            <div class="text-right">
                                <div class="font-['Lexend'] text-xl font-black text-white/85">42,850</div>
                                <div class="text-[10px] font-bold uppercase tracking-[0.28em] text-white/45">pts</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-[#07110b]/65 p-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 font-['Lexend'] text-xl font-black text-white/40">3</div>
                            <div class="flex-1">
                                <div class="font-bold text-white">@Milanista99</div>
                                <div class="text-sm text-white/55">Milan tribe • Historian</div>
                            </div>
                            <div class="text-right">
                                <div class="font-['Lexend'] text-xl font-black text-white/85">39,100</div>
                                <div class="text-[10px] font-bold uppercase tracking-[0.28em] text-white/45">pts</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-4xl border border-white/10 bg-[#07110b]/85 p-6 backdrop-blur-xl sm:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-3xl text-emerald-300" style="font-variation-settings: 'FILL' 1;">military_tech</span>
                        <h3 class="font-['Lexend'] text-2xl font-black uppercase tracking-tighter text-white">Recent unlocks</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $unlockCards = [
                                ['icon' => 'local_fire_department', 'title' => 'On fire', 'text' => '5 correct score predictions in a row', 'color' => 'text-amber-300'],
                                ['icon' => 'record_voice_over', 'title' => 'Pundit', 'text' => '100+ upvotes on a tactical analysis', 'color' => 'text-emerald-300'],
                                ['icon' => 'groups', 'title' => 'Ultra', 'text' => 'Active in tribe for 365 days straight', 'color' => 'text-sky-300'],
                                ['icon' => 'lock', 'title' => 'Locked', 'text' => 'Keep participating to reveal', 'color' => 'text-white/45'],
                            ];
                        @endphp
                        @foreach ($unlockCards as $card)
                            <div class="rounded-2xl border border-white/8 bg-white/5 p-4 text-center transition hover:border-white/15">
                                <span class="material-symbols-outlined text-4xl {{ $card['color'] }}" style="font-variation-settings: 'FILL' 1;">{{ $card['icon'] }}</span>
                                <div class="mt-3 text-sm font-black uppercase tracking-[0.24em] text-white">{{ $card['title'] }}</div>
                                <div class="mt-2 text-xs leading-5 text-white/55">{{ $card['text'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-white/60">Trusted by the 12th man</div>
                    <h2 class="font-['Lexend'] text-4xl font-black uppercase tracking-tighter text-white sm:text-5xl">Built for serious fans</h2>
                    <div class="mt-8 grid gap-6 sm:grid-cols-2">
                        <div>
                            <div class="font-['Lexend'] text-5xl font-black text-white">{{ number_format(\App\Models\Post::count()) }}<span class="text-emerald-300">+</span></div>
                            <div class="mt-2 text-sm font-bold uppercase tracking-[0.24em] text-white/45">Fan posts</div>
                        </div>
                        <div>
                            <div class="font-['Lexend'] text-5xl font-black text-white">{{ number_format(\App\Models\Competition::count()) }}<span class="text-amber-300">+</span></div>
                            <div class="mt-2 text-sm font-bold uppercase tracking-[0.24em] text-white/45">Competitions</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-4xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl sm:p-8">
                    <span class="material-symbols-outlined mb-4 text-4xl text-amber-300" style="font-variation-settings: 'FILL' 1;">format_quote</span>
                    <p class="text-xl leading-9 text-white/80 sm:text-2xl">"Finally, a platform that treats fans like analysts. The real-time data combined with community banter makes matchday feel alive again."</p>
                    <div class="mt-8 flex items-center gap-4 border-t border-white/10 pt-6">
                        <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-emerald-300 bg-white/10">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80" alt="User portrait" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="font-['Lexend'] text-lg font-bold text-white">Sarah Jenkins</div>
                            <div class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-300">Founding fan</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @guest
        <section class="px-4 pb-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl overflow-hidden rounded-[2.25rem] border border-white/10 bg-[linear-gradient(135deg,rgba(74,225,118,0.18),rgba(7,17,11,0.96)_55%,rgba(249,189,34,0.12))] p-8 sm:p-14">
                <div class="max-w-3xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-white/70">Join the movement</div>
                    <h2 class="font-['Lexend'] text-4xl font-black uppercase tracking-tighter text-white sm:text-6xl">Claim your spot on the terraces.</h2>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-white/75">Create your account and start following matches, joining tribes, and earning badges with the rest of the fan base.</p>
                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-7 py-4 text-base font-black uppercase tracking-wider text-[#07110b] transition hover:scale-[1.02] hover:bg-emerald-50">Create free account</a>
                        <a href="{{ route('matches.live') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/5 px-7 py-4 text-base font-bold uppercase tracking-wider text-white transition hover:bg-white/10">Browse live matches</a>
                    </div>
                </div>
            </div>
        </section>
        @endguest
        </div>

        <footer class="border-t border-white/10 bg-[#07110b] px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div>
                    <div class="font-['Lexend'] text-lg font-black uppercase tracking-tight text-white">Soccer Aficionado</div>
                    <p class="mt-2 text-sm leading-6 text-white/55">The ultimate platform for football fans worldwide. Track, discuss, and celebrate the beautiful game.</p>
                </div>
                <div class="flex flex-row flex-wrap items-center gap-x-6 gap-y-3 text-sm font-semibold uppercase tracking-wider text-white/55">
                    <a href="{{ route('matches.index') }}" class="transition hover:text-white">Matches</a>
                    <a href="{{ route('clubs.index') }}" class="transition hover:text-white">Clubs</a>
                    <a href="{{ route('competitions.index') }}" class="transition hover:text-white">Competitions</a>
                    @auth
                        <a href="{{ route('leaderboard') }}" class="transition hover:text-white">Leaderboard</a>
                    @else
                        <a href="{{ route('login') }}" class="transition hover:text-white">Sign in</a>
                    @endauth
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
