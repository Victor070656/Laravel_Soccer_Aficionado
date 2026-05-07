<div wire:poll.30s>
    @if($liveMatchCount > 0)
        <div class="lg:hidden fixed bottom-16 right-4 z-50">
            <a href="{{ route('matches.live') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-container text-on-primary-container text-xs font-bold shadow-lg hover:scale-105 transition-transform">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                </span>
                {{ $liveMatchCount }} Live
            </a>
        </div>
    @endif
</div>
