<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Emails\SendInquiryReplyEmail;
use App\Mail\InquiryReplyMail;
use App\Models\Inquiry;
use App\Models\InquiryReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class InquiryManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return  $this->getInquiries($request);
            } else {
                return Inertia::render('Admin/inquiry/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getInquiries(Request $request)
    { 
        $sortBy = [
            'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
            'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
        ];

        $filter = isset($request->filters) ? $request->filters : [];
        $inquiries = Inquiry::with('reply')
        ->filter($filter)
        ->ordering($sortBy)
        ->orderBy('id','desc')
        ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
        return response()->json($inquiries);
    }

    public function show(Request $request, string $id)
    {   
        try {
            $inquiry = Inquiry::with('reply')->find($id);
            return Inertia::render('Admin/inquiry/Show', compact('inquiry'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function reply(Request $request)
    {
        $validate = $request->validate([
            'reply'            => 'required',
            'Inquiry_id'       => 'required',
        ]);

        try {

            $inquiryReply = InquiryReply::create($validate);
            $inquiry = Inquiry::find($inquiryReply->Inquiry_id);
            $inquiry->status = 'Replied';
            $inquiry->update();

            SendInquiryReplyEmail::dispatch(
                $inquiry->email,
                $inquiry->name,
                $inquiryReply->reply
            );
            
            return response()->json($inquiryReply);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }


    }
}
