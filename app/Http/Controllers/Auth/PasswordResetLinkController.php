<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            $response = back()->with('status', __($status));

            if (
                $user !== null
                && app()->environment('local')
                && in_array(config('mail.default'), ['log', 'array'], true)
            ) {
                $token = cache()->pull(self::devTokenCacheKey($user->email));

                if (is_string($token) && $token !== '') {
                    $response = $response->with('dev_reset_url', route('password.reset', [
                        'token' => $token,
                        'email' => $user->email,
                    ]));
                }
            }

            return $response;
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    /**
     * Build the cache key used to expose local reset links.
     */
    private static function devTokenCacheKey(string $email): string
    {
        return 'dev_password_reset_token_'.sha1(strtolower(trim($email)));
    }
}
