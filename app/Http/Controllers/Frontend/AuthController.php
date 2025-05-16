<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        try {
            return Inertia::render('Frontend/Login');
        } catch (Exception $e) {
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

        if (Auth::attempt($credentials, $request->remember)) {
            if (is_null(Auth::user()->email_verified_at)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Inertia::clearHistory();
                session()->flash('error', 'Your email is not verified');
                return back();
            }
            
            $roleNames = Auth::user()->role_names->toArray();
            if (in_array('USER', $roleNames)) {
                return to_route('frontend.client.dashboard');
            } elseif (in_array('SERVICE-PROVIDER', $roleNames)) {
                return to_route('frontend.provider.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Incorrect Email or Password! Please try again.',
        ])->onlyInput('email');
    }

    public function registration(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:users'
            ],
            'phone' => ['required', 'numeric', 'unique:users'],
            'location' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== request()->password) {
                        $fail('Password does not match');
                    }
                }
            ],
            'accept_terms' => ['accepted']
        ], [
            'accept_terms.accepted' => 'Please accept the terms and conditions',
        ]);

        $name = explode(' ', $data['name']);

        $user = User::create([
            'first_name' => $name[0],
            'middle_name' => $name[1] ?? null,
            'last_name' => $name[2] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'location' => $data['location'],
            'password' => \Hash::make($data['password']),
        ]);

        $user->assignRole($request->input('user_role'));

        $code = random_int(1000, 9999);

        \DB::table('users_email_verify')->insert([
            'user_id' => $user->id,
            'code' => $code,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->put('user_id', $user->id);

        \Mail::to($user->email)->send(new \App\Mail\SendOTPMail($code));
        return to_route('frontend.mail.verify');
    }

    public function forgotPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'exists:users,email',
                'unique:password_reset_tokens,email'
            ]
        ], [
            'email.exists' => 'User not regsister',
            'email.unique' => 'Email address already exists',
        ]);

        $token = \Str::random(64);

        \DB::table('password_reset_tokens')->insert([
            'email' => $validatedData['email'], 
            'token' => $token, 
            'created_at' => now()
        ]);

        \Mail::send(
            'mail.forget_password', [
                'token' => $token, 'email' => $validatedData['email']
            ], function ($message) use($validatedData) {
                $message->to($validatedData['email']);
                $message->subject('Reset your password');
            }
        );
        session()->flash('success', "We've sent you an email to reset your password.");
        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function resendOtpCode(Request $request)
    {
        $user = User::find(session('user_id'));
        
        $code = random_int(1000, 9999);
        
        \DB::table('users_email_verify')->where('user_id', session('user_id'))->update(['code' => $code]);

        \Mail::to($user->email)->send(new \App\Mail\SendOTPMail($code));

        session()->flash('success', 'OTP has been send to your email address.');     
    }

    public function verifyMail(Request $request)
    {
        $validatedData = $request->validate([
            'code' => ['required', 'exists:users_email_verify,code']
        ]);

        $verifyData = \DB::table('users_email_verify')->where([
            'code' => $validatedData['code']
        ])->first();

        $user = User::find($verifyData->user_id);

        $user->update(['email_verified_at' => now()]);
    
        Auth::login($user);

        $roleNames = Auth::user()->role_names->toArray();

        \DB::table('users_email_verify')->where(['code' => $validatedData['code']])->delete();

        session()->forget('user_id');

        if (in_array('USER', $roleNames)) {
            return to_route('frontend.client.dashboard');
        } else {
            return to_route('frontend.provider.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Inertia::clearHistory();
        return to_route('frontend.login');
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, auth()->user()->password)) {
                        $fail("The ".\Str::replace('_', ' ', $attribute)." is not correct");
                    }
                }
            ],
            'password' => [
                'required', 'string', 'min:8', 'confirmed'
            ],
            'password_confirmation' => ['required'],
        ]);

        $request->user()->update([
            'password' => \Hash::make($validatedData['password'])
        ]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $validatedData = $request->validate([
            'token' => ['required', 'exists:password_reset_tokens'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'email' => ['required', 'email', 'exists:users', 'exists:password_reset_tokens,email'],
        ]);

        $updatePassword = \DB::table('password_reset_tokens')->where([
            'token' => $request->input('token')
        ])->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where(
            'email', $validatedData['email']
        )->update([
            'password' => \Hash::make($request->password)
        ]);

        \DB::table('password_reset_tokens')->where(['email'=> $validatedData['email']])->delete();
        return redirect()->route('frontend.login')->with('success', 'Your password has been changed!');
    }
}
