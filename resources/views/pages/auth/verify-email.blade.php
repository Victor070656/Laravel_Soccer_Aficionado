<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div class="text-center lg:text-left mb-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-container/10 mb-4">
                <span class="material-symbols-outlined text-3xl text-primary-container">mail</span>
            </div>
        </div>
        
        <x-auth-header :title="__('Verify Email')" :description="__('We sent a verification link to your email address. Click it to verify your account.')" />

        @if (session('status') == 'verification-link-sent')
            <div class="text-center rounded-xl bg-primary-container/10 border border-primary-container/20 p-4">
                <flux:text class="font-medium text-primary-container">
                    ✅ {{ __('A new verification link has been sent to your email address.') }}
                </flux:text>
            </div>
        @endif

        <div class="flex flex-col items-center justify-between gap-4 pt-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full h-14 font-display text-lg rounded-xl shadow-lg shadow-primary-container/20 hover:bg-primary-container/90 active:scale-[0.98] transition-all">
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
