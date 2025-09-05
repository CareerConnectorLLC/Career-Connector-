<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompletedJobsController extends Controller
{
    public function index(Request $request)
    {
        $completedTasks = $request->user()->providerBookings()
                                ->with('service:id,name', 'client:id,name')
                                ->where('status', 'Completed')
                                ->latest()
                                ->get();
        
        return Inertia::render('Frontend/provider/JobCompletion', [
            'completedTasks' => $completedTasks,
            'pageTitle' => env('APP_NAME') . ' | Provider Completed Jobs'
        ]);
    }
}
