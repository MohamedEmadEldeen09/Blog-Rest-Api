<?php

namespace App\Providers;

use App\Http\Resources\Channel\ChannelResource;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //for polymorphic purposes
        Relation::enforceMorphMap([
            'user' => "App\Models\User",
            'blog' => "App\Models\Blog",
            'admin' => "App\Models\Admin",
        ]);

        //to prevent data wrapping
        UserResource::withoutWrapping();
        ChannelResource::withoutWrapping();
        ImageResource::withoutWrapping();
    }
}
