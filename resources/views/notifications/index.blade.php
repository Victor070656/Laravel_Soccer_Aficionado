<x-layouts::app :title="__('Notifications')">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Notifications</h1>
            @if($notifications->where('read_at', null)->count())
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button class="text-sm text-green-600 hover:text-green-700 font-medium">Mark all as read</button>
            </form>
            @endif
        </div>

        <div class="space-y-2">
            @forelse($notifications as $notification)
            <div class="rounded-xl border {{ $notification->read_at ? 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800' : 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20' }} p-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 text-lg">
                            @switch($notification->type)
                                @case('like') ♥ @break
                                @case('comment') 💬 @break
                                @case('follow') 👤 @break
                                @case('community') 👥 @break
                                @case('badge') 🏅 @break
                                @case('mention') @ @break
                                @default 🔔
                            @endswitch
                        </div>
                        <div>
                            <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $notification->message }}</p>
                            <span class="text-xs text-zinc-400 mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if(!$notification->read_at)
                    <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST">
                        @csrf
                        <button class="text-xs text-green-600 hover:text-green-700">✓</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-zinc-400">
                <p class="text-4xl mb-3">🔔</p>
                <p class="text-lg">No notifications</p>
                <p class="text-sm mt-1">You're all caught up!</p>
            </div>
            @endforelse
        </div>

        <div>{{ $notifications->links() }}</div>
    </div>
</x-layouts::app>
