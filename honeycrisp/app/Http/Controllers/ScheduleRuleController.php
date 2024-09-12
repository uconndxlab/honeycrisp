<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ScheduleRuleController extends Controller
{
    public function createForm(Request $request)
    {
        $product = Product::find($request->product_id);
        return view('schedule-rules.create', compact('product'));
    }

    public function store(Request $request) {

    }
}
