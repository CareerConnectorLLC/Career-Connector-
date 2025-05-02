<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\Activitylog\Facades\Activity;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class AdminManagementController extends Controller
{
    public function dashboard()
    {
        try {
            $stats = $this->getStats();
            $login_activity = ModelsActivity::where('log_name', 'auth-log')->latest()->take(3)->get();
            return Inertia::render('Admin/dashboard/Dashboard', compact('stats', 'login_activity'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getStats()
    {
        $active_clients = User::role('USER')->where('active', '1')->count();
        $inactive_clients = User::role('USER')->where('active', '0')->count();
        $active_service_provider = User::role('SERVICE-PROVIDER')->where('active', '1')->count();
        $inactive_service_provider = User::role('SERVICE-PROVIDER')->where('active', '0')->count();
        $data = [
            'active_clients' => $active_clients,
            'inactive_clients'=> $inactive_clients,
            'active_service_provider'=> $active_service_provider,
            'inactive_service_provider'=> $inactive_service_provider,
        ];

        return $data;
    }


    function adminProfile()
    {

        $user = User::find(Auth::user()->id);
        if(request()->isMethod('post')){
            $credentials = request()->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' =>  'required|email:rfc,dns|unique:users,email,'.$user->id,
                // 'profile_photo' => 'required',
            ]);
            try {
                $user->first_name = request()->first_name;
                $user->last_name = request()->last_name;
                $user->email = request()->email;

                if(request()->file('profile_photo')){
                    File::delete(storage_path('app/'.$user->profile_photo_path));
                    $user->profile_photo_path = request()->file('profile_photo')->store('profile_photo');
                    }
                $user->save();

                session()->flash('success', 'Profile successfully updated');
                return redirect()->route('admin.admin-profile');

            } catch (\Exception $e) {
                        Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                        return back()->with('error', 'Server error');
                    }

            }
        return Inertia::render('Admin/profile/AdminProfile',compact('user'));

    }


    public function adminChangePassword()
    {
        $credentials = request()->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
          ],
          [
            'confirm_password.same'=> 'New password & confirm password must be same'
          ]);


          $user = auth()->user();
           if (!Hash::check(request()->old_password, $user->password)) {
             return to_route('admin.admin-profile')->with('error', "The old Password doesn't match");
            }
              $user->password = request()->new_password;
              $user->save();
              Auth::logout();

        session()->flash('success', 'Password changed successfully');
        return to_route('admin.login');
    }
}
