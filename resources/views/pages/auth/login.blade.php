<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <x-auth-header :title="__('Welcome Back')" :description="__('Log in to your account to continue your journey.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-8">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1">
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="name@stadium.com"
                    icon="envelope"
                    class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
                />
            </div>

            <!-- Password -->
            <div class="space-y-1 relative">
                <div class="flex justify-between items-center mb-[-32px] relative z-10 px-1">
                    <label class="opacity-0 pointer-events-none">Password</label>
                    @if (Route::has('password.request'))
                        <flux:link class="text-xs font-bold text-primary-container hover:underline uppercase tracking-wider" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot?') }}
                        </flux:link>
                    @endif
                </div>
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('••••••••')"
                    icon="lock-closed"
                    viewable
                    class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
                />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember this device')" :checked="old('remember')" class="!text-on-surface" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full h-14 font-display text-lg rounded-xl shadow-lg shadow-primary-container/20 hover:bg-primary-container/90 active:scale-[0.98] transition-all" data-test="login-button">
                    {{ __('Login') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="text-center font-medium text-sm text-on-surface-variant pt-4">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate class="text-primary-container hover:underline font-bold ml-1 uppercase tracking-wider">{{ __('Join the League') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
