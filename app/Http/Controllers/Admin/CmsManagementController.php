<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CmsManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filters = request()->all('title');
            $cms = Cms::filter(request()->only('title'))
            ->ordering(request()->only('fieldName','sortBy'))
            ->orderBy('id','desc')
            ->paginate($request->perPage ?? 10, ['*'], 'page', $request->page ?? 1)
            ->withQueryString()
            ->through(function ($cms) {
                return [
                    'id' => $cms->id,
                    'title' => $cms->title,
                    'text_content' => $cms->text_content,
                ];
            });

            return Inertia::render('Admin/cms/List', compact('cms','filters'));
        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/cms/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function store( Request $request )
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'text_content'  => 'required|string',
        ]);
        try {

            $cms = new Cms();
            $cms->title = $request->title;
            $cms->text_content = $request->text_content;
            $cms->save();

            return to_route('admin.cms.list')->with('success', 'CMS has been created successfully');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function edit(string $id)
    {
        try {
            $cms = Cms::find($id);
            return Inertia::render('Admin/cms/CreateEdit', compact('cms'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request, string $id)
    {
        $cms = Cms::find($id);
        $request->validate([
            'title'            => 'required|string|max:255',
            'text_content'     => 'required|string',
        ]);

        try {

            $cms->title = $request->title;
            $cms->text_content = $request->text_content;
            $cms->update();
            return to_route('admin.cms.list')->with('success', 'CMS has been updated successfully');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }


    public function remove(string $id)
    {
        $cms = Cms::find($id);
        if (is_null($cms)) {
            throw new NotFoundResourceException("The CMS with ID $id does not exist.");
        }
        $cms->delete();
    }



}
