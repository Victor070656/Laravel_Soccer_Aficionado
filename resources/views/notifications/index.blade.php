<x-layouts::app :title="__('Notifications')">
    <div class="min-h-screen bg-surface py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-secondary/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                            <span class="w-12 h-12 rounded-xl bg-secondary/20 flex items-center justify-center text-2xl">🔔</span>
                            Notifications
                        </h1>
                        <p class="mt-2 text-body-md text-on-surface-variant">Stay updated with your latest activity and interactions.</p>
                    </div>
                    @if($notifications->whereNull('read_at')->count())
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button class="rounded-xl bg-secondary/20 px-4 py-2 text-sm font-semibold text-secondary hover:bg-secondary/30 transition-colors">
                                Mark all read
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    @php
                        $notifConfig = match($notification->type) {
                            'like' => ['icon' => '♥', 'bg' => 'bg-secondary/15', 'text' => 'text-secondary'],
                            'comment' => ['icon' => '💬', 'bg' => 'bg-primary-container/15', 'text' => 'text-primary-container'],
                            'follow' => ['icon' => '👤', 'bg' => 'bg-primary-container/15', 'text' => 'text-primary-container'],
                            'community' => ['icon' => '👥', 'bg' => 'bg-secondary/15', 'text' => 'text-secondary'],
                            'badge' => ['icon' => '🏅', 'bg' => 'bg-tertiary/15', 'text' => 'text-tertiary'],
                            'mention' => ['icon' => '@', 'bg' => 'bg-primary-container/15', 'text' => 'text-primary-container'],
                            default => ['icon' => '🔔', 'bg' => 'bg-surface-container-high', 'text' => 'text-on-surface-variant'],
                        };
                    @endphp

                    <div class="glass-card rounded-xl p-4 {{ $notification->read_at ? 'opacity-85' : 'ring-1 ring-primary-container/20' }}">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-xl {{ $notifConfig['bg'] }} flex items-center justify-center {{ $notifConfig['text'] }} text-lg">
                                {{ $notifConfig['icon'] }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-on-surface">{{ $notification->message }}</p>
                                <span class="mt-1 block text-xs text-on-surface-variant">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            @if(! $notification->read_at)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button class="rounded-lg bg-primary-container/15 p-2 text-primary-container hover:bg-primary-container/25 transition-colors" title="Mark as read">
                                        ✓
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="glass-card rounded-xl p-8 text-center">
                        <div class="text-4xl mb-4">🔔</div>
                        <h2 class="text-headline-md text-on-surface mb-2">No notifications</h2>
                        <p class="text-body-md text-on-surface-variant">You're all caught up.</p>
                    </div>
                @endforelse
            </div>

            <div>{{ $notifications->links() }}</div>
        </div>
    </div>
</x-layouts::app>
