<?php

namespace App\Actions\Posts;

use App\Concerns\ExtractsMentions;
use App\Models\Post;
use App\Models\User;
use App\Services\GamificationService;

class CreatePost
{
    use ExtractsMentions;

    public const MAX_BODY_LENGTH = 280;

    public const MAX_MEDIA_COUNT = 4;

    public static function types(): array
    {
        return [
            'banter' => ['icon' => '💬', 'label' => 'Banter', 'placeholder' => 'Share your banter... 😂'],
            'match_reaction' => ['icon' => '⚽', 'label' => 'Match Reaction', 'placeholder' => 'React to a match... ⚽🔥'],
            'goal_reaction' => ['icon' => '🥅', 'label' => 'Goal Reaction', 'placeholder' => 'React to the goal... 🥅🔥'],
            'tactical_opinion' => ['icon' => '📋', 'label' => 'Tactical Opinion', 'placeholder' => 'Share your tactical take... 📋'],
            'player_comparison' => ['icon' => '⚔', 'label' => 'Player Comparison', 'placeholder' => 'Compare players... ⚔'],
            'meme' => ['icon' => '😂', 'label' => 'Meme', 'placeholder' => 'Drop a meme caption... 😂'],
            'breaking_news' => ['icon' => '📰', 'label' => 'Breaking News', 'placeholder' => 'Share the latest football news... 📰'],
            'matchday_discussion' => ['icon' => '🔥', 'label' => 'Matchday Discussion', 'placeholder' => 'Start the matchday debate... 🔥'],
        ];
    }

    public static function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:' . self::MAX_BODY_LENGTH],
            'type' => ['nullable', 'string', 'in:' . implode(',', array_keys(self::types()))],
            'community_id' => ['nullable', 'exists:communities,id'],
            'media' => ['nullable', 'array', 'max:' . self::MAX_MEDIA_COUNT],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm,mov', 'max:10240'],
        ];
    }

    public function execute(User $user, array $data): Post
    {
        $mediaPaths = [];

        foreach (($data['media'] ?? []) as $file) {
            $mediaPaths[] = $file->store('posts', 'public');
        }

        $post = $user->posts()->create([
            'body' => $data['body'],
            'community_id' => $data['community_id'] ?? null,
            'type' => $data['type'] ?? 'banter',
            'media' => !empty($mediaPaths) ? $mediaPaths : null,
            'is_approved' => true,
        ]);

        $this->extractAndSaveMentions($data['body'], $post);

        app(GamificationService::class)->awardPoints($user, 'post_created', $post);
        app(GamificationService::class)->recordActivity($user, 'post_created', $post);

        return $post;
    }
}
