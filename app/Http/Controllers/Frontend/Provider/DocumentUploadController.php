<?php

namespace App\Http\Controllers\Frontend\Provider;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ProviderDocument;
use App\Http\Controllers\Controller;

class DocumentUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doc' => [
                'nullable',
                'image',
                Rule::requiredIf(is_null(request()->item_id)),
            ],
            'service_id' => [
                'required',
                'numeric',
                'exists:services,id',
                Rule::unique('provider_documents')->ignore(request()->item_id)
            ]
        ]);

        $filePath = null;
        $document = null;

        if (!is_null($request->file('doc')) && is_null($request->item_id)) {
            $filePath = \Storage::putFile('onboard/provider/'.auth()->id().'/', $request->file('doc'), 'public');
        }

        if (!is_null($request->item_id)) {
            $document = ProviderDocument::find($request->item_id);
            $filePath = $document->file_path;
        }

        if (!is_null($request->file('doc')) && !is_null($request->item_id)) {
            unlink(storage_path('app/public/' . $document->file_path));
            $filePath = \Storage::putFile('onboard/provider/'.auth()->id().'/', $request->file('doc'), 'public');
        }

        ProviderDocument::updateOrCreate([
            'id' => $request->item_id,
            'provider_id' => auth()->id(),
        ], [
            'service_id' => $request->service_id,
            'file_path' => $filePath
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $document = ProviderDocument::find($id);
        unlink(storage_path('app/public/' . $document->file_path));
        $document->delete();
    }
}
