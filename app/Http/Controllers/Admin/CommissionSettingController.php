<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use App\Http\Controllers\Controller;

class CommissionSettingController extends Controller
{
    public function index()
    {
        $charges = CommissionSetting::first();

        return Inertia::render('Admin/commission-setting/CreateEdit', [
            'settings' => $charges,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_charge' => 'required|integer',
            'service_charge' => 'required|integer',
        ]);

        CommissionSetting::updateOrCreate([
            'id' => $request->id
        ], [
            'booking_charge' => $request->booking_charge,
            'service_charge' => $request->service_charge,
        ]);
        
        return to_route('admin.commission-setting.index')->with('success', 'Settings added successfully');
    }
}
