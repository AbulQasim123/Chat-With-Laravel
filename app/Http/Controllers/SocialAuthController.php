<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class SocialAuthController extends Controller
{
    protected array $allowedProviders = ['google', 'facebook'];

    public function redirect(string $provider)
{
    $this->ensureProviderIsAllowed($provider);

    if ($provider === 'facebook') {

        return Socialite::driver('facebook')
            ->scopes(['public_profile', 'email'])
            ->redirect();
    }

    return Socialite::driver($provider)->redirect();
}


    public function callback(string $provider)
    {
        $this->ensureProviderIsAllowed($provider);


        try {

            $client = new Client([
                'verify' => 'C:\\wamp64\\bin\\php\\php8.2.26\\extras\\ssl\\cacert.pem',
            ]);

            $socialUser = Socialite::driver($provider)
                ->setHttpClient($client)
                ->user();


            $email = $socialUser->getEmail();

            if (!$email) {

                Log::warning('Social login email missing.', [
                    'provider' => $provider,
                    'social_id' => $socialUser->getId(),
                    'user' => $socialUser,
                ]);

                return redirect()
                    ->route('login')
                    ->with('error', 'Email permission is required.');
            }

            $user = User::firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'name' => $socialUser->getName() ?? 'User',
                    'username' => $this->generateUniqueUsername(
                        $socialUser->getName() ?? 'user'
                    ),
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'photo' => $socialUser->getAvatar(),
            ]);

            Auth::login($user, true);

            request()->session()->regenerate();

            return redirect()->route('chat.list');

        } catch (\Throwable $e) {

            Log::error('Social Login Failed', [

                'provider' => $provider,

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'trace' => $e->getTraceAsString(),

                'request' => request()->all(),

                'url' => request()->fullUrl(),

                'ip' => request()->ip(),

                'user_agent' => request()->userAgent(),

            ]);

            return redirect()
                ->route('login')
                ->with('error', 'Unable to login using ' . ucfirst($provider));
        }
    }

    protected function ensureProviderIsAllowed(string $provider): void
    {
        abort_unless(in_array($provider, $this->allowedProviders), Response::HTTP_NOT_FOUND);
    }

    protected function generateUniqueUsername(string $name): string
    {
        $base = Str::slug($name) ?: 'user';
        $username = $base;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $i;
            $i++;
        }

        return $username;
    }
}
