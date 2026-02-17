<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use ProfileValidationRules, WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $bio = '';
    public string $country = '';
    public string $timezone = '';
    public $avatar = null;
    public ?string $currentAvatar = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->bio = $user->bio ?? '';
        $this->country = $user->country ?? '';
        $this->timezone = $user->timezone ?? '';
        $this->currentAvatar = $user->avatar;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate(array_merge(
            $this->profileRules($user->id),
            ['avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048']],
        ));

        if ($this->avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $this->avatar->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->currentAvatar = $user->avatar;
        $this->avatar = null;

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function removeAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            $this->currentAvatar = null;
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your profile information, avatar, and bio')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            {{-- Avatar --}}
            <div>
                <flux:label class="mb-2">{{ __('Profile Photo') }}</flux:label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-green-500/20 flex items-center justify-center text-xl font-bold text-green-700 dark:text-green-400">
                        @if ($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover" alt="Preview">
                        @elseif ($currentAvatar)
                            <img src="{{ asset('storage/' . $currentAvatar) }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            {{ Auth::user()->initials() }}
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <input type="file" wire:model="avatar" accept="image/*" class="text-sm text-zinc-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400" />
                        @if ($currentAvatar)
                            <button type="button" wire:click="removeAvatar" class="text-xs text-red-500 hover:text-red-700 text-left">{{ __('Remove photo') }}</button>
                        @endif
                    </div>
                </div>
                @error('avatar') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Bio --}}
            <div>
                <flux:label class="mb-1">{{ __('Bio') }}</flux:label>
                <textarea wire:model="bio" rows="3" maxlength="500"
                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:border-green-500 focus:ring-green-500"
                    placeholder="{{ __('Tell us about yourself...') }}"></textarea>
                <p class="text-xs text-zinc-500 mt-1">{{ __('Max 500 characters') }}</p>
                @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Country --}}
            <flux:input wire:model="country" :label="__('Country')" type="text" placeholder="e.g. United Kingdom" autocomplete="country-name" />

            {{-- Timezone --}}
            <div>
                <flux:label class="mb-1">{{ __('Timezone') }}</flux:label>
                <select wire:model="timezone"
                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:border-green-500 focus:ring-green-500">
                    <option value="">{{ __('Select timezone') }}</option>
                    @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}">{{ $tz }}</option>
                    @endforeach
                </select>
                @error('timezone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full !bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
