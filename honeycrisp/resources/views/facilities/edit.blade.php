@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
<div class="container py-2">
    <div class="row my-3">
        <div class="col-md-12">
            <h1>Manage Facility: {{ $facility->name }}</h1>
        </div>
        <!-- Display any form errors here -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-2 subnav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#facility-meta" 
                    class="nav-link">Facility Information</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('facilities.products', $facility->id) }}"
                    
                     class="nav-link"> Products</a>
                </li>


                @if($facility->orders->where('status', 'invoice')->count() > 0)
                <li class="nav-item">
                    {{-- pending invoice exports with count of orders marked invoice --}}
                    <a class="nav-link" href="#invoices">Export Invoices ({{ $facility->orders->where('status', 'invoice')->count() }})</a>
                </li>
                @endif



            </ul>
        </div>
        <div class="col-md-6">
            <div id="facility-meta" class="facility-information">
                <h3 class="mb-3">Facility Information</h3>

                <!-- Form for editing the facility -->
                <form action="{{ route('facilities.update', $facility->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Facility Name -->
                    <div class="form-group mb-2">
                        <label for="name">Facility Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-2">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $facility->description) }}</textarea>
                    </div>

                    

                    <!-- Abbreviation -->
                    <div class="form-group mb-2">
                        <label for="abbreviation">Abbreviation</label>
                        <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ old('abbreviation', $facility->abbreviation) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $facility->email) }}" required>
                    </div>

                    <!-- Address -->
                    <div class="form-group mb-2">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $facility->address) }}">
                    </div>

                    <!-- Recharge Account -->
                    <div class="form-group mb-2">
                        <label for="recharge_account">Recharge Account</label>
                        <input type="text" name="recharge_account" id="recharge_account" class="form-control" value="{{ old('recharge_account', $facility->recharge_account) }}" required>

                    </div>

                    <!-- Account Type -->
                    <div class="form-group mb-2">
                        <label for="account_type">Account Type</label>
                        <select name="account_type" id="account_type" class="form-select" required>
                            <option value="kfs" @if(old('account_type', $facility->account_type) == 'kfs') selected @endif>KFS</option>
                            <option value="uch" @if(old('account_type', $facility->account_type) == 'uch') selected @endif>Banner/UCH</option>
                            <option value="other" @if(old('account_type', $facility->account_type) == 'other') selected @endif>Other</option>
                        </select>
                    </div>



                    <div class="card my-4">
                        <div class="card-header">
                            <h3>Facility Staff</h3>
                        </div>
                        <div class="card-body">
                            {{-- accordion with three headers: senior staff, student employee, billing staff --}}
                            <div class="accordion" id="staff">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="senior-staff">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#senior-staff-collapse" aria-expanded="true" aria-controls="senior-staff-collapse">
                                            Senior Staff
                                        </button>
                                    </h2>
                                    <div id="senior-staff-collapse" class="accordion-collapse collapse show" aria-labelledby="senior-staff" data-bs-parent="#staff">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="senior_staff">Senior Staff</label>
                                                @php $senior_staff = $facility->seniorStaff->pluck('id')->toArray() ?? []; @endphp

                                              <livewire:user-search input-name="senior_staff" :initial-users="$senior_staff">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="student-employees">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#student-employees-collapse" aria-expanded="false" aria-controls="student-employees-collapse">
                                            Student Employees
                                        </button>
                                    </h2>
                                    <div id="student-employees-collapse" class="accordion-collapse collapse" aria-labelledby="student-employees" data-bs-parent="#staff">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="student_employees">Student Employees</label>
                                                @php
                                                $student_employees = $facility->studentStaff->pluck('id')->toArray() ?? [];
                                                @endphp
                                                <livewire:user-search input-name="student_staff" :initial-users="$student_employees">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="billing-staff">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing-staff-collapse" aria-expanded="false" aria-controls="billing-staff-collapse">
                                            Billing Staff
                                        </button>
                                    </h2>
                                    <div id="billing-staff-collapse" class="accordion-collapse collapse" aria-labelledby="billing-staff" data-bs-parent="#staff">
                                        <div class="accordion-body">
                                            <div class="form-group">
                                                <label for="billing_staff">Billing Staff</label>
                                                @php 
                                                $billing_staff = $facility->billingStaff->pluck('id')->toArray() ?? [];
                                                @endphp
                                                <livewire:user-search input-name="billing_staff" :initial-users="$billing_staff">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Facility</button>
                    </div>
                </form>
            </div>




            @if($facility->orders->where('status', 'invoice')->count() > 0)
            <div id="invoices">
                <h3>Pending Internal Invoices ({{ $facility->orders->where('status', 'invoice')->where('price_group', 'internal')->count() }})</h3>
                <div class="alert alert-info my-3">
                    {{-- add the ability to switch between UCH and KFS before exporting --}}
                    <form action="{{ route('facilities.exportInvoices', $facility->abbreviation) }}" method="GET">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="account_type">Account Type</label>
                            <select name="account_type" id="account_type" class="form-select" required>
                                <option value="kfs">KFS</option>
                                <option value="uch">UCH</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Export Invoices</button>

                    </form>

                    {{-- List the orders marked as invoice --}}
                    <h5>Orders Marked as Invoice</h5>
                    <ul class="list-group">
                        @foreach($facility->orders->where('status', 'invoice')->where('price_group','internal') as $order)
                        <li class="list-group-item">
                            <span class="badge bg-primary">{{ strtoupper($order->paymentAccount->account_type) }}</span>
                            <strong>Order ID:</strong> {{ $order->id }}<br>
                            <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                            <strong>Total:</strong> $@dollars($order->total)<br>
                            <strong>Customer:</strong> {{ $order->customer->name }}<br>
                            <strong>Title:</strong> {{ $order->title }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif


            @can('admin')
            <div id="danger-zone" class="my-4">
                <h3 class="text-danger">Danger Zone</h3>
                <div class="alert alert-danger">
                    <p class="mb-3"> Deleting a facility is permanent and cannot be undone. This will also delete all associated products, categories, and orders. Are you sure you want to delete this facility?</p>
                    <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this facility?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Facility</button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>

@endsection