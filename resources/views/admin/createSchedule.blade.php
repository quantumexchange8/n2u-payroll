@extends('layouts.master')
@section('content')

<!-- Include SweetAlert library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#selected_users').select2({
            placeholder:"Select Employee",
            allowClear: true,
            closeOnSelect: false,
        });
    });
</script>

<style>
    .select2-container--default .select2-selection--multiple{
        background-color: #f0f0f0;
        height: 40px;
        padding: 0 15px;
        /* border-radius: 7px !important; */
        font-size: 16px;
    }

    .select2-container--default{
        background-color: #f0f0f0;
        border-radius: 7px;
    }

    .select2-results__option--selectable{

    }

    .select2-selection__choice__display{
        bottom: 2px !important;
    }
</style>
<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Create New Schedule</h4>

                    <!-- Form -->
                    <form action="{{route('addSchedule')}}" method="POST" class="repeater-default">
                        @csrf

                        <!-- Color Options -->
                        <div class="form-element color-options">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="font-14 bold mb-2" for="outlet_id">Outlet
                                        <span style="color:red;">*</span>
                                    </label>
                                    <select class="theme-input-style" name="outlet_id" id="outlet_id" onchange="displayName()">
                                        <option value="">Select Outlet</option>

                                        @foreach ( $outlets as $outlets )
                                        <option value="{{ $outlets->id}}" {{ old('outlet_id') == $outlets->id ? 'selected' : '' }}>{{ $outlets->outlet_location}}</option>
                                        {{-- <option value="{{ $outlets->id}}">{{ $outlets->outlet_location}}</option> --}}
                                        @endforeach

                                    </select>
                                    @error('outlet_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label class="font-14 bold mb-2" for="selected_users">Name
                                        <span style="color:red;">*</span>
                                    </label>
                                    <select class="theme-input-style form-control" name="selected_users[]" id="selected_users" multiple>
                                        {{-- <select id="multiple-checkboxes" multiple="multiple"> --}}
                                        {{-- <option value="">Select Employee</option> --}}
                                        {{-- @foreach ( $users as $users )
                                        <option value="{{ $users->id }}" {{ old('user_id') == $users->id ? 'selected' : ''}}>{{ $users->nickname}}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('selected_users')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- @php
                                    $usersChunked = $users->chunk(ceil($users->count() / 4));
                                @endphp  --}}

                                    {{-- @foreach ($usersChunked as $userChunk)
                                    <div class="col-lg-3 col-sm-6 mb-30 mb-lg-0">
                                        @foreach ($userChunk as $user)

                                                <div class="d-flex align-items-center mb-3">
                                                    <!-- Custom Checkbox -->
                                                    <label class="custom-checkbox solid position-relative mr-2">
                                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                        {{ in_array($user->id, old('selected_users', [])) ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <!-- End Custom Checkbox -->

                                                    <label for="check{{ $user->id }}">{{ $user->nickname }}</label>
                                                </div>

                                        @endforeach
                                    </div>
                                @endforeach --}}
                                
                            </div>
                        </div>
                        <!-- End Color Options -->

                        <div class="form-element">
                            <div class="row">
                                {{-- <div class="col-lg-6 mb-3"> --}}

                                    <!-- Form Group -->
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2" for="date_start">Start Date
                                            <span style="color:red;">*</span>
                                        </label>
                                        <input type="date" class="theme-input-style" id="date_start" name="date_start" autocomplete="off"  value="{{ old('date_start') }}" max="9999-12-31">
                                        @error('date_start')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    <!-- End Form Group -->


                                    {{-- <div class="d-flex align-items-center mb-3" style="margin-top: 50px;">
                                        <!-- Custom Checkbox -->
                                        <label class="custom-checkbox solid position-relative mr-2">
                                            <input type="checkbox" name="off_day" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                        <!-- End Custom Checkbox -->
                                        <label for="check26">Off Day</label>
                                    </div> --}}

                                {{-- </div> --}}

                                {{-- <div class="col-lg-6 mb-3"> --}}

                                    <!-- Form Group -->
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2" for="date_end">End Date</label>
                                        <input type="date" class="theme-input-style" id="date_end" name="date_end" autocomplete="off"  value="{{ old('date_end') }}" max="9999-12-31">
                                        @error('date_end')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2" for="shift_id">Shift
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="theme-input-style" id="shift_id" name="shift_id" autocomplete="off" onchange="displayShifts()">
                                            <option value="">Select Shift</option>
                                            @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{$shift->shift_name}} - {{ $shift->formatted_shift_time }}</option>
                                                {{-- <option value="{{ $shift->id }}">{{$shift->shift_name}} - {{ $shift->formatted_shift_time }}</option> --}}
                                            @endforeach
                                        </select>
                                        @error('shift_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2" for="remarks">Remarks</label>
                                        <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                        @error('remarks')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    <!-- End Form Group --> 
                                {{-- </div> --}}
                            </div>

                            {{-- <div class="col-12">
                                <!-- Form Element -->
                                <div class="form-element py-30 mb-30">
                                    <!-- Repeater Html Start -->
                                    <div data-repeater-list="group-a">

                                        <!-- Repeater Items -->
                                        <div data-repeater-item id="data-repeater-item">
                                            <!-- Repeater Content -->
                                            <div class="item-content align-items-center row">

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label class="bold mb-2" for="period_id">Period</label>
                                                    <select class="theme-input-style" id="period_id" name="period_id">
                                                        <option value="">Select Period</option>
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>{{ $period->period_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('period_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label class="bold mb-2" for="start_time">Start Time</label>
                                                    <input type="time" class="form-control" id="start_time" name="start_time" autocomplete="off" value="{{ old('start_time') }}">
                                                    @error('start_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label class="bold mb-2" for="end_time">End Time</label>
                                                    <input type="time" class="form-control" id="end_time" name="end_time" autocomplete="off" value="{{ old('end_time') }}">
                                                    @error('end_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label class="bold mb-2" for="duty_id">Duty</label>
                                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off" value="{{ old('duty_id') }}">
                                                        <option value="">Select Duty</option>
                                                        @foreach ($duties as $duty)
                                                        <option value="{{ $duty->id }}" {{ old('duty_id') == $duty->id ? 'selected' : '' }}>{{ $duty->duty_name }}</option>
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
                                                        {{-- <button data-repeater-delete class="">  --}}
                                                    {{-- <button class="remove-extra" id="remove-extra" type="button" onclick="removeExtra()">
                                                        <img src="{{ asset('assets/img/svg/remove.svg') }}" alt="" class="svg">
                                                    </button>
                                                </div>

                                            </div> 
                                            <hr />
                                        </div> --}}
                                        <!-- End Repeater Items -->

                                    {{-- </div>
                                    <!-- Repeater End -->
                                    <button data-repeater-create type="button" class="repeater-add-btn btn-circle">
                                        <img src="{{ asset('assets/img/svg/plus_white.svg') }}" alt="" class="svg">
                                    </button>
                                </div> 
                                <!-- End Form Element -->
                            </div> --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="check-all">Shift Days
                                        <span style="color:red;">*</span>
                                    </label>
                                        <div data-repeater-item class="row text-center pt-2">
                                            <input hidden type="checkbox" class="check-all" id="check-all">
                                            <label class="btn btn-block bg-white border border-secondary text-primary col-lg-2 m-1" style="box-shadow: none !important;" for="check-all" >Select All</label>
                                        
                                                @php
                                                    $days = [
                                                        1 => 'Mon',
                                                        2 => 'Tue',
                                                        3 => 'Wed',
                                                        4 => 'Thu',
                                                        5 => 'Fri',
                                                        6 => 'Sat',
                                                        7 => 'Sun'
                                                    ];
                                                @endphp

                                                @foreach ( $days as $dayValue => $dayName )
                                                {{-- <div class="col-sm px-0"> --}}
                                                    <input hidden class="btn-check" type="checkbox" id="checkbox-{{ $dayName }}" value="{{$dayValue}}" name="shift_days[]" {{ old('shift_days') == $dayName ? 'checked' : '' }} autocomplete="off" >
                                                    <label class="btn btn-block bg-white border border-secondary text-primary col-lg-1 m-1" style="box-shadow: none !important;" for="check-all" >
                                                        <span class="text">{{$dayName}}</span>
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

                        <div class="row form-element pt-0">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <div class="table-responsive"> 
                                        <table class="text-nowrap bg-white dh-table">
                                            <thead>
                                                <tr>
                                                    <th>Shift</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Pick a Time</th>
                                                </tr>
                                            </thead>
                                            <tbody id="displayShift">
                                                {{-- <th><label></label><span class="checkmark"></span></th> --}}
                                            </tbody>
                                        </table>
                                        <div class="text-muted text-center pt-4" id="shiftText"> Select shift to view the details </div>
                                    </div>
                                </div>
                                @error('shift_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Submit</button>
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
<script>

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
        return displayName();
    });

    $(document).ready(function(){
        return displayShifts();
    });

});

    // change the employees name display by outlet input
    function displayName() {

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let outlet_id = document.getElementById("outlet_id").value;
        let employeeOption = document.getElementById('selected_users');
        if (outlet_id != ''){
        $.ajax({
            url:"{{route('changeNameByOutlet')}}",
            type:"GET",
            dataType:"json",
            data:{
                _token: "{{ csrf_token() }}",
                'outlet_id':outlet_id
            },
            success:function(response){
                employeeOption.innerHTML = null;

                    response.forEach(item => {
                    let opt = document.createElement('option');
                        opt.innerHTML = item.nickname;
                        opt.value = item.id;
                        employeeOption.appendChild(opt);
                        // employeeOption.insertAdjacentHTML('beforeend', opt);
                    });

            },
            error:function(xhr,status,error){
                console.log(error);
            }
        })
    }
    else{
        employeeOption.innerHTML = null;
    }

    }

    //display table based on selected shift
    function displayShifts() {
        let shift_id = document.getElementById('shift_id').value;
        let shiftTable = document.getElementById('displayShift');
        let shiftText = document.getElementById('shiftText');
        let details = '';

        $.ajax({
            url:"{{route('displayShift')}}",
            type:"GET",
            dataType:"json",
            data:{
                _token: "{{ csrf_token() }}",
                'shift_id':shift_id
            },
            success:function(response){
                if( shift_id != '' && shiftText != null){
                    shiftText.remove();
                }
                
                $('#displayShift').empty();

                let idIndex = 0;
                response.forEach(item => {
                    let shift_name = item.shift_name;
                    item.shift_schedules.forEach( array => {

                        let shift_schedules_id = array.id;
                        let shift_id = array.shift_id;
                        let shift_start_str = array.shift_start;
                        let shift_end_str = array.shift_end;
                        let days = array.shift_days;

                const [shift_start_24hours, shift_start_min] = shift_start_str.split(':').map(Number);
                const [shift_end_24hours, shift_end_min] = shift_end_str.split(':').map(Number);

                const dayMappings = [
                    'Mon',
                    'Tue',
                    'Wed',
                    'Thu',
                    'Fri',
                    'Sat',
                    'Sun'
                ];

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

                // details = '<tr><td>' + shift_name +
                //           '</td><td>' + shift_start +
                //           '</td><td>' + shift_end +
                //           '</td><td data-repeater-item class="row dayMo" id="dayMo-' + idIndex +'"><input hidden class="check-all" id="check-all" type="checkbox"><label for="check-all" class="btn btn-block bg-white border border-secondary text-primary col-3 m-1" style="box-shadow: none !important;">Select All</label></td></tr>';
                
                details = '<tr><td>' + shift_name +
                          '</td><td>' + shift_start +
                          '</td><td>' + shift_end +
                        //   '</td><td class="row dayMo" id="dayMo-' + idIndex +'"></td></tr>'
                        '</td><td><label><input class="time_check" type="checkbox" checked name="shift_schedule_id[' +idIndex + 
                        ']" id="time_check" value="' + shift_schedules_id + '"></label></td></tr>'
                        //   '</td><td><label class="custom-checkbox"><input type="checkbox" id="pickA"><span class="checkmark"></span></label></td>'
                displayShift.insertAdjacentHTML('beforeend', details);

                    idIndex = idIndex + 1;
                    });
                });
            },
            error:function(xhr,status,error){
                console.log(error);
            }
        })
    }
</script>
@endsection


