<?php

namespace App\Livewire\Reactions;

use App\Services\ReactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Bar extends Component
{
    public string $targetType;

    public string|int $targetId;

    public array $buttons = [];

    public int $totalCount = 0;

    public ?string $currentReaction = null;

    public function mount(string $targetType, string|int $targetId): void
    {
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->refreshSummary();
    }

    public function toggleReaction(string $emoji, ReactionService $service): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $summary = $service->toggle($user, $this->targetType, $this->targetId, $emoji);

        $this->buttons = $this->attachActions($summary['buttons']);
        $this->currentReaction = $summary['currentReaction'];
        $this->totalCount = $summary['totalCount'];

        $this->dispatch('reaction-updated');
    }

    public function refreshSummary(ReactionService $service = null): void
    {
        $user = Auth::user();
        $summary = ($service ?? app(ReactionService::class))->summary($this->targetType, $this->targetId, $user);

        $this->buttons = $this->attachActions($summary['buttons']);
        $this->currentReaction = $summary['currentReaction'];
        $this->totalCount = $summary['totalCount'];
    }

    public function render()
    {
        return view('livewire.reactions.bar');
    }

    public function getListeners(): array
    {
        return [
            'reaction-updated' => 'refreshSummary',
        ];
    }

    private function attachActions(array $buttons): array
    {
        return array_map(function (array $button) {
            $button['action'] = 'toggleReaction(' . json_encode($button['emoji'], JSON_UNESCAPED_UNICODE) . ')';

            return $button;
        }, $buttons);
    }

}
