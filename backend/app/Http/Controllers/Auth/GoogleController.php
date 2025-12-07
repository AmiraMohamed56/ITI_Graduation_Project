<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\GoogleLoginResource;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Redirect to Google for authentication
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle the Google callback
    public function handleGoogleCallback()
    {
        // Get user information from Google
        $googleUser = Socialite::driver('google')->user();

        // Check if the user already exists in the database
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // If the user doesn't exist, create a new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make('Password@1234'), // Generate a random password // Generate a random password
            ]);
        }

        Auth::login($user);

        // Generate JWT for the authenticated user
        $token = $user->createToken('Google Login Token')->plainTextToken;

        // Return token and user data as JSON response
        // return new GoogleLoginResource([
        //     'token' => $token,
        //     'user' => $user,
        // ]);

        return redirect()->away(
            "http://localhost:4200/auth/callback" .
            "?token=$token" .
            "&id=" . $user->id .
            "&name=" . urlencode($user->name) .
            "&email=" . urlencode($user->email) .
            "&google_id=" . urlencode($user->google_id)
        );


    }
}
