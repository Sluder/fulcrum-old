<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    /**
     * USer profile view
     */
    public function view()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    /**
     * Update users basic information
     */
    public function update(Request $request)
    {
        Auth::user()->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'notify_by' => $request['notify-by'],
            'phone_number' => $request['phone-number']
        ]);

        return redirect()->route('profile.view')->with([
            "success" => "Successfully updated your profile information"
        ]);
    }

    /**
     * Update users Robinhood login information
     */
    public function updateRobinhoodCredentials(Request $request)
    {
        Auth::user()->update([
            'robinhood_username' => Crypt::encryptString($request['username']),
            'robinhood_password' => Crypt::encryptString($request['password']),
            'robinhood_mfa' => Crypt::encryptString($request['mfa'])
        ]);

        return redirect()->route('profile.view')->with([
            "success" => "Successfully updated your Robinhood credentials"
        ]);
    }
}
