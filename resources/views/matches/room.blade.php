<x-layouts::app :title="'Match Room - ' . ($match->home_team['name'] ?? '') . ' vs ' . ($match->away_team['name'] ?? '')">
    <livewire:matches.room :id="$match->id" />
</x-layouts::app>
