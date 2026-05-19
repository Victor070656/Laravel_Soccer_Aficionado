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
    public string $username = '';
    public string $email = '';
    public string $bio = '';
    public string $country = '';
    public string $state = '';
    public string $timezone = '';
    public string $football_personality = '';
    public string $favorite_coach = '';
    public $avatar = null;
    public ?string $currentAvatar = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        $this->bio = $user->bio ?? '';
        $this->country = $user->country ?? '';
        $this->state = $user->state ?? '';
        $this->timezone = $user->timezone ?? '';
        $this->football_personality = $user->football_personality ?? '';
        $this->favorite_coach = $user->favorite_coach ?? '';
        $this->currentAvatar = $user->avatar;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate(
            array_merge($this->profileRules($user->id), [
                'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
                'state' => ['nullable', 'string', 'max:100'],
                'football_personality' => ['nullable', 'string', 'max:100'],
                'favorite_coach' => ['nullable', 'string', 'max:100'],
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            ]),
        );

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
        return Auth::user() instanceof MustVerifyEmail && !Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return !Auth::user() instanceof MustVerifyEmail || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your profile information, avatar, and football identity')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-8">
            {{-- Section: Identity --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="user-circle" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">
                        {{ __('Public Identity') }}</flux:heading>
                </div>

                <div class="flex-shrink-0">
                    <flux:label class="mb-2">{{ __('Photo') }}</flux:label>
                    <div class="relative group" wire:loading.class="opacity-50">
                        <div
                            class="w-24 h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center text-3xl font-black text-green-700 dark:text-green-400 border-2 border-green-500/10 shadow-inner">
                            @if ($avatar)
                                {{-- Temporary Preview --}}
                                <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover"
                                    alt="Preview">
                            @elseif ($currentAvatar)
                                {{-- Current Stored Avatar --}}
                                <img src="{{ Storage::url($currentAvatar) }}" class="w-full h-full object-cover"
                                    alt="Current Avatar">
                            @else
                                {{ Auth::user()->initials() }}
                            @endif

                            {{-- Loading Spinner --}}
                            <div wire:loading.delay.shortest
                                class="absolute inset-0 bg-white/50 dark:bg-zinc-900/50 flex items-center justify-center">
                                <flux:icon icon="arrow-path" variant="mini" class="animate-spin text-green-600" />
                            </div>
                        </div>

                        {{-- Upload Button --}}
                        <label
                            class="absolute -bottom-2 -right-2 p-1.5 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                            title="Change Photo">
                            <flux:icon icon="camera" variant="mini" class="text-zinc-500" />
                            <input type="file" wire:model="avatar" accept="image/*" class="hidden"
                                aria-label="Upload new profile photo" />
                        </label>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-2 flex gap-3">
                        @if ($currentAvatar && !$avatar)
                            <button type="button" wire:click="removeAvatar"
                                class="text-[10px] uppercase font-bold tracking-widest text-red-500 hover:text-red-700 transition-colors">
                                {{ __('Remove') }}
                            </button>
                        @endif
                        @if ($avatar)
                            <button type="button" wire:click="$set('avatar', null)"
                                class="text-[10px] uppercase font-bold tracking-widest text-zinc-500 hover:text-zinc-700 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                        @endif
                    </div>

                    @error('avatar')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Section: Contact & Bio --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="envelope" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">
                        {{ __('Contact & Bio') }}</flux:heading>
                </div>

                <div class="space-y-4">
                    <flux:input wire:model="email" :label="__('Email Address')" type="email" required
                        autocomplete="email" />

                    @if ($this->hasUnverifiedEmail)
                        <div
                            class="p-3 rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800">
                            <flux:text class="text-xs text-amber-800 dark:text-amber-400">
                                {{ __('Your email is unverified.') }}
                                <flux:link class="text-xs font-bold cursor-pointer underline"
                                    wire:click.prevent="resendVerificationNotification">
                                    {{ __('Resend verification email') }}
                                </flux:link>
                            </flux:text>
                            @if (session('status') === 'verification-link-sent')
                                <flux:text
                                    class="mt-1 text-[10px] font-bold text-green-600 dark:text-green-400 uppercase">
                                    {{ __('Verification link sent!') }}
                                </flux:text>
                            @endif
                        </div>
                    @endif

                    <flux:textarea wire:model="bio" :label="__('Short Bio')" rows="3" maxlength="500"
                        placeholder="{{ __('Tell the community about your passion for football...') }}" />
                </div>
            </div>

            {{-- Section: Football Identity --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="bolt" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">
                        {{ __('Football Identity') }}</flux:heading>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="football_personality" :label="__('Football Personality')"
                        placeholder="e.g. Tactical Analyst, Ultras, Casual Viewer" />
                    <flux:input wire:model="favorite_coach" :label="__('Favorite Coach')"
                        placeholder="e.g. Pep Guardiola, Jose Mourinho" />
                </div>
            </div>

            {{-- Section: Location & Timezone --}}
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:icon icon="globe-americas" variant="mini" class="text-green-600" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-zinc-500">
                        {{ __('Location & Time') }}</flux:heading>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="country" :label="__('Country')" type="text"
                        placeholder="e.g. United Kingdom" autocomplete="country-name" />
                    <flux:input wire:model="state" :label="__('City / State')" type="text"
                        placeholder="e.g. London" />
                </div>

                <flux:select wire:model="timezone" :label="__('Timezone')">
                    <option value="">{{ __('Select timezone') }}</option>
                    @foreach (timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}">{{ $tz }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit"
                        class="!bg-gradient-to-r !from-green-600 !to-emerald-600 hover:!from-green-500 hover:!to-emerald-500 !shadow-lg !shadow-green-500/25 px-8"
                        data-test="update-profile-button">
                        {{ __('Save Changes') }}
                    </flux:button>

                    <x-action-message on="profile-updated">
                        <span class="text-green-600 font-bold text-sm flex items-center gap-1">
                            <flux:icon icon="check-circle" variant="mini" />
                            {{ __('Saved successfully.') }}
                        </span>
                    </x-action-message>
                </div>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <div class="mt-12 pt-12 border-t border-red-100 dark:border-red-900/20">
                <livewire:pages::settings.delete-user-form />
            </div>
        @endif
    </x-pages::settings.layout>
</section>
