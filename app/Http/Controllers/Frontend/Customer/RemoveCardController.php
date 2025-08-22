<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RemoveCardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        app('stripe')->paymentMethods->detach($id);
        $request->session()->flash('success', 'Card removed successfully!');
    }
}
