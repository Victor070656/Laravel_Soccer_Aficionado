<x-layouts::app :title="'Edit Match'">
    <div class="max-w-2xl mx-auto space-y-6">
        <a href="{{ route('admin.matches.index') }}" class="text-sm text-green-600 hover:text-green-700">← Back to Matches</a>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit: {{ $match->homeClub->name }} vs {{ $match->awayClub->name }}</h1>

        <form action="{{ route('admin.matches.update', $match) }}" method="POST" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Home Club *</label>
                    <select name="home_club_id" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        @foreach($clubs as $club)
                        <option value="{{ $club->id }}" {{ old('home_club_id', $match->home_club_id) == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Away Club *</label>
                    <select name="away_club_id" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        @foreach($clubs as $club)
                        <option value="{{ $club->id }}" {{ old('away_club_id', $match->away_club_id) == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Competition</label>
                <select name="competition_id" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                    <option value="">No competition</option>
                    @foreach($competitions as $comp)
                    <option value="{{ $comp->id }}" {{ old('competition_id', $match->competition_id) == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Kick Off *</label>
                    <input type="datetime-local" name="kick_off" required value="{{ old('kick_off', $match->kick_off->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        @foreach(['scheduled', 'live', 'half_time', 'finished', 'postponed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', $match->status) === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $match->venue) }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Matchday / Round</label>
                <input type="text" name="matchday" value="{{ old('matchday', $match->matchday) }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Home Score</label>
                    <input type="number" name="home_score" value="{{ old('home_score', $match->home_score) }}" min="0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Away Score</label>
                    <input type="number" name="away_score" value="{{ old('away_score', $match->away_score) }}" min="0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm text-white hover:bg-green-700">Update Match</button>
            </div>
        </form>
    </div>
</x-layouts::app>
