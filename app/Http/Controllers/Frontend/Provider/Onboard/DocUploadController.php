<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

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
            ->where('provider_id', auth()->id())
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
        $validatedData = $request->validate([
            'image' => ['required', 'array', 'min:1'],
            'image.*' => ['required', 'image'],
        ]);

        $documents = \DB::table('provider_documents')
            ->where('provider_id', auth()->id())
            ->get();

        if ($documents->count()) {
            foreach ($documents as $key => $value) {
                unlink(storage_path('/app/public/'.$value->file_path));
            }

            $request->user()->providerDocuments()->delete();
        }

        foreach ($validatedData['image'] as $key => $value) {
            $file = $validatedData['image'][$key];
            $filePath = \Storage::putFile('onboard/provider/'.auth()->id(), $file, 'public');
            
            $request->user()->providerDocuments()->create([
                'service_id' => $key,
                'file_path' => $filePath,
            ]);
        }

        session()->forget('services');
        return to_route('frontend.provider.dashboard');
    }
}
