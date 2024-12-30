<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::all();
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('admin')) {
            return redirect()->route('facilities.index');
        }
        return view('facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $facility = new Facility();
        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->abbreviation = $request->abbreviation;
        $facility->email = $request->email;
        $facility->recharge_account = $request->recharge_account;
        $facility->address = $request->address;
        $facility->account_type = $request->account_type;

        $facility->save();

        return redirect()->route('facilities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        // admin gate
        if (Gate::denies('admin')) {
            return redirect()->route('facilities.index');
        }

        // get the facility users
        $facility->load('users');
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->abbreviation = $request->abbreviation;
        $facility->email = $request->email;
        $facility->recharge_account = $request->recharge_account;
        $facility->address = $request->address;
        $facility->account_type = $request->account_type;

        // attach the users in the senior_staff, student_staff, and billing_staff arrays

        $usersUpdated = false;

        // Handle senior_staff
        if (!empty($request->senior_staff)) {
            $seniorStaffWithRole = [];
            foreach ($request->senior_staff as $userId) {
                $seniorStaffWithRole[$userId] = ['role' => 'senior_staff'];
            }

            // Sync only the users with the 'senior_staff' role
            $facility->users()
                ->wherePivot('role', 'senior_staff') // Filter to only the 'senior_staff' role
                ->sync($seniorStaffWithRole); // Sync will remove any not in the provided array
            $usersUpdated = true;

        } else {
            // If no senior staff provided, remove all users with the 'senior_staff' role
            $facility->users()
                ->wherePivot('role', 'senior_staff')
                ->detach();
        }

        // handle student_staff
        if (!empty($request->student_staff)) {
            $studentStaffWithRole = [];
            foreach ($request->student_staff as $userId) {
                $studentStaffWithRole[$userId] = ['role' => 'student_staff'];
            }

            // Sync only the users with the 'student_staff' role
            $facility->users()
                ->wherePivot('role', 'student_staff') // Filter to only the 'student_staff' role
                ->sync($studentStaffWithRole); // Sync will remove any not in the provided array
            $usersUpdated = true;

        } else {
            // If no student staff provided, remove all users with the 'student_staff' role
            $facility->users()
                ->wherePivot('role', 'student_staff')
                ->detach();
        }

        // handle billing_staff
        if (!empty($request->billing_staff)) {
            $billingStaffWithRole = [];
            foreach ($request->billing_staff as $userId) {
                $billingStaffWithRole[$userId] = ['role' => 'billing_staff'];
            }

            // Sync only the users with the 'billing_staff' role
            $facility->users()
                ->wherePivot('role', 'billing_staff') // Filter to only the 'billing_staff' role
                ->sync($billingStaffWithRole); // Sync will remove any not in the provided array
            $usersUpdated = true;

        } else {
            // If no billing staff provided, remove all users with the 'billing_staff' role
            $facility->users()
                ->wherePivot('role', 'billing_staff')
                ->detach();
        }







        $facility->save();

        if ($facility->wasChanged() || $usersUpdated) {
            $state = 'success';
            $msg = $facility->name . ' has been updated';
        } else {
            $state = 'alert';
            $msg = $facility->name . ' was not updated (no changes)';
        }

        return redirect()->route('facilities.edit', $facility)->with($state, $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        $msg = $facility->name . ' has been deleted';
        $facility->delete();

        return redirect()->route('facilities.index')->with('alert', $msg);
    }

    public function exportInvoices($facility)
    {

        $facility = Facility::where('abbreviation', $facility)->first();

        $lines = "";
        $lines = $facility->generateFinancialHeader();
        $glCount = 0;
        $total = 0;
        $sequenceNumber = 0;

        //for each order, generate the financialLines
        foreach ($facility->orders->where('status', 'invoice')->where('price_group', 'internal') as $order) {

            foreach ($order->items as $item) {

                $lines .= $item->kfsDebitLine($sequenceNumber) . "\n";
                $lines .= $item->kfsCreditLine($sequenceNumber) . "\n";

                $sequenceNumber++;
            }

            $glCount += $order->items->count();
            $total += $order->total;

            $order->status = 'sent_to_kfs';

            $order->save();
        }

        $lines .= $facility->generateFinancialFooter($glCount, $total);

        $filename = $facility->abbreviation . '_financials.dat';
        $timestamp = now()->format('Ymd_His');
        $filename = $facility->abbreviation . '_financials_' . $timestamp . '.dat';

        // save the file to the server in the storage/app/exports   directory
        $path = storage_path('app/exports/' . $filename);
        file_put_contents($path, $lines);



        return response($lines)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
