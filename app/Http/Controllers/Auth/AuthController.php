<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccessRequestEmail;
use App\Models\AccessRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Google OAuth callback. Creates new user if they don't exist
     */
    public function googleCallback()
    {
        $google_user = \Socialite::driver('google')->user();

        try {
            $user = User::where('email', $google_user->email)->firstOrFail();
            $user->update([
                'avatar_url' => $google_user->avatar
            ]);

            Auth::login($user, true);

            return redirect()->route('bots.view');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('welcome.view')->withErrors([
                'message' => 'You do not have access. Please request access, and we will email you when approved'
            ]);
        }
    }

    /**
     * Logout authenticated user
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('welcome.view');
    }

    /**
     * Redirect to Google OAuth login
     */
    public function redirectToGoogle()
    {
        return \Socialite::driver('google')->redirect();
    }

    /**
     * New user request access
     */
    public function requestAccess(Request $request)
    {
        // Check if provided email is already registered
        if (User::where('email', $request['email'])->exists()) {
            return redirect()->route('welcome.view')->withErrors([
                'message' => 'That email is already registered with ' . env('APP_NAME')
            ]);
        }

        AccessRequest::create([
            'name' => $request['name'],
            'email' => $request['email']
        ]);

        return redirect()->route('welcome.view')->with([
            'success' => 'We have received your request, we will email you whether you were approved'
        ]);
    }

    /**
     * Approve or deny access request
     */
    public function accessRequestReview(AccessRequest $access_request, Request $request)
    {
        if ($request->has('approve')) {
            $user = User::create([
                'name' => $access_request->name,
                'email' => $access_request->email,
                'notify_by' => 'email'
            ]);

            Mail::to($access_request->email)->send(new AccessRequestEmail('approved', $user->name));
        } else {
            Mail::to($access_request->email)->send(new AccessRequestEmail('denied', $access_request->name));
        }

        $access_request->delete();

        return redirect()->route('access-requests.view')->with([
            'success' => 'You have successfully ' . ($request->has('approve') ? 'approved' : 'denied') . " access to {$access_request->name}"
        ]);
    }
}
