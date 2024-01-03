@extends('layouts.master')
@section('content')

<!-- Include SweetAlert library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                <div class="col-lg-12">
                                    <label class="font-14 bold mb-2">Name</label>
                                </div>

                                @php
                                    $usersChunked = $users->chunk(ceil($users->count() / 4));
                                @endphp

                                @foreach ($usersChunked as $userChunk)
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
                                @endforeach
                            </div>
                            @error('selected_users')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- End Color Options -->

                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift</label>
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
                                    <label class="font-14 bold mb-2">Start Date</label>
                                    <input type="date" class="theme-input-style" id="date_start" name="date_start" autocomplete="off"  value="{{ old('date_start') }}">
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
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">End Date</label>
                                    <input type="date" class="theme-input-style" id="date_end" name="date_end" autocomplete="off"  value="{{ old('date_end') }}">
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
                                        <div data-repeater-item>
                                            <!-- Repeater Content -->
                                            <div class="item-content align-items-center row">

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputName" class="bold mb-2">Period</label>
                                                    <select class="theme-input-style" id="period_id" name="period_id">
                                                        <option value="">Select Period</option>
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>{{ $period->period_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label for="inputMobile" class="bold mb-2">Start Time</label>
                                                    <input type="time" class="form-control" id="start_time" name="start_time" autocomplete="off" value="{{ old('start_time') }}">
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label for="inputMobile" class="bold mb-2">End Time</label>
                                                    <input type="time" class="form-control" id="end_time" name="end_time" autocomplete="off" value="{{ old('end_time') }}">
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputCompany" class="bold mb-2">Duty</label>
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

@endsection


