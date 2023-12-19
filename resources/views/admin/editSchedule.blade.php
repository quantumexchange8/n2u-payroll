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
                    <form action="{{ route('updateSchedule', $schedule->id) }}" method="POST" class="repeater-default">
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

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id" autocomplete="off">
                                        <option value="">Select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ optional($schedule->shift)->id === $shift->id ? 'selected' : ''}}>
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
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ $schedule->remarks }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-12">
                                {{-- <pre>{{ dd($tasksAndDuties) }}</pre> --}}
                                <!-- Form Element -->
                                <div class="form-element py-30 mb-30">
                                    <!-- Repeater Html Start -->
                                    <div data-repeater-list="group-a">
                                        @forelse ($tasksAndDuties ?? [] as $task)
                                            <!-- Repeater Items -->
                                            <div data-repeater-item data-task-id="{{ $task->id }}">
                                                <!-- Repeater Content -->
                                                <div class="item-content align-items-center row">
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-3">
                                                        <label for="inputName" class="bold mb-2">Period</label>
                                                        <select class="theme-input-style" name="period_id" required>
                                                            <option value="">Select Period</option>
                                                            @foreach ($periods as $period)
                                                                <option value="{{ $period->id }}" {{ $task->period_name === $period->period_name ? 'selected' : ''}}>
                                                                    {{ $period->period_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('period_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-2">
                                                        <label for="inputMobile" class="bold mb-2">Start Time</label>
                                                        <input type="time" class="form-control" name="start_time" value="{{ $task->start_time }}" required>
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-2">
                                                        <label for="inputMobile" class="bold mb-2">End Time</label>
                                                        <input type="time" class="form-control" name="end_time" value="{{ $task->end_time }}" required>
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-3">
                                                        <label for="inputCompany" class="bold mb-2">Duty</label>
                                                        <select class="theme-input-style" name="duty_id" required>
                                                            <option value="">Select Duty</option>
                                                            @foreach($duties as $duty)
                                                                <option value="{{ $duty->id }}" {{ $task->duty_name === $duty->duty_name ? 'selected' : '' }}>
                                                                    {{ $duty->duty_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('duty_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- End Form Group -->

                                                    <!-- Repeater Remove Btn -->
                                                    <div class="repeater-remove-btn col-lg-1">
                                                        <button data-repeater-delete class="remove-btn" data-task-id="{{ $task->id }}">
                                                            <img src="../../assets/img/svg/remove.svg" alt="" class="svg">
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                            <!-- End Repeater Items -->
                                        @empty
                                            <!-- Display a default form when there are no tasks -->
                                            <div data-repeater-item>
                                                <!-- Repeater Content -->
                                                <div class="item-content align-items-center row">
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-3">
                                                        <label for="inputName" class="bold mb-2">Period</label>
                                                        <select class="theme-input-style" id="period_id" name="period_id" value="{{ old('period_id') }}" >
                                                            <option value="">Select Period</option>
                                                            @foreach ($periods as $period)
                                                                <option value="{{ $period->id }}">{{ $period->period_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('period_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-2">
                                                        <label for="inputMobile" class="bold mb-2">Start Time</label>
                                                        <input type="time" class="form-control" name="start_time" value="">
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-2">
                                                        <label for="inputMobile" class="bold mb-2">End Time</label>
                                                        <input type="time" class="form-control" name="end_time" value="">
                                                    </div>
                                                    <!-- End Form Group -->
                                                    <!-- Form Group -->
                                                    <div class="form-group col-lg-3">
                                                        <label for="inputCompany" class="bold mb-2">Duty</label>
                                                        <select class="theme-input-style" name="duty_id" >
                                                            <option value="">Select Duty</option>
                                                            @foreach($duties as $duty)
                                                                <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('duty_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- End Form Group -->

                                                    <!-- Repeater Remove Btn -->
                                                    <div class="repeater-remove-btn col-lg-1">
                                                        <button data-repeater-delete class="remove-btn">
                                                            <img src="../../assets/img/svg/remove.svg" alt="" class="svg">
                                                        </button>
                                                    </div>

                                                </div>
                                                <hr />
                                            </div>
                                        @endforelse
                                    </div>
                                    <!-- Repeater End -->
                                    <button data-repeater-create type="button" class="repeater-add-btn btn-circle">
                                        <img src="../../assets/img/svg/plus_white.svg" alt="" class="svg">
                                    </button>
                                </div>

                                <!-- End Form Element -->
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    var csrfToken = "{{ csrf_token() }}";
</script>
