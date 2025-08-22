<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MeetingPageController extends Controller
{
    public function show(Booking $booking, Request $request)
    {
        if (!$booking->meeting_url || $booking->status === 'Completed') {
            abort(403, 'This meeting is not available or has already been completed.');
        }
        
        $developerKey = config('services.digitalsamba.developer_key');
        $teamId = config('services.digitalsamba.team_id');
        $userName = '';
        $userRole = '';

        if (Auth::check()) {
            $user = Auth::user();
            $userName = $user->name;
            $userRole = $user->roles->first()->name;
        } else if ($request->has('token')) {
            if ($request->token === $booking->provider_join_token && now()->lessThan($booking->provider_join_token_expires_at)) {
                $userName = $booking->provider->name;
                $userRole = 'SERVICE-PROVIDER';
            } else {
                abort(403, 'Invalid or expired token.');
            }
        } else {
            abort(403, 'Unauthorized. Please login or use a valid token.');
        }

        $payload = [
            'td' => $teamId,
            'rd' => $booking->meeting_url,
            'u' => $userName,
            'exp' => time() + 3600, // Token expiration time (1 hour)
            'nbf' => time(), // Token "not before" time
            'role' => $userRole === 'USER' ? 'moderator' : 'speaker'
        ];

        $token = JWT::encode($payload, $developerKey, 'HS256');

        $meetingUrl = config('services.digitalsamba.base_url') . '/' . $booking->meeting_url;

        $bookingData = $booking->toArray();

        return Inertia::render('Frontend/Meeting', [
            'meetingUrl' => $meetingUrl,
            'token' => $token,
            'isLoggedIn' => Auth::check(),
            'role' => $userRole,
            'booking' => $bookingData
        ]);
    }
}