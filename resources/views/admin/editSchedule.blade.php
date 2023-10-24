@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Schedule</h4>

                    <!-- Form -->
                    <form action="{{ route('updateSchedule', ['id' => $scheduleId]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <select class="theme-input-style" id="employee_id" name="employee_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id === $user->employee_id ? 'selected' : '' }}>
                                                {{ $user->full_name }}
                                            </option>                               
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-lg-6">                               
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id">
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ $shift->id === $shift->shift_id ? 'selected' : '' }}>
                                                {{ $shift->formatted_shift_time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group --> 

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Duty</label>
                                    <select class="theme-input-style" id="duty_id" name="duty_id">
                                        @foreach ($duties as $duty)
                                            <option value="{{ $duty->id }}" {{ $duty->id === $duty->duty_id ? 'selected' : '' }}>
                                                {{ $duty->duty_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group --> 

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="date" name="date">
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