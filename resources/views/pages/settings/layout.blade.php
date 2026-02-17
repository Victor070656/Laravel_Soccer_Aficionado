<div class="flex items-start max-md:flex-col gap-8">
    <div class="w-full pb-4 md:w-[240px] shrink-0">
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-3 shadow-lg shadow-black/5">
            <flux:navlist aria-label="{{ __('Settings') }}">
                <flux:navlist.item :href="route('profile.edit')" wire:navigate icon="user">{{ __('Profile') }}</flux:navlist.item>
                <flux:navlist.item :href="route('user-password.edit')" wire:navigate icon="lock-closed">{{ __('Password') }}</flux:navlist.item>
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <flux:navlist.item :href="route('two-factor.show')" wire:navigate icon="shield-check">{{ __('Two-Factor Auth') }}</flux:navlist.item>
                @endif
                <flux:navlist.item :href="route('appearance.edit')" wire:navigate icon="swatch">{{ __('Appearance') }}</flux:navlist.item>
            </flux:navlist>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading size="lg" class="!text-xl !font-bold">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading class="mt-1">{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-6 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
