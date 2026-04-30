<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#bfff00]/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-[#bfff00]">lock_reset</span>
            </div>
        </div>
        
        <x-auth-header :title="__('Set New Password')" :description="__('Choose a strong new password for your account.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
                placeholder="name@stadium.com"
                icon="envelope"
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('New Password')"
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
                :label="__('Confirm new password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('••••••••')"
                icon="lock-closed"
                viewable
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <div class="flex items-center justify-end mt-4">
                <flux:button type="submit" variant="primary" class="w-full h-14 !bg-[#bfff00] !text-[#0a2e1c] !font-display !text-lg !rounded-xl !shadow-[0_0_20px_rgba(191,255,0,0.2)] hover:shadow-[0_0_30px_rgba(191,255,0,0.4)] active:scale-[0.98] transition-all" data-test="reset-password-button">
                    {{ __('Update Password') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
