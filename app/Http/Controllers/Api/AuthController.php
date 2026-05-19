<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->loadCount(['followers', 'following', 'posts']);
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Registration successful.', 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->is_banned) {
            return $this->error('Your account has been suspended.', 403);
        }

        $user->loadCount(['followers', 'following', 'posts']);
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out.');
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->loadCount(['followers', 'following', 'posts']);
        $user->load(['roles', 'favoriteClubs', 'badges']);

        return $this->success($user);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => ['sometimes', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
            'favorite_clubs' => 'nullable|array',
            'favorite_clubs.*' => 'integer',
            'primary_club_id' => 'nullable|integer',
        ]);

        $data = collect($validated)->only(['name', 'username', 'bio', 'country', 'timezone'])->toArray();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        if (array_key_exists('favorite_clubs', $validated)) {
            try {
                $api = app(\App\Services\FootballApiService::class);
                $syncData = [];
                foreach ($validated['favorite_clubs'] as $apiTeamId) {
                    try {
                        $raw = $api->getTeam((int) $apiTeamId);
                        if ($raw) {
                            $team = \App\Services\FootballApiService::normaliseTeam($raw);
                            $club = \App\Models\Club::fromApiTeam($team);
                            $syncData[$club->id] = [
                                'is_primary' => $apiTeamId == ($validated['primary_club_id'] ?? null),
                            ];
                        }
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning("Failed to process club API team ID {$apiTeamId}: ".$e->getMessage());

                        continue;
                    }
                }
                $user->favoriteClubs()->sync($syncData);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to sync favorite clubs: '.$e->getMessage());
            }
        }

        $user->loadCount(['followers', 'following', 'posts']);
        $user->load(['roles', 'favoriteClubs', 'badges']);

        return $this->success($user, 'Profile updated.');
    }
}
