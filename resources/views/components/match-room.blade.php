@props([
    'match' => null,
    'isLive' => true,
    'matchScore' => '0-0',
    'minuteElapsed' => 45,
])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 lg:grid-cols-3 gap-6 h-screen']) }}>
    
    <!-- Main Chat Area -->
    <div class="lg:col-span-2 flex flex-col bg-surface-container rounded-lg overflow-hidden">
        
        <!-- Match Header -->
        <div class="card-live p-4 border-b-2 border-b-primary-container">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-error rounded-full animate-pulse"></span>
                        <p class="text-label-bold text-on-surface">LIVE</p>
                        <p class="text-label-sm text-on-surface-variant">{{ $minuteElapsed }}'</p>
                    </div>
                    <h2 class="text-headline-md text-on-surface mt-1">{{ $match?->display_name ?? 'Match Room' }}</h2>
                </div>
                <div class="text-center">
                    <p class="text-display-xl text-primary-container font-display">{{ $matchScore }}</p>
                </div>
            </div>
        </div>
        
        <!-- Chat Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            
            <!-- Sample Messages with Emoji Reactions -->
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-tertiary flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-label-bold text-on-surface">Rival Fan</span>
                        <span class="text-label-sm text-on-surface-variant">2m</span>
                    </div>
                    <p class="text-body-md text-on-surface mt-1">That was a clear handball! 🙄</p>
                    <div class="flex gap-1 mt-2">
                        <span class="px-2 py-1 bg-surface-bright rounded-full text-sm">👏 12</span>
                        <span class="px-2 py-1 bg-surface-bright rounded-full text-sm">⚽ 8</span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-secondary flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-label-bold text-on-surface">Club Legend</span>
                        <x-badge text="Verified" variant="active" />
                    </div>
                    <p class="text-body-md text-on-surface mt-1">Let's go! This team is on fire! 🔥🔥🔥</p>
                    <div class="flex gap-1 mt-2">
                        <span class="px-2 py-1 bg-surface-bright rounded-full text-sm">❤️ 234</span>
                        <span class="px-2 py-1 bg-surface-bright rounded-full text-sm">⚽ 89</span>
                    </div>
                </div>
            </div>
            
            <!-- Emoji Storm Indicator -->
            <div class="bg-primary-container/10 border-l-4 border-l-primary-container p-3 rounded-r-lg">
                <p class="text-label-sm text-primary-container">⚡ Emoji storm detected: ⚽🔥💪🎉</p>
            </div>
        </div>
        
        <!-- Input Area -->
        <div class="border-t border-outline-variant p-4 bg-surface-container-low">
            <div class="flex gap-2">
                <!-- Quick Reactions -->
                <div class="flex gap-1">
                    <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors text-xl" title="Goal!">⚽</button>
                    <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors text-xl" title="Fire!">🔥</button>
                    <button class="p-2 hover:bg-surface-bright rounded-lg transition-colors text-xl" title="Sad!">😢</button>
                </div>
                
                <!-- Message Input -->
                <div class="flex-1 flex gap-2">
                    <x-textarea 
                        placeholder="Join the banter..." 
                        class="py-2 resize-none h-auto"
                        rows="1"
                    />
                    <button class="px-4 py-2 bg-primary-container hover:bg-primary-container/90 text-on-primary-container rounded-lg font-label-bold transition-colors">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar: Stats & Live Ticker -->
    <div class="lg:col-span-1 flex flex-col gap-4">
        
        <!-- Live Stats Card -->
        <x-card variant="elevated" class="space-y-4">
            <h3 class="text-headline-md text-on-surface">Match Stats</h3>
            
            <div class="space-y-3">
                <!-- Possession -->
                <div>
                    <div class="flex justify-between text-label-sm text-on-surface-variant mb-1">
                        <span>Possession</span>
                        <span class="text-on-surface">52% - 48%</span>
                    </div>
                    <div class="h-2 bg-surface-bright rounded-full overflow-hidden flex">
                        <div class="w-1/2 bg-primary-container"></div>
                        <div class="w-1/2 bg-tertiary"></div>
                    </div>
                </div>
                
                <!-- Shots -->
                <div class="flex justify-between text-label-sm">
                    <span class="text-on-surface-variant">Shots</span>
                    <span class="text-on-surface">8 - 5</span>
                </div>
                
                <!-- Accuracy -->
                <div class="flex justify-between text-label-sm">
                    <span class="text-on-surface-variant">Accuracy</span>
                    <span class="text-on-surface">65% - 58%</span>
                </div>
            </div>
        </x-card>
        
        <!-- Sentiment Meter -->
        <x-card variant="elevated" class="space-y-3">
            <h3 class="text-headline-md text-on-surface">Fan Sentiment</h3>
            <div class="sentiment-meter h-8 rounded-lg overflow-hidden flex">
                <div class="w-1/3 bg-error"></div>
                <div class="w-1/3 bg-outline"></div>
                <div class="w-1/3 bg-primary-container"></div>
            </div>
            <div class="flex justify-between text-label-sm text-on-surface-variant">
                <span>😤 Upset</span>
                <span>😐 Neutral</span>
                <span>🔥 Hyped</span>
            </div>
        </x-card>
        
        <!-- Active Users -->
        <x-card variant="elevated" class="space-y-3">
            <h3 class="text-headline-md text-on-surface">In This Room</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-primary-container/20"></div>
                    <p class="text-body-md text-on-surface flex-1">{{ number_format(1234) }} fans watching</p>
                </div>
                <div class="text-label-sm text-on-surface-variant">
                    <p>Top contributors:</p>
                    <div class="flex gap-2 mt-2">
                        <div class="w-6 h-6 rounded-full bg-secondary"></div>
                        <div class="w-6 h-6 rounded-full bg-tertiary"></div>
                        <div class="w-6 h-6 rounded-full bg-primary-container/30"></div>
                    </div>
                </div>
            </div>
        </x-card>
        
        {{ $slot }}
    </div>
</div>
