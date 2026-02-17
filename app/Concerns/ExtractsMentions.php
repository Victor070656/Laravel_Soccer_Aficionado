<?php

namespace App\Concerns;

use App\Models\Mention;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ExtractsMentions
{
    /**
     * Extract @mentions from text and create Mention records.
     *
     * @param  string  $text  The text to extract mentions from
     * @param  Model  $mentionable  The model being mentioned in (Post or Comment)
     * @return void
     */
    protected function extractAndSaveMentions(string $text, Model $mentionable): void
    {
        preg_match_all('/@(\w+)/', $text, $matches);

        if (empty($matches[1])) {
            return;
        }

        $usernames = array_unique($matches[1]);
        $users = User::whereIn('username', $usernames)->get();

        foreach ($users as $user) {
            $mentionable->mentions()->firstOrCreate([
                'user_id' => $user->id,
            ]);
        }
    }
}
