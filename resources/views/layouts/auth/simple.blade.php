<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 overflow-x-hidden">
        <div class="flex min-h-svh">
            {{-- Left decorative panel (hidden on mobile) --}}
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-700 items-center justify-center p-12">
                <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=600&q=30'); background-size: cover; background-position: center;"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-green-600/90 via-emerald-600/90 to-teal-700/90"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
                <div class="relative z-10 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/15 backdrop-blur-sm mb-8 text-5xl shadow-xl">⚽</div>
                    <h2 class="text-4xl font-bold text-white mb-4">Soccer Aficionado</h2>
                    <p class="text-green-100 text-lg max-w-md leading-relaxed">Join the ultimate football fan community. Track matches, earn badges, and connect with fans worldwide.</p>
                    <div class="mt-10 flex items-center justify-center gap-6 text-green-100/80">
                        <div class="text-center"><div class="text-2xl font-bold text-white">{{ number_format(\App\Models\User::count()) }}+</div><div class="text-xs mt-1">Fans</div></div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center"><div class="text-2xl font-bold text-white">{{ \App\Models\Club::count() }}+</div><div class="text-xs mt-1">Clubs</div></div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center"><div class="text-2xl font-bold text-white">{{ \App\Models\Community::count() }}+</div><div class="text-xs mt-1">Communities</div></div>
                    </div>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col items-center justify-center gap-6 p-6 md:p-10 bg-white dark:bg-zinc-950">
                <div class="flex w-full max-w-sm flex-col gap-2">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 font-medium mb-2" wire:navigate>
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-xl shadow-green-500/25 text-2xl">
                            ⚽
                        </div>
                        <span class="text-lg font-bold text-zinc-900 dark:text-white lg:hidden">Soccer Aficionado</span>
                    </a>
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
