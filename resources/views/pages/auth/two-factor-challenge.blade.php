<x-layouts::auth>
    <div class="flex flex-col gap-6 animate-fade-in-up">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <div class="text-center lg:text-left mb-2">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#bfff00]/10 mb-4">
                        <span class="material-symbols-outlined text-3xl text-[#bfff00]">lock_person</span>
                    </div>
                </div>
                <x-auth-header
                    :title="__('Security Check')"
                    :description="__('Enter the 6-digit code from your authenticator app.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <div class="text-center lg:text-left mb-2">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#bfff00]/10 mb-4">
                        <span class="material-symbols-outlined text-3xl text-[#bfff00]">key</span>
                    </div>
                </div>
                <x-auth-header
                    :title="__('Recovery Code')"
                    :description="__('Please confirm access to your account by entering an emergency recovery code.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-6">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center lg:justify-start my-6">
                            <flux:otp
                                x-model="code"
                                length="6"
                                name="code"
                                label="OTP Code"
                                label:sr-only
                             />
                        </div>
                    </div>

                    <div x-show="showRecoveryInput" class="my-6">
                        <flux:input
                            type="text"
                            name="recovery_code"
                            x-ref="recovery_code"
                            x-bind:required="showRecoveryInput"
                            autocomplete="one-time-code"
                            x-model="recovery_code"
                            placeholder="Enter recovery code"
                            icon="key"
                            class="uppercase tracking-widest !text-[10px] font-bold text-on-surface-variant"
                        />
                        @error('recovery_code')
                            <flux:text color="red" class="mt-2 text-xs">
                                {{ $message }}
                            </flux:text>
                        @enderror
                    </div>

                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full h-14 !bg-[#bfff00] !text-[#0a2e1c] !font-display !text-lg !rounded-xl !shadow-[0_0_20px_rgba(191,255,0,0.2)] hover:shadow-[0_0_30px_rgba(191,255,0,0.4)] active:scale-[0.98] transition-all"
                    >
                        {{ __('Authenticate') }}
                    </flux:button>
                </div>

                <div class="mt-8 space-x-1 text-sm leading-5 text-center lg:text-left">
                    <span class="text-on-surface-variant">{{ __('Trouble signing in?') }}</span>
                    <div class="inline font-bold !text-[#bfff00] hover:underline cursor-pointer uppercase tracking-wider text-xs">
                        <span x-show="!showRecoveryInput" @click="toggleInput()">{{ __('Use recovery code') }}</span>
                        <span x-show="showRecoveryInput" @click="toggleInput()">{{ __('Use auth code') }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>
