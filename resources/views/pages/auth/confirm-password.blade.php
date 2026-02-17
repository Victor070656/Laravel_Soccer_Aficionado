<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-500/10 to-amber-500/10 dark:from-yellow-500/20 dark:to-amber-500/20 mb-4">
                <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full !bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
