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
                                    <label class="font-14 bold mb-2" for="outlet_id">Outlet</label>
                                    <select class="theme-input-style" name="outlet_id" id="outlet_id" onchange="displayName()">
                                        <option value="">Select Outlet</option>

                                        @foreach ( $outlets as $outlets )
                                        {{-- <option value="{{ $outlets->id}}" {{ old('outlet_id') == $outlets->id ? 'selected' : '' }}>{{ $outlets->outlet_location}}</option> --}}
                                        <option value="{{ $outlets->id}}">{{ $outlets->outlet_location}}</option>
                                        @endforeach

                                    </select>
                                    @error('outlet')
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

                        <div class="row form-element">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="shift_id">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id" autocomplete="off">
                                        <option value="">Select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{$shift->shift_name}} - {{ $shift->formatted_shift_time }}</option>
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="date_start">Start Date</label>
                                    <input type="date" class="theme-input-style" id="date_start" name="date_start" autocomplete="off"  value="{{ old('date_start') }}" max="9999-12-31">
                                    @error('date_start')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <div class="d-flex align-items-center mb-3" style="margin-top: 50px;">
                                    <!-- Custom Checkbox -->
                                    <label class="custom-checkbox solid position-relative mr-2">
                                        <input type="checkbox" name="off_day" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <!-- End Custom Checkbox -->
                                    <label for="check26">Off Day</label>
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="remarks">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="date_end">End Date</label>
                                    <input type="date" class="theme-input-style" id="date_end" name="date_end" autocomplete="off"  value="{{ old('date_end') }}" max="9999-12-31">
                                    @error('date_end')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-12">
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
                                                    {{-- <button class="remove-extra" id="remove-extra" type="button" onclick="removeExtra()"> --}}
                                                        <img src="{{ asset('assets/img/svg/remove.svg') }}" alt="" class="svg">
                                                    </button>
                                                </div>

                                            </div>
                                            <hr />
                                        </div>
                                        <!-- End Repeater Items -->

                                    </div>
                                    <!-- Repeater End -->
                                    <button data-repeater-create type="button" class="repeater-add-btn btn-circle">
                                        <img src="{{ asset('assets/img/svg/plus_white.svg') }}" alt="" class="svg">
                                    </button>
                                </div>
                                <!-- End Form Element -->
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

    function removeExtra() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#109130',
            cancelButtonColor: '#d33',
            confirmButtonText:'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {

            if(result.isConfirmed){
            const El = document.getElementById('remove-extra');
                El.classList.add("zoomOut");
            const repeater_item = document.getElementById('data-repeater-item');

            setTimeout(() => {
                repeater_item.remove();
                Swal.fire(
                'Deleted',
                'Your item has been removed',
                'success'
                );
            }, 1000);
                
        }else if (result.dismiss === Swal.DismissReason.cancel){
            Swal.fire(
                'Cancelled',
                'Safe',
                'Error'
            );
        }
        });
    }

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
                if (Array.isArray(response)){
                    const placeHolder = document.createElement('option');
                    employeeOption.appendChild(placeHolder);
                    response.forEach(item => {
                        // let opt = '<option value="' + item.value + '{{ old(' + item.value +') ==' + item.value '? "selected" : "" }}">' + item.text + '</option>';
                        // employeeOption.append('<option value="' + item.value + '">' + item.text + '</option>');
                        // employeeOption.append(opt);
                        let opt = document.createElement('option');
                        opt.innerHTML = item.text;
                        opt.value = item.value;
                        employeeOption.appendChild(opt);
                        // console.log(opt);
                    });
                }
                else{
                console.log('not array');
                }
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
</script>
@endsection


