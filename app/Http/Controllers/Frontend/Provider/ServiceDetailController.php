<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\ProviderServiceDetail;

class ServiceDetailController extends Controller
{
    public function show(Request $request, $id)
    {
        $service = ProviderServiceDetail::with('service:id,name,slug')->find($id);

        return Inertia::render('Frontend/ServiceSingle', [
            'service' => $service
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => [
                'required',
                'integer',
                Rule::unique('provider_service_details')->where(fn ($query) => $query->where('provider_id', auth()->id()))->ignore(request()->item_id)
            ],
            'description' => ['required'],
            'location' => ['required'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image'],
            'item_id' => ['nullable', 'numeric']
        ]);

        $filePath = null;

        if (!is_null($request->item_id)) {
            $filePath = $request->user()->providerServiceDetails->where('id', $request->item_id)->first()->image_path;
        }

        if (!is_null($request->file('image'))) {
            $file = $request->file('image');
            
            if (!is_null($filePath)) {
                unlink(storage_path('app/public/'.$filePath));
            }

            $filePath = \Storage::putFile('profile/service', $file, 'public');
        }

        ProviderServiceDetail::updateOrCreate([
            'id' => $request->item_id,
            'provider_id' => auth()->id()
        ], [
            'description' => $request->description,
            'location' => $request->location,
            'price' => $request->price * 100,
            'image_path' => $filePath,
            'service_id' => $request->service_id,
        ]);
    }

    public function destroy($id)
    {
        $serviceDetail = ProviderServiceDetail::find($id);

        if (!is_null($serviceDetail->image_path)) {
            unlink(storage_path('app/public/'.$serviceDetail->image_path));
        }

        $serviceDetail->delete();

        return redirect()->route('frontend.provider-profile.index');
    }
}
