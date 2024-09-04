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
                            {{-- <div class="col-lg-6"> --}}
                                <!-- Form Group -->
                                <div class="form-group col-lg-6">
                                    <label class="font-14 bold mb-2">Outlet</label>
                                    <select class="theme-input-style" id="outlet_id" name="outlet_id" autocomplete="off" onchange="displayName()">
                                        @foreach ($outlets as $outlet)
                                        @php
                                            // $id = $users->outlet_id->id;
                                            // dd($id);
                                        @endphp
                                            <option value="{{ $outlet->id }}" {{ $schedule->user->outlet->id === $outlet->id ? 'selected' : '' }}> 
                                                {{ $outlet->outlet_location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('outlet_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group col-lg-6">
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
                                <div class="form-group col-lg-6">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="date" name="date" autocomplete="off" value="{{ $schedule->date }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            {{-- </div> --}}

                            {{-- <div class="col-lg-6"> --}}

                                <!-- Form Group -->
                                <div class="form-group col-lg-6">
                                    <label class="font-14 bold mb-2">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id" onchange="displayTime()" autocomplete="off">
                                        <option value="">Select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ optional($schedule->shift)->id === $shift->id ? 'selected' : ''}}>
                                                {{$shift->shift_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group col-lg-6">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ $schedule->remarks }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group col-lg-6">
                                    <label class="font-14 bold mb-2">Shift Times</label>
                                    <select class="theme-input-style" id="shift_schedule_id" name="shift_schedule_id" autocomplete="off">
                                        <option value="">Select Shift Times</option>
                                        @foreach ($shift_schedules as $shift_schedule)
                                        <option value="{{ $shift_schedule->id }}" {{ optional($shift_schedule)->id === $shift_schedule->id ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($shift_schedule->shift_start)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($shift_schedule->shift_end)->format('h:i A') }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('shift_schedule_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                            {{-- </div> --}}

                            {{-- <div class="col-12"> --}}
                                {{-- <pre>{{ dd($tasksAndDuties) }}</pre> --}}
                                <!-- Form Element -->
                                {{-- <div class="form-element py-30 mb-30"> --}}
                                    <!-- Repeater Html Start -->
                                    {{-- <div data-repeater-list="group-a"> --}}
                                        {{-- @forelse ($tasksAndDuties ?? [] as $task)
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
                                                            <img src="{{ asset('assets/img/svg/remove.svg') }}" alt="" class="svg">
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
                                                            <img src="{{ asset('assets/img/svg/remove.svg') }}" alt="" class="svg">
                                                        </button>
                                                    </div>

                                                </div>
                                                <hr />
                                            </div>
                                        @endforelse --}}
                                    {{-- </div> --}}

                                    <!-- Repeater End -->
                                    {{-- <button data-repeater-create type="button" class="repeater-add-btn btn-circle">
                                        <img src="{{ asset('assets/img/svg/plus_white.svg') }}" alt="" class="svg">
                                    </button> --}}

                                {{-- </div> --}}

                                <!-- End Form Element -->
                            {{-- </div> --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="check-all">Shift Days
                                        {{-- <span style="color:red;">*</span> --}}
                                    </label>
                                        <div data-repeater-item class="row text-center pt-2">
                                            <input hidden type="checkbox" class="check-all" id="check-all">
                                            <label class="btn btn-block bg-white border border-secondary text-primary col-lg-2 m-1" style="box-shadow: none !important;" for="check-all" >Select All</label>
                                        
                                                @php
                                                    $days = [
                                                        1 => 'Monday',
                                                        2 => 'Tuesday',
                                                        3 => 'Wednesday',
                                                        4 => 'Thursday',
                                                        5 => 'Friday',
                                                        6 => 'Saturday',
                                                        7 => 'Sunday'
                                                    ];

                                                    $selectedDays = $schedule->shift_days;
                                                    if (!is_array($selectedDays)) {
                                                        $selectedDays = explode('-', trim($selectedDays, '-'));
                                                    }
                                                    // dd($selectedDays);
                                                @endphp

                                                @foreach ( $days as $dayValue => $dayName )
                                                {{-- <div class="col-sm px-0"> --}}
                                                    <input hidden class="btn-check" type="checkbox" id="checkbox-{{ $dayName }}" value="{{$dayValue}}" name="shift_days[]" {{ isset($selectedDays) && in_array($dayValue, $selectedDays) ? 'checked' : '' }} autocomplete="off" >
                                                    <label class="btn btn-block bg-white border border-secondary text-primary col-lg-1 m-1 px-1" style="box-shadow: none !important;" for="check-all" >
                                                        <span class="text" style="text-align: center">{{$dayName}}</span>
                                                    </label>
                                                {{-- </div> --}}
                                                @endforeach
                                        </div>
                                        @error('shift_days')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
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

$(document).ready(function(){

    function updateCheckAll(repeater){
        var allChecked = true;  
        repeater.find('.btn-check').each(function() {
            if (!$(this).prop('checked')){
                allChecked = false;
                return false;
            }
        });

        repeater.find('.check-all').prop('checked', allChecked);
        return changeSelectAllLabel(allChecked);
    }

    function changeSelectAllLabel(allChecked){
        if(allChecked === true){
            $('.check-all').next('label').removeClass('bg-white border border-secondary text-primary').addClass('');
        }
        else{
            $('.check-all').next('label').removeClass('').addClass('bg-white border border-secondary text-primary');
        }
    }

    function updateLabel(checkbox){
        var label = checkbox.next('label');

        if (checkbox.prop('checked')) {
                label.removeClass('bg-white border-secondary text-primary').addClass('');
            } else {
                label.removeClass('').addClass('bg-white border border-secondary text-primary');
            }
            
            updateCheckAll(checkbox.closest('[data-repeater-item]'));
    }

    $(document).on('change', '.check-all', function(){
        var isChecked = $(this).prop('checked');
        var checkAll = $(this).next('label');

        if ($(this).prop('checked')) {
                checkAll.removeClass('bg-white border-secondary text-primary').addClass('');
            } else {
                checkAll.removeClass('').addClass('bg-white border border-secondary text-primary');
            }

        $(this).closest('[data-repeater-item]').find('.btn-check').prop('checked', isChecked).trigger('change');
    });

    $(document).on('click', '.btn-check + label', function(e){
        e.preventDefault();

        var checkbox = $(this).prev('.btn-check');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
    });

    $(document).on('change', '.btn-check', function(){
        updateLabel($(this));
    });

    $('.btn-check').each(function(){
        updateLabel($(this));
    });

    $('.check-all').each(function(){
        var repeaterItem = $(this).closest('[data-repeater-item]');
        updateCheckAll(repeaterItem);
    });

    $(document).ready( function(){
        var outlet_id = $('#outlet_id').val();
        return displayName();
    });

    // $(document).ready(function(){
    //     var shift_id = $('#shift_id').val();
    //     return displayShifts();
    // });

});

function displayName() {//in-progress, run 2 times if only working
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    let outlet_id = document.getElementById('outlet_id').value;
    let employeeOption = document.getElementById('employee_id');// make it get the default value?
    let employee_id = employeeOption.value;

        $.ajax({
            url:"{{route('changeNameByOutlet')}}",
            type:"GET",
            dataType:"json",
            data:{
                'outlet_id':outlet_id
            },
            success:function(response){
                // if (outlet_id != ''){
                    employeeOption.innerHTML = null;
                    let defaultSelection = '<option value="">Select Employee</option>';
                    employeeOption.insertAdjacentHTML('beforeend', defaultSelection);
                    response.forEach(item => {
                        let opt = document.createElement('option');
                            opt.innerHTML = item.nickname;
                            opt.value = item.id;
                            employeeOption.appendChild(opt);
                        });

                    if (employee_id != ''){// try pass the value from controller first?
                        console.log('if', employee_id);
                        employeeOption.value = employee_id;
                    }else{
                        // employeeOption.value = '';
                        console.log('else', employee_id);
                    }

                // }
            },
            error:function(xhr,status,error){
                console.log(error);
            }
        })
}

function displayTime() {

    let shift_id = document.getElementById('shift_id').value;
    let timeOption = document.getElementById('shift_schedule_id');
    if (shift_id !=''){
        $.ajax({
            url:"{{route('changeShiftTimeByShift')}}",
            type:"GET",
            dataType:"json",
            data:{
                'shift_id':shift_id
            },
            success:function(response){
                timeOption.innerHTML = null;
                let defaultSelection = '<option value="">Select Shift Time</option>';
                timeOption.insertAdjacentHTML('beforeend', defaultSelection);
                response.forEach(item => {

                const [shift_start_24hours, shift_start_min] = item.shift_start.split(':').map(Number);
                const [shift_end_24hours, shift_end_min] = item.shift_end.split(':').map(Number);

                const shift_start_ampm = shift_start_24hours >= 12? 'PM' : 'AM';
                const shift_end_ampm = shift_end_24hours >= 12? 'PM' : 'AM';

                    shift_start_12hour = shift_start_24hours % 12 || 12;
                    shift_end_12hour = shift_end_24hours % 12 || 12;

                const shift_start_fhour = shift_start_12hour < 10 ? '0' + shift_start_12hour : shift_start_12hour;
                const shift_end_fhour = shift_end_12hour < 10 ? '0' + shift_end_12hour : shift_end_12hour;

                const shift_start_fmin = shift_start_min < 10 ? '0' + shift_start_min : shift_start_min;
                const shift_end_fmin = shift_end_min < 10 ? '0' + shift_end_min : shift_end_min;

                let shift_start = shift_start_fhour + ':' + shift_start_fmin + ' ' + shift_start_ampm;
                let shift_end = shift_end_fhour +':'+ shift_end_fmin + ' '+ shift_end_ampm;

                let opt = '<option value="' + item.id + '">' + shift_start + ' - ' + shift_end + '</option>'
                timeOption.insertAdjacentHTML('beforeend', opt);
                // console.log(opt);
                });
            },
            error:function(xhr,status,error){
                console.log(error);
            }
        })
    }
    // console.log(shift_id);
}
</script>
