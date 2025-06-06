<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocUploadController extends Controller
{
    public function index(Request $request)
    {
        $docs = null;
        
        $services = session('services');

        $documents = \DB::table('provider_documents')
            ->where('provider_id', session('user_onboard')['id'])
            ->get();

        if ($documents->count()) {
            foreach ($documents as $key => $value) {
                $docs['image'][$value->service_id] = asset('/storage/'.$value->file_path);
            }
        }

        return Inertia::render('Frontend/onboarding/provider/DocUpload', [
            'services' => $services,
            'current_url' => $request->url(),
            'documents' => $docs,
        ]);
    }

    public function store(Request $request)
    {
        $servicesInSession = $request->session()->get('services');

        $rules = [
            'image' => ['required', 'array', 'min:1'],
        ];

        $messages = [];

        foreach ($servicesInSession as $key => $service) {
            $serviceId = $service['id'];
            $serviceName = $service['name'];

            $rules["image.{$serviceId}"] = ['required', 'image'];
            
            $messages["image.{$serviceId}.required"] = "Document for ${serviceName} is required";
            $messages["image.{$serviceId}.image"] = "Document format for ${serviceName} must be in jpg or png";
        }

        $validatedData = $request->validate($rules, $messages);

        $user = User::select('id','name')->find(session('user_onboard')['id']);

        $documents = \DB::table('provider_documents')
            ->where('provider_id', session('user_onboard')['id'])
            ->get();

        if ($documents->count()) {
            foreach ($documents as $key => $value) {
                unlink(storage_path('/app/public/'.$value->file_path));
            }

            $user->providerDocuments()->delete();
        }

        foreach ($validatedData['image'] as $key => $value) {
            $file = $validatedData['image'][$key];
            $filePath = \Storage::putFile('onboard/provider/'.$user->id, $file, 'public');
            
            $user->providerDocuments()->create([
                'service_id' => $key,
                'file_path' => $filePath,
            ]);
        }

        return to_route('frontend.onboard.provider.availability.index');
    }
}
