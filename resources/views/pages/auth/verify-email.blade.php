<x-layouts::auth>
    <div class="mt-4 flex flex-col gap-6">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-500/20 dark:to-indigo-500/20 mb-4">
                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <flux:text class="text-center">
            {{ __('We sent a verification link to your email address. Click it to verify your account.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <div class="text-center rounded-xl bg-green-500/10 border border-green-500/20 p-3">
                <flux:text class="font-medium !dark:text-green-400 !text-green-600">
                    ✅ {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </flux:text>
            </div>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full !bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25">
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer transition-colors hover:!text-red-400" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
