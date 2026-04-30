<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#bfff00]/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-[#bfff00]">mail</span>
            </div>
        </div>
        
        <x-auth-header :title="__('Verify Email')" :description="__('We sent a verification link to your email address. Click it to verify your account.')" />

        @if (session('status') == 'verification-link-sent')
            <div class="text-center rounded-xl bg-[#bfff00]/10 border border-[#bfff00]/20 p-4">
                <flux:text class="font-medium !text-[#bfff00]">
                    ✅ {{ __('A new verification link has been sent to your email address.') }}
                </flux:text>
            </div>
        @endif

        <div class="flex flex-col items-center justify-between gap-4 pt-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full h-14 !bg-[#bfff00] !text-[#0a2e1c] !font-display !text-lg !rounded-xl !shadow-[0_0_20px_rgba(191,255,0,0.2)] hover:shadow-[0_0_30px_rgba(191,255,0,0.4)] active:scale-[0.98] transition-all">
                    {{ __('Resend Email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm font-bold uppercase tracking-wider cursor-pointer transition-colors hover:!text-red-400" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
