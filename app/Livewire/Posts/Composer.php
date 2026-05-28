<?php

namespace App\Livewire\Posts;

use App\Actions\Posts\CreatePost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Composer extends Component
{
    use WithFileUploads;

    public string $body = '';

    public string $type = 'banter';

    public array $media = [];

    public ?int $community_id = null;

    public string $mode = 'feed';

    public bool $redirectAfterSubmit = false;

    public string $submitLabel = 'Post';

    public string $buttonIcon = '📰';

    public array $types = [];

    public function mount(string $mode = 'feed', bool $redirectAfterSubmit = false, ?int $communityId = null): void
    {
        $this->mode = $mode;
        $this->redirectAfterSubmit = $redirectAfterSubmit;
        $this->community_id = $communityId;
        $this->types = CreatePost::types();
        $this->buttonIcon = $this->types[$this->type]['icon'] ?? '📰';
    }

    public function updatedType(): void
    {
        $this->buttonIcon = $this->types[$this->type]['icon'] ?? '📰';
    }

    public function removeMedia(int $index): void
    {
        if (!array_key_exists($index, $this->media)) {
            return;
        }

        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function submit(CreatePost $creator)
    {
        $this->validate(CreatePost::rules());

        $user = Auth::user();

        if (!$user) {
            return;
        }

        $post = $creator->execute($user, [
            'body' => $this->body,
            'type' => $this->type,
            'media' => $this->media,
            'community_id' => $this->community_id,
        ]);

        $this->reset(['body', 'media']);
        $this->resetValidation();
        $this->buttonIcon = $this->types[$this->type]['icon'] ?? '📰';

        if ($this->redirectAfterSubmit) {
            return $this->redirectRoute('posts.show', $post);
        }

        $this->dispatch('post-created', postId: $post->id);
    }

    public function render()
    {
        return view('livewire.posts.composer', [
            'maxBodyLength' => CreatePost::MAX_BODY_LENGTH,
            'wrapperClass' => $this->wrapperClass(),
            'descriptionClass' => $this->descriptionClass(),
            'controlsClass' => $this->controlsClass(),
            'previewClass' => $this->previewClass(),
        ]);
    }

    public function placeholder(): string
    {
        return $this->types[$this->type]['placeholder'] ?? 'Share your football thoughts... ⚽';
    }

    public function submitButtonLabel(): string
    {
        return $this->submitLabel . ' ' . $this->buttonIcon;
    }

    private function wrapperClass(): string
    {
        return $this->mode === 'dashboard'
            ? 'card card-post rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 p-5 shadow-sm hover:shadow-card transition-all duration-300 glass-premium'
            : 'glass-card rounded-xl p-5';
    }

    private function descriptionClass(): string
    {
        return $this->mode === 'dashboard'
            ? 'w-full rounded-xl p-4 border border-primary/10 bg-surface/60 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm resize-none placeholder:text-on-surface-variant/60 transition-all'
            : 'w-full rounded-lg bg-surface-container-high border border-outline-variant/40 text-on-surface placeholder-on-surface-variant/50 p-4 text-body-md focus:border-primary-container focus:ring-1 focus:ring-primary-container/20 resize-none';
    }

    private function controlsClass(): string
    {
        return 'mt-3 flex items-center justify-between gap-3 flex-wrap';
    }

    private function previewClass(): string
    {
        return 'mt-4 grid gap-3 sm:grid-cols-2';
    }
}
