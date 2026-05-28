<?php

namespace App\Providers;

use App\Services\FootballApiService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use App\Livewire\Posts\Composer as PostComposer;
use App\Livewire\Reactions\Bar as ReactionBar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Livewire::component('posts.composer', PostComposer::class);
        Livewire::component('reactions.bar', ReactionBar::class);

        View::composer('components.bottom-nav', function ($view) {
            $api = app(FootballApiService::class);
            $liveMatches = $api->getLiveFixtures();
            $view->with('liveMatchCount', count($liveMatches));
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
