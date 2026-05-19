<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            .glass-surface {
                background: rgba(26, 26, 26, 0.8);
                backdrop-filter: blur(20px);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                border-left: 1px solid rgba(255, 255, 255, 0.1);
            }
            .stadium-overlay {
                background: linear-gradient(to right, rgba(10, 46, 28, 0.9), rgba(5, 22, 14, 0.4));
            }
            .pitch-mesh {
                background-image: radial-gradient(#bfff00 0.5px, transparent 0.5px);
                background-size: 24px 24px;
                opacity: 0.03;
            }
        </style>
    </head>
    <body class="auth-page bg-background text-on-background font-sans overflow-x-hidden">
        <main class="flex min-h-screen w-full flex-col lg:flex-row">
            {{-- Left Panel: Hero Content --}}
            <section class="relative hidden lg:flex lg:w-7/12 xl:w-8/12 flex-col justify-center items-start px-12 overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <video autoplay muted loop playsinline class="h-full w-full object-cover">
                        <source src="{{ asset('assets/soca.mp4') }}" type="video/mp4">
                    </video>
                    <div class="absolute inset-0 stadium-overlay z-10"></div>
                    <div class="absolute inset-0 pitch-mesh z-20"></div>
                </div>

                <div class="relative z-30 animate-fade-in-left">
                    <header class="mb-8">
                        <h1 class="text-5xl font-extrabold text-[#bfff00] uppercase tracking-tighter mb-2 font-display">
                            Soccer Aficionado
                        </h1>
                        <div class="h-1 w-24 bg-secondary mb-6"></div>
                        <p class="text-4xl font-bold text-white font-display">
                            Join the Terraces.
                        </p>
                    </header>
                    <p class="text-lg text-on-surface-variant max-w-4xl mb-10 leading-relaxed">
                        Experience the beautiful game with precision data, real-time scoreboards, and the raw energy of the global fan community. Your stadium seat awaits.
                    </p>

                    <div class="flex gap-6 animate-fade-in-up">
                        <div class="glass-surface p-6 rounded-xl border border-white/5">
                            <span class="block text-2xl font-bold text-[#bfff00] font-display">24/7</span>
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Live Coverage</span>
                        </div>
                        <div class="glass-surface p-6 rounded-xl border border-white/5">
                            <span class="block text-2xl font-bold text-[#bfff00] font-display">500+</span>
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Global Leagues</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Right Panel: Form Panel --}}
            <section class="auth-page-panel w-full flex-1 lg:w-5/12 xl:w-4/12 bg-surface-container-lowest flex flex-col justify-start lg:justify-center items-center px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12 relative overflow-y-auto">
                <div class="lg:hidden absolute top-8 left-8">
                    <span class="text-2xl font-bold text-[#bfff00] uppercase tracking-tighter font-display">SA</span>
                </div>

                <div class="w-full max-w-md space-y-6 sm:space-y-8 pt-12 lg:pt-0 pb-24 lg:pb-0">
                    {{ $slot }}
                </div>

                <footer class="mt-8 w-full flex flex-col gap-3 border-t border-white/10 bg-[#05160e] px-4 py-4 sm:flex-row sm:items-center sm:justify-between lg:absolute lg:bottom-0 lg:mt-0 lg:px-8">
                    <span class="text-[10px] font-display text-zinc-500 uppercase tracking-widest">© {{ date('Y') }} Soccer Aficionado</span>
                    <div class="flex gap-4">
                        <a class="text-[10px] font-display text-zinc-500 uppercase tracking-widest hover:text-[#bfff00] transition-colors" href="#">Terms</a>
                        <a class="text-[10px] font-display text-zinc-500 uppercase tracking-widest hover:text-[#bfff00] transition-colors" href="#">Privacy</a>
                    </div>
                </footer>
            </section>
        </main>
        @fluxScripts
    </body>
</html>
