<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-container/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-primary-container">key</span>
            </div>
        </div>
        
        <x-auth-header :title="__('Lost Your Key?')" :description="__('No worries! Enter your email and we\'ll send you a recovery link.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email Address')"
                type="email"
                required
                autofocus
                placeholder="name@stadium.com"
                icon="envelope"
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <flux:button variant="primary" type="submit" class="w-full h-14 font-display text-lg rounded-xl shadow-lg shadow-primary-container/20 hover:bg-primary-container/90 active:scale-[0.98] transition-all" data-test="email-password-reset-link-button">
                {{ __('Send Reset Link') }}
            </flux:button>
        </form>

        <div class="text-center font-medium text-sm text-on-surface-variant pt-4">
            <flux:link :href="route('login')" wire:navigate class="text-primary-container hover:underline font-bold uppercase tracking-wider flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                {{ __('Back to Login') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
