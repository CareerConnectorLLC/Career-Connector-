<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Locale;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LocationManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return $this->getLocations($request);
            } else {
                return Inertia::render('Admin/location/List');
            }
            
        } catch (\Exception $e) {
            Log::error(" :: Exception :: " . $e->getMessage() . "\n" .  $e->getTraceAsString());
            return back()->with('error', 'Server Error');
        }
    }

    public function getLocations(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filter = isset($request->filters) ? $request->filters : [];

            $locations = Location::filter($filter)
            ->ordering($sortBy)
            ->orderBy('id','desc')
            ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
            return response()->json($locations);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create(){
        try {
            return Inertia::render('Admin/location/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
        ]);

        try {
            $location = new Location();
            $location->name = $request->name;

            if ($request->file('image')) {
                File::delete(storage_path('app/'.$location->image_url));
                $location->image_url = '/storage/'. request()->file('image')->store('image');
            }

            $location->save();
            session()->flash('success', 'Location has been created successfully');
            return response()->json(['redirectURL' => route('admin.location.list')]);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function edit(Request $request, string $id)
    {   
        try {
            $location = Location::find($id);
            return Inertia::render('Admin/location/CreateEdit', compact('location'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function remove(string $id)
    {
        try {
            $location = Location::find($id);
            if (is_null($location)) {
                throw new NotFoundResourceException("The Location with ID $id does not exist.");
            }
            $location->delete();
            session()->flash('success', 'Location has been deleted successfully.');
            return response()->json(['redirectUrl' => route('admin.location.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

}
