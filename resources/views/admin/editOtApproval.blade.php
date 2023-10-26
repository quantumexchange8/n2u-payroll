@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit OT Approval</h4>

                    <!-- Form -->
                    <form action="{{ route('updateOtApproval', $punchRecords->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" placeholder="Employee ID" readonly value="{{$punchRecords->employee_id}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Name</label>
                                    <input type="text" class="theme-input-style" id="name" name="name" autocomplete="off" placeholder="Name" readonly value="{{ $punchRecords->user->full_name }}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group for Date -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="text" class="theme-input-style" value="{{ $punchRecords->created_at->toDateString() }}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group for Time -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Time</label>
                                    <input type="text" class="theme-input-style" readonly value="{{ $punchRecords->created_at->toTimeString() }}">
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-lg-6">                               
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Status</label>
                                    <select class="theme-input-style" id="ot_approval" name="ot_approval">                                        
                                        <option value="Pending" {{ $punchRecords->ot_approval === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ $punchRecords->ot_approval === 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ $punchRecords->ot_approval === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select> 
                                </div>
                                <!-- End Form Group -->
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Update</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->

@endsection