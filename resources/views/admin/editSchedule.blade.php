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
                    <form action="{{ route('updateSchedule', $schedule->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">   
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nickname</label>
                                    <select class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $schedule->user->id === $user->id ? 'selected' : '' }}>
                                                {{ $user->nickname }}
                                            </option>  
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="date" name="date" autocomplete="off" value="{{ $schedule->date }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Duty</label>
                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off">
                                        @foreach ($duties as $duty)
                                        <option value="{{ $duty->id }}" {{ $schedule->duty->id === $duty->id ? 'selected' : ''}}>
                                            {{ $duty->duty_name }}
                                        </option>  
                                    @endforeach
                                    </select>
                                    @error('duty_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id" autocomplete="off">
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ $schedule->shift->id === $shift->id ? 'selected' : ''}}>
                                                {{ $shift->formatted_shift_time }}
                                            </option>  
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" value="{{ $schedule->remarks }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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

{{-- <script>
    $(document).ready(function() {
        // Handle "Edit" button click
        $('.edit-schedule').on('click', function() {
            var scheduleId = $(this).data('schedule-id');
            // Use the scheduleId to make an Ajax request to retrieve schedule details
            console.log(scheduleId);
            // Example: Ajax request
            $.ajax({
                url: '/admin/getScheduleDetails/' + scheduleId, // Adjust the URL
                type: 'GET',
                success: function(schedule) {
                    // Populate the form fields with schedule details
                    $('#employee_id').val(schedule.employee_id);
                    $('#date_start').val(schedule.date_start);
                    $('#duty_id').val(schedule.duty_id);
                    $('#shift_id').val(schedule.shift_id);
                    $('#date_end').val(schedule.date_end);
                    $('#remarks').val(schedule.remarks);

                    // Display the "Edit Schedule" modal
                    $('#editScheduleModal').modal('show');
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });
    });
</script> --}}
