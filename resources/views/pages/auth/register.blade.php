<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <x-auth-header :title="__('Create Account')" :description="__('Join the global fan community today.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Full Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Your Name')"
                icon="user"
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="name@stadium.com"
                icon="envelope"
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    icon="lock-closed"
                    viewable
                    class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
                />

                <!-- Confirm Password -->
                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    icon="shield-check"
                    viewable
                    class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
                />
            </div>

            <div class="flex items-start gap-2 py-2">
                <flux:checkbox id="terms" name="terms" required class="mt-1" />
                <label for="terms" class="text-xs text-on-surface-variant leading-relaxed">
                    I agree to the <a class="text-primary-container hover:underline" href="#">Terms of Service</a> and <a class="text-primary-container hover:underline" href="#">Privacy Policy</a>.
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <flux:button type="submit" variant="primary" class="w-full h-14 font-display text-lg rounded-xl shadow-lg shadow-primary-container/20 hover:bg-primary-container/90 active:scale-[0.98] transition-all" data-test="register-user-button">
                    {{ __('Create Account') }}
                </flux:button>
            </div>
        </form>

        <div class="text-center font-medium text-sm text-on-surface-variant pt-4">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate class="text-primary-container hover:underline font-bold ml-1 uppercase tracking-wider">{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
