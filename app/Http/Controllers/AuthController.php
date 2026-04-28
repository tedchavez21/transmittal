<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\EmailHandler;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $channel = $request->input('channel'); // OD, Email, or Facebook
        return view('auth.login', compact('channel'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'channel' => 'required|string|in:OD,Email,Facebook',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $channel = $request->input('channel');
        $username = $request->input('username');
        $password = $request->input('password');

        switch ($channel) {
            case 'OD':
                return $this->loginOfficer($request, $username, $password);
            case 'Email':
                return $this->loginEmail($request, $username, $password);
            case 'Facebook':
                return $this->loginFacebook($request, $username, $password);
            default:
                return back()->with('error', 'Invalid channel');
        }
    }

    private function loginOfficer(Request $request, $username, $password)
    {
        $officer = Officer::where('username', $username)->first();

        if (!$officer || !Hash::check($password, $officer->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        // Set officer as active
        $officer->update(['active' => true]);

        // Store session
        $request->session()->put('officer_name', $officer->name);
        $request->session()->put('officer_id', $officer->id);
        $request->session()->put('officer_logged_in', true);

        return redirect()->route('officer-of-the-day')->with('success', 'Login successful');
    }

    private function loginEmail(Request $request, $username, $password)
    {
        // For email handlers, we'll use name as username for now
        $handler = EmailHandler::where('name', $username)->first();

        if (!$handler) {
            // Create new handler if not exists
            $handler = EmailHandler::create([
                'name' => $username,
                'approved' => true, // Auto-approve for now
                'active' => true,
            ]);
        }

        // Store session
        $request->session()->put('email_user_name', $handler->name);
        $request->session()->put('email_logged_in', true);

        return redirect()->route('email-handler')->with('success', 'Login successful');
    }

    private function loginFacebook(Request $request, $username, $password)
    {
        // For Facebook, we'll use a simple password check
        $correctPassword = env('FACEBOOK_HANDLER_PASSWORD', 'facebook2026');

        if ($password !== $correctPassword) {
            return back()->with('error', 'Invalid credentials');
        }

        // Store session
        $request->session()->put('facebook_logged_in', true);
        $request->session()->put('facebook_user', $username);

        return redirect()->route('facebook-handler')->with('success', 'Login successful');
    }

    public function logout(Request $request)
    {
        $channel = $request->input('channel') ?? 'unknown';

        switch ($channel) {
            case 'OD':
                $officerName = $request->session()->get('officer_name');
                if ($officerName) {
                    Officer::where('name', $officerName)->update(['active' => false]);
                }
                $request->session()->forget(['officer_name', 'officer_id', 'officer_logged_in']);
                break;
            case 'Email':
                $emailUserName = $request->session()->get('email_user_name');
                if ($emailUserName) {
                    EmailHandler::where('name', $emailUserName)->update(['active' => false]);
                }
                $request->session()->forget(['email_user_name', 'email_logged_in']);
                break;
            case 'Facebook':
                $request->session()->forget(['facebook_logged_in', 'facebook_user']);
                break;
        }

        return redirect()->route('welcome');
    }
}
