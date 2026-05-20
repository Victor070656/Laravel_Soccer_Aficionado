<x-layouts::app :title="__('Edit Profile')">
    <div class="min-h-screen bg-surface py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-headline-lg text-on-surface">{{ __('Edit Profile') }}</h1>
                <p class="mt-2 text-body-md text-on-surface-variant">{{ __('Update your profile information and preferences') }}</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 glass-card rounded-2xl p-4 bg-primary-container/15 border border-primary-container/30">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">✓</span>
                        <p class="text-body-sm text-on-surface">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Edit Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="glass-card rounded-2xl p-6 border border-outline-variant/20">
                    <h2 class="text-headline-md text-on-surface mb-6">{{ __('Basic Information') }}</h2>

                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Full Name') }}
                                <span class="text-error">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('name')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Username') }}
                                <span class="text-error">*</span>
                            </label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('username')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Email Address') }}
                                <span class="text-error">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('email')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Bio') }}
                            </label>
                            <textarea id="bio" name="bio" maxlength="500" rows="4"
                                      placeholder="{{ __('Tell others about yourself...') }}"
                                      class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
                            <div class="mt-1 text-label-xs text-on-surface-variant">
                                {{ strlen($user->bio ?? '') }}/500 {{ __('characters') }}
                            </div>
                            @error('bio')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Profile Photo Section -->
                <div class="glass-card rounded-2xl p-6 border border-outline-variant/20">
                    <h2 class="text-headline-md text-on-surface mb-6">{{ __('Profile Photo') }}</h2>

                    <div class="space-y-4">
                        <!-- Current Avatar Preview -->
                        @if($user->avatar)
                            <div>
                                <p class="text-body-sm font-medium text-on-surface mb-3">{{ __('Current Photo') }}</p>
                                <div class="flex items-center gap-4">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                         class="h-20 w-20 rounded-xl object-cover border-2 border-outline-variant/40">
                                    <p class="text-body-xs text-on-surface-variant">{{ __('Your current profile photo') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-20 w-20 rounded-xl bg-surface-container-high border-2 border-outline-variant/40">
                                <span class="text-2xl font-bold text-on-surface">{{ $user->initials() }}</span>
                            </div>
                        @endif

                        <!-- Upload New Photo -->
                        <div>
                            <label for="avatar" class="block text-body-sm font-medium text-on-surface mb-3">
                                {{ __('Upload New Photo') }}
                            </label>
                            <div class="relative">
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                       class="block w-full text-sm text-on-surface-variant
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-primary-container file:text-on-primary-container
                                       hover:file:bg-primary-container/80
                                       file:cursor-pointer file:transition-all">
                            </div>
                            <p class="mt-2 text-label-xs text-on-surface-variant">
                                {{ __('JPG, PNG or GIF (max 2MB)') }}
                            </p>
                            @error('avatar')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location & Preferences Section -->
                <div class="glass-card rounded-2xl p-6 border border-outline-variant/20">
                    <h2 class="text-headline-md text-on-surface mb-6">{{ __('Location & Preferences') }}</h2>

                    <div class="space-y-4">
                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Country') }}
                            </label>
                            <input type="text" id="country" name="country" value="{{ old('country', $user->country) }}"
                                   placeholder="{{ __('e.g., Nigeria') }}"
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('country')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('State/Region') }}
                            </label>
                            <input type="text" id="state" name="state" value="{{ old('state', $user->state) }}"
                                   placeholder="{{ __('e.g., Lagos') }}"
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('state')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Timezone -->
                        <div>
                            <label for="timezone" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Timezone') }}
                            </label>
                            <select id="timezone" name="timezone"
                                    class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                <option value="">{{ __('Select a timezone') }}</option>
                                @php
                                    $timezones = [
                                        'Africa/Lagos' => 'West Africa Time (Lagos)',
                                        'Africa/Cairo' => 'East Africa Time (Cairo)',
                                        'Europe/London' => 'GMT (London)',
                                        'Europe/Paris' => 'CET (Paris)',
                                        'America/New_York' => 'EST (New York)',
                                        'America/Chicago' => 'CST (Chicago)',
                                        'America/Los_Angeles' => 'PST (Los Angeles)',
                                        'Asia/Tokyo' => 'JST (Tokyo)',
                                        'Australia/Sydney' => 'AEDT (Sydney)',
                                    ];
                                @endphp
                                @foreach($timezones as $tz => $label)
                                    <option value="{{ $tz }}" {{ old('timezone', $user->timezone) === $tz ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Football Preferences Section -->
                <div class="glass-card rounded-2xl p-6 border border-outline-variant/20">
                    <h2 class="text-headline-md text-on-surface mb-6">{{ __('Football Preferences') }}</h2>

                    <div class="space-y-4">
                        <!-- Favorite Player -->
                        <div>
                            <label for="favorite_player_id" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Favorite Player ID') }}
                            </label>
                            <input type="number" id="favorite_player_id" name="favorite_player_id" 
                                   value="{{ old('favorite_player_id', $user->favorite_player_id) }}"
                                   placeholder="{{ __('Enter player ID') }}"
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('favorite_player_id')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Favorite Coach -->
                        <div>
                            <label for="favorite_coach" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Favorite Coach') }}
                            </label>
                            <input type="text" id="favorite_coach" name="favorite_coach" 
                                   value="{{ old('favorite_coach', $user->favorite_coach) }}"
                                   placeholder="{{ __('e.g., Carlo Ancelotti') }}"
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('favorite_coach')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Football Personality -->
                        <div>
                            <label for="football_personality" class="block text-body-sm font-medium text-on-surface mb-2">
                                {{ __('Football Personality') }}
                            </label>
                            <input type="text" id="football_personality" name="football_personality" 
                                   value="{{ old('football_personality', $user->football_personality) }}"
                                   placeholder="{{ __('e.g., Tactical Analyst, Striker, Goalkeeper, etc.') }}"
                                   class="w-full rounded-xl border border-outline-variant bg-surface-container px-4 py-3 text-on-surface placeholder-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('football_personality')
                                <span class="text-error text-label-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('profiles.show', $user) }}"
                       class="px-6 py-3 rounded-xl border border-outline-variant bg-surface-container text-on-surface font-semibold text-body-sm hover:bg-surface-container-high transition-all">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-primary-container text-on-primary-container font-semibold text-body-sm hover:bg-primary-container/90 transition-all shadow-lg shadow-primary-container/20">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
