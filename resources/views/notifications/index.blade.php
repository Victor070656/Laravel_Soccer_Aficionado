<x-layouts::app :title="__('Notifications')">
    <div class="max-w-3xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">🔔</span>
                        Notifications
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">Stay updated with your latest activity and interactions.</p>
                </div>
                @if($notifications->where('read_at', null)->count())
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20 hover:scale-105">
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
                    'like' => ['icon' => '♥', 'bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-600'],
                    'comment' => ['icon' => '💬', 'bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600'],
                    'follow' => ['icon' => '👤', 'bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-600'],
                    'community' => ['icon' => '👥', 'bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600'],
                    'badge' => ['icon' => '🏅', 'bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-600'],
                    'mention' => ['icon' => '@', 'bg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'text' => 'text-indigo-600'],
                    default => ['icon' => '🔔', 'bg' => 'bg-zinc-100 dark:bg-zinc-700', 'text' => 'text-zinc-600'],
                };
            @endphp
            <div class="rounded-2xl border {{ $notification->read_at ? 'border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800' : 'border-green-200/80 dark:border-green-800/60 bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-green-900/15 dark:to-emerald-900/15' }} shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="p-4 sm:p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $notifConfig['bg'] }} flex items-center justify-center {{ $notifConfig['text'] }} text-lg">
                            {{ $notifConfig['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $notification->message }}</p>
                            <span class="text-xs text-zinc-400 dark:text-zinc-500 mt-1.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if(!$notification->read_at)
                        <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 hover:bg-green-200 dark:hover:bg-green-900/50 transition" title="Mark as read">
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
