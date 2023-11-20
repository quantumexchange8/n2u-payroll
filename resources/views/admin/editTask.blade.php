@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Task</h4>

                    <!-- Form -->
                    <form action="{{ route('updateTask', $task->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">   

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nickname</label>
                                    <select class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $task->user->id === $user->id ? 'selected' : '' }}>
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
                                    <label class="font-14 bold mb-2">Start Time</label>
                                    <input type="time" class="theme-input-style" id="start_time" name="start_time" autocomplete="off" placeholder="Start Time" value="{{$task->start_time}}">
                                    @error('start_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Period</label>
                                    <label for="inputName" class="bold mb-2">Task</label>
                                    <select class="theme-input-style" id="task_name" name="task_name" value="{{ old('task_name') }}">
                                        <option value="">Select Task</option>
                                        <option value="Opening" {{ $task->task_name === 'Opening' ? 'selected' : '' }}>Opening</option>
                                        <option value="Lunch" {{ $task->task_name === 'Lunch' ? 'selected' : '' }}>Lunch</option>
                                        <option value="Dinner" {{ $task->task_name === 'Dinner' ? 'selected' : '' }}>Dinner</option>
                                    </select>
                                    @error('task_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="date" name="date" autocomplete="off" value="{{ $task->date }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">End Time</label>
                                    <input type="time" class="theme-input-style" id="end_time" name="end_time" autocomplete="off" placeholder="End Time" value="{{$task->end_time}}">
                                    @error('end_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Duty</label>
                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off">
                                        @foreach ($duties as $duty)
                                            <option value="{{ $duty->id }}" {{ $task->duty->id === $duty->id ? 'selected' : '' }}>
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