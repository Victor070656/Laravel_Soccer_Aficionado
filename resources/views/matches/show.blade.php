<x-layouts::app :title="$match->home_team['name'] . ' vs ' . $match->away_team['name']">
    <livewire:matches.details :id="$match->id" />
</x-layouts::app>
