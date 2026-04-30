<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#bfff00]/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-[#bfff00]">security</span>
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
                <flux:button variant="primary" type="submit" class="w-full h-14 !bg-[#bfff00] !text-[#0a2e1c] !font-display !text-lg !rounded-xl !shadow-[0_0_20px_rgba(191,255,0,0.2)] hover:shadow-[0_0_30px_rgba(191,255,0,0.4)] active:scale-[0.98] transition-all" data-test="confirm-password-button">
                    {{ __('Confirm Action') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
