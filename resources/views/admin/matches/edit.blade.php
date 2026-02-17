<x-layouts::app :title="'Edit Match'">
    <div class="max-w-2xl mx-auto space-y-6">
        <a href="{{ route('admin.matches.index') }}" class="inline-flex items-center gap-1 text-sm text-green-500 hover:text-green-400 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Matches
        </a>
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <h1 class="text-2xl font-bold text-white relative">✏️ {{ $match->homeClub->name }} vs {{ $match->awayClub->name }}</h1>
        </div>

        <form action="{{ route('admin.matches.update', $match) }}" method="POST" class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 space-y-4 shadow-xl shadow-black/5">
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
                <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-500/25 transition-all duration-300">Update Match</button>
            </div>
        </form>
    </div>
</x-layouts::app>
