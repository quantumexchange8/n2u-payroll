@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Create New Task</h4>
                    <!-- Form -->
                    <form action="{{route('addTask')}}" method="POST" class="repeater-default">
                        @csrf
                        {{-- @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}

                        <!-- Color Options -->
                        <div class="form-element color-options">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="bold mb-2">Nickname</label>
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
                                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
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
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="date" name="dates" autocomplete="off">
                                    @error('dates')
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
                                                    <select class="theme-input-style" id="period_id" name="group-a[0][period_id]" value="{{ old('period_id') }}">
                                                        <option value="">Select Period</option>
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}">{{ $period->period_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('group-a.0.period_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label for="inputMobile" class="bold mb-2">Start</label>
                                                    <input type="time" class="form-control" id="start_time" name="group-a[0][start_time]" autocomplete="off">
                                                    @error('group-a.0.start_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-2">
                                                    <label for="inputMobile" class="bold mb-2">End</label>
                                                    <input type="time" class="form-control" id="end_time" name="group-a[0][end_time]" autocomplete="off">
                                                    @error('group-a.0.end_time')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputCompany" class="bold mb-2">Duty</label>
                                                    <select class="theme-input-style" id="duty_id" name="group-a[0][duty_id]" autocomplete="off" value="{{ old('duty_id') }}">
                                                        <option value="">Select Duty</option>
                                                        @foreach ($duties as $duty)
                                                        <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>
                                                    @endforeach
                                                    </select>
                                                    @error('group-a.0.duty_id')
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
