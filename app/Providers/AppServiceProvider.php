<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
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
        ResetPassword::createUrlUsing(function (User $user, string $token): string {
            if (app()->environment('local') && in_array(config('mail.default'), ['log', 'array'], true)) {
                cache()->put(
                    self::devTokenCacheKey($user->email),
                    $token,
                    now()->addMinutes((int) config('auth.passwords.users.expire', 60))
                );
            }

            return route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        });
    }

    /**
     * Build the cache key used to expose local reset links.
     */
    private static function devTokenCacheKey(string $email): string
    {
        return 'dev_password_reset_token_'.sha1(strtolower(trim($email)));
    }
}
