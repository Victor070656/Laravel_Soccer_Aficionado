<x-layouts::app :title="__('Notifications')">
    <div class="max-w-3xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-secondary via-secondary/80 to-secondary/60 p-6 sm:p-8 text-on-secondary shadow-xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">🔔</span>
                        Notifications
                    </h1>
                    <p class="text-on-secondary/70 text-sm sm:text-base">Stay updated with your latest activity and interactions.</p>
                </div>
                @if($notifications->where('read_at', null)->count())
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-on-secondary transition-all border border-white/20 hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mark All Read
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Notification Cards --}}
        <div class="space-y-3">
            @forelse($notifications as $notification)
            @php
                $notifConfig = match($notification->type) {
                    'like' => ['icon' => '♥', 'bg' => 'bg-secondary/20 dark:bg-secondary/25', 'text' => 'text-secondary'],
                    'comment' => ['icon' => '💬', 'bg' => 'bg-primary/20 dark:bg-primary/25', 'text' => 'text-primary'],
                    'follow' => ['icon' => '👤', 'bg' => 'bg-primary/20 dark:bg-primary/25', 'text' => 'text-primary'],
                    'community' => ['icon' => '👥', 'bg' => 'bg-secondary/20 dark:bg-secondary/25', 'text' => 'text-secondary'],
                    'badge' => ['icon' => '🏅', 'bg' => 'bg-tertiary/20 dark:bg-tertiary/25', 'text' => 'text-tertiary'],
                    'mention' => ['icon' => '@', 'bg' => 'bg-primary/20 dark:bg-primary/25', 'text' => 'text-primary'],
                    default => ['icon' => '🔔', 'bg' => 'bg-surface-container-high dark:bg-surface-container-high', 'text' => 'text-on-surface-variant'],
                };
            @endphp
            <div class="rounded-2xl border {{ $notification->read_at ? 'border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container' : 'border-primary/30 dark:border-primary/40 bg-gradient-to-r from-primary/10 to-primary/5 dark:from-primary/15 dark:to-primary/10' }} shadow-sm hover:shadow-md transition-shadow overflow-hidden glass-edge">
                <div class="p-4 sm:p-5">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-10 h-10 rounded-xl {{ $notifConfig['bg'] }} flex items-center justify-center {{ $notifConfig['text'] }} text-lg">
                            {{ $notifConfig['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-on-surface leading-relaxed">{{ $notification->message }}</p>
                            <span class="text-xs text-on-surface-variant mt-1.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if(!$notification->read_at)
                        <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="shrink-0">
                            @csrf
                            <button class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary hover:bg-primary/30 transition" title="Mark as read">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <span class="text-4xl">🔔</span>
                </div>
                <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">No Notifications</h2>
                <p class="text-sm text-zinc-400 dark:text-zinc-500 max-w-md mx-auto">You're all caught up! New notifications will appear here.</p>
            </div>
            @endforelse
        </div>

        <div>{{ $notifications->links() }}</div>
    </div>
</x-layouts::app>
