<?php

namespace App\Http\Controllers\Admin;

use App\Events\BookingConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return  $this->getBookings($request);
            } else {
                return Inertia::render('Admin/booking/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getBookings(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filters = isset($request->filters) ? $request->filters : [];

            // $filters = [
            //     ["column" => "category", "value" => ["Electrician"]],
            //     ["column" => "active", "value" => [1]]
            // ];
            

            $bookings = Booking::with(['client', 'provider', 'service'])
            ->filter($filters)
            ->ordering($sortBy)
            ->orderBy('id','asc')
            ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
            // dd($bookings);
            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }
}
