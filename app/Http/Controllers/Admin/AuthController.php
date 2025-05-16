<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendOTPMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        try {
            return Inertia::render('Auth/admin/Login');
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember_me)) {
            $roleNames = Auth::user()->role_names->toArray();
            if (in_array('SUPER-ADMIN', $roleNames)) {
                Inertia::clearHistory();
                activity('auth-log')
                ->causedBy(Auth::user())
                ->withProperties(['ip' => $request->ip(), 'browser' => $request->header('User-Agent')])
                ->log('Successful');
                return redirect()->route('admin.dashboard');
            } else {
                activity('auth-log')
                ->withProperties(['email' => $request->email, 'ip' => $request->ip(), 'browser' => $request->header('User-Agent')])
                ->log('Failed');
                Auth::logout();
                return back()->withErrors([
                    'email' => "Access Denied! You don't have permission to access this page",
                ])->onlyInput('email');
            }
        }
        activity('auth-log')
        ->withProperties(['email' => $request->email, 'ip' => $request->ip(), 'browser' => $request->header('User-Agent')])
        ->log('Failed');
        return back()->withErrors([
            'email' => 'Incorrect Email or Password! Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Inertia::clearHistory();
        return redirect()->route('admin.login');
    }

    public function forgotPasswordIndex(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email'
            ]);

            $token = random_int(100000, 999999);
            $get_user = User::where('email', $request->email)->first();

            if (!$get_user) {
                return back()->withErrors([
                    'email' => 'Please enter your registered email',
                ]);
            }
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            $data['code'] = $token;

            try {
                Mail::to($request->email)->send(new SendOTPMail($token));
            } catch (\Exception $e) {
                return back()->withErrors(['email' => 'Failed to send OTP. Please try again later.']);
            }

            session()->flash('success', 'OTP successfully send.');
            session()->put('forgot_password_email', $request->email);
            return redirect()->route('admin.forgot-password.otp-validation');
        }

        return Inertia::render('Auth/admin/ForgotPassword');
    }

    public function forgotPasswordStore(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'otp' => 'required'
            ]);

            $email = session()->get('forgot_password_email');
            $get_otp = DB::table('password_reset_tokens')->where('token', $request->otp)->where('email', $email)->first();

            if (!$get_otp ||  Carbon::parse($get_otp->created_at)->addMinutes(5) < now()) {
                session()->forget('forgot_password_email');
                DB::table('password_reset_tokens')->where('email', $email)->delete();
                return back()->withErrors([
                    'otp' => 'Please Enter Valid OTP',
                ]);
            }

            DB::table('password_reset_tokens')->where('email', $email)->delete();

            session()->flash('success', 'OTP successfully validate.');
            return to_route('admin.forgot-password.reset-password');
        }

        return inertia('Auth/admin/OtpVerification')->with('email', session()->get('forgot_password_email'));
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {

            $request->validate([
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            ]);
            try {
                $user = User::where('email', session()->get('forgot_password_email'))->first();
                if ($user) {
                    $user->password = $request->password;
                    $user->save();
                    session()->flash('success', 'Password changed successfully. Please login with new password.');
                } else {
                    Log::info('error here', session()->get('forgot_password_email'));
                    session()->flash('error', 'Something went wrong. Please try again. The email address is not valid.');
                }

                $request->session()->forget('forgot_password_email');
                return to_route('admin.login.index');
            } catch (\Exception $e) {
                Log::error($e);
                session()->flash('error', 'Something went wrong. Please try again.');
                return back();
            }
        }

        return inertia('Auth/admin/ResetPassword');
    }
}
