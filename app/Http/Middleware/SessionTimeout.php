<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Officer;
use App\Models\EmailHandler;
use App\Models\Admin;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in (any channel)
        $isLoggedIn = false;
        $lastActivityKey = '';
        $logoutChannel = '';

        if ($request->session()->get('officer_logged_in')) {
            $isLoggedIn = true;
            $lastActivityKey = 'officer_last_activity';
            $logoutChannel = 'OD';
        } elseif ($request->session()->get('email_logged_in')) {
            $isLoggedIn = true;
            $lastActivityKey = 'email_last_activity';
            $logoutChannel = 'Email';
        } elseif ($request->session()->get('facebook_logged_in')) {
            $isLoggedIn = true;
            $lastActivityKey = 'facebook_last_activity';
            $logoutChannel = 'Facebook';
        } elseif ($request->session()->get('admin_logged_in')) {
            $isLoggedIn = true;
            $lastActivityKey = 'admin_last_activity';
            $logoutChannel = 'admin';
        }

        if ($isLoggedIn) {
            $lastActivity = $request->session()->get($lastActivityKey);
            $now = Carbon::now();

            // If no last activity recorded, set it to now
            if (!$lastActivity) {
                $request->session()->put($lastActivityKey, $now);
            } else {
                // Check if 1 hour has passed
                $lastActivity = Carbon::parse($lastActivity);
                if ($now->diffInMinutes($lastActivity) >= 60) {
                    // Auto logout due to inactivity
                    $this->performAutoLogout($request, $logoutChannel);
                    
                    // Clear session and redirect to welcome with timeout message
                    $request->session()->flush();
                    return redirect()->route('welcome')->with('error', 'Session expired due to inactivity. Please login again.');
                }
            }

            // Update last activity timestamp
            $request->session()->put($lastActivityKey, $now);
        }

        return $next($request);
    }

    /**
     * Perform auto logout by setting user as inactive in database
     */
    private function performAutoLogout(Request $request, string $channel)
    {
        switch ($channel) {
            case 'OD':
                $officerName = $request->session()->get('officer_name');
                if ($officerName) {
                    Officer::where('name', $officerName)->update(['active' => false]);
                }
                break;
            case 'Email':
                $emailUserName = $request->session()->get('email_user_name');
                if ($emailUserName) {
                    EmailHandler::where('name', $emailUserName)->update(['active' => false]);
                }
                break;
            case 'Facebook':
                // Facebook users don't have database records, just clear session
                break;
            case 'admin':
                $adminUsername = $request->session()->get('admin_username');
                if ($adminUsername) {
                    Admin::where('username', $adminUsername)->update(['active' => false]);
                }
                break;
        }
    }
}
