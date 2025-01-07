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

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer|exists:products,id',
        'day' => 'required|array|min:1',
        'day.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        'time_of_day_start' => 'required|string|date_format:H:i',
        'time_of_day_end' => 'required|string|date_format:H:i|after:time_of_day_start',
    ], [
        'product_id.required' => 'The product ID is required.',
        'product_id.integer' => 'The product ID must be an integer.',
        'product_id.exists' => 'The selected product does not exist.',
        'day.required' => 'You must select at least one day of the week.',
        'day.array' => 'The day field must be an array.',
        'day.*.string' => 'Each day must be a string.',
        'day.*.in' => 'Each day must be a valid day of the week.',
        'time_of_day_start.required' => 'The start time of day is required.',
        'time_of_day_start.date_format' => 'The start time must be in the format HH:mm.',
        'time_of_day_end.required' => 'The end time of day is required.',
        'time_of_day_end.date_format' => 'The end time must be in the format HH:mm.',
        'time_of_day_end.after' => 'The end time must be after the start time.',
    ]);

    $product = Product::findOrFail($request->product_id);

    // Create schedule rules for each selected day
    foreach ($request->day as $day) {

        

        $product->scheduleRules()->create([
            'day' => $day,
            'time_of_day_start' => $request->time_of_day_start,
            'time_of_day_end' => $request->time_of_day_end,
        ]);


    }

    return redirect()->route('products.show', $product->id)
        ->with('success', 'Schedule rules created successfully.');
}
}
