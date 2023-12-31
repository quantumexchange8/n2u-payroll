@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Create New Shift</h4>

                    <!-- Form -->
                    <form action="{{route('addShift')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift Name</label>
                                    <input type="text" class="theme-input-style" id="shift_name" name="shift_name" autocomplete="off" placeholder="Shift Name" value="{{ old('shift_name') }}">
                                    @error('shift_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 
                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Start Time</label>
                                    <input type="time" class="theme-input-style" id="shift_start" name="shift_start" autocomplete="off" placeholder="Start Time" value="{{ old('shift_start') }}">
                                    @error('shift_start')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">End Time</label>
                                    <input type="time" class="theme-input-style" id="shift_end" name="shift_end" autocomplete="off" placeholder="End Time" value="{{ old('shift_end') }}">
                                    @error('shift_end')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 
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