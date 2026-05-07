<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-container/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-primary-container">security</span>
            </div>
        </div>
        
        <x-auth-header
            :title="__('Confirm Password')"
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
                :placeholder="__('••••••••')"
                icon="lock-closed"
                viewable
                class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
            />

            <div class="mt-4">
                <flux:button variant="primary" type="submit" class="w-full h-14 font-display text-lg rounded-xl shadow-lg shadow-primary-container/20 hover:bg-primary-container/90 active:scale-[0.98] transition-all" data-test="confirm-password-button">
                    {{ __('Confirm Action') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
