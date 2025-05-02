<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class FaqManagementController extends Controller
{   
    public function index()
    {
        try {
            return Inertia::render('Admin/faq/List');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function getFaqs(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filter = isset($request->filters) ? $request->filters : [];
            $faq = Faq::filter($filter)
            ->ordering($sortBy)
            ->orderBy('id','desc')
            ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1)
            ->withQueryString()
            ->through(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                ];
            });
            // dd($request->per_page);
            return response()->json($faq);         
        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/faq/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store( Request $request )
    {
        $request->validate([
            'question'      => 'required|string|max:255',
            'answer'        => 'required|string',
        ]);

        try {

            $faq = new Faq();
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->save();

            return("FAQ has been added succesfully.");

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function edit(string $id)
    {   
        $faq = Faq::find($id);
        try {
            return Inertia::render('Admin/faq/CreateEdit', compact('faq'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update( Request $request, string $id )
    {
        $request->validate([
            'question'      => 'required|string|max:255',
            'answer'        => 'required|string',
        ]);

        try {

            $faq = Faq::find($id);
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->update();

            return("FAQ has been updated succesfully.");

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function remove(string $id)
    {   
        $faq = Faq::find($id);
        // dd($faq);
        if (is_null($faq)) {
            throw new NotFoundResourceException("The FAQ with ID $id does not exist.");
        }
        $faq->delete();
    }
}
