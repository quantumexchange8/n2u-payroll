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

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift Days</label>
                                    <div class="row text-center pt-2">
                                        <div class="col-sm px-0">
                                            <input hidden id="chk1" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block text-indigo-500" for="chk1">
                                                <span class="text-primary">Monday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk2" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk2">
                                                <span class="text">Tuesday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk3" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk3">
                                                <span class="text">Wednesday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk4" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk4">
                                                <span class="text">Thursday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk5" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk5">
                                                <span class="text">Friday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk6" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk6">
                                                <span class="text">Saturday</span>
                                            </label>
                                        </div>

                                        <div class="col-sm px-0">
                                            <input hidden id="chk7" type="checkbox" class="btn-check" autocomplete="off">
                                            <label class="btn btn-block" for="chk7">
                                                <span class="text">Sunday</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('shift_days')
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
    // Toggle label classes based on checkbox state for each checkbox
    $('.btn-check').each(function() {
        var checkbox = $(this);
        var label = $('label[for="' + checkbox.attr('id') + '"]');

        checkbox.change(function() {
            var isChecked = checkbox.prop('checked');

            if (isChecked) {
                label.addClass('bg-white border border-indigo text-indigo').removeClass('');
            } else {
                label.addClass('').removeClass('bg-white border border-indigo text-indigo');
            }
        });

        // Initial check on page load
        if (checkbox.prop('checked')) {
            label.addClass('').removeClass('');
        } else {
            label.addClass('').removeClass('');
        }
    });
});
</script>