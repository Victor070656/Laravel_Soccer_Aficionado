<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500/10 to-emerald-500/10 dark:from-green-500/20 dark:to-emerald-500/20 mb-4">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
        <x-auth-header :title="__('Reset password')" :description="__('Choose a strong new password for your account')" />

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
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full !bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25" data-test="reset-password-button">
                    {{ __('Reset password') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
