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
                                    <div class="d-flex justify-content-between">
                                        <label class="font-14 bold">Shift Days</label>
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" id="checkAll">
                                            <label class="font-14">Select all Days</label>
                                        </div>
                                    </div>
                                    <div class="row text-center pt-2">
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
                                        @endphp

                                        @foreach($days as $dayValue => $dayName)
                                            <div class="col-sm px-0">
                                                <input hidden id="chk{{ $dayValue }}" name="shift_days[]" type="checkbox" class="btn-check" autocomplete="off" value="{{ $dayValue }}" {{ in_array($dayValue, old('shift_days', [])) ? 'checked' : '' }}>
                                                <label class="btn btn-block bg-white border border-secondary text-primary" style="box-shadow:none !important" for="chk{{ $dayValue }}">
                                                    <span class="text">{{ $dayName }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                        <!-- <div class="col-sm px-0">
                                            <input hidden id="chk1" name="shift_days[]" type="checkbox" class="btn-check" autocomplete="off" value="1" {{ in_array('1', old('shift_days', [])) ? 'checked' : '' }}>
                                            <label class="btn btn-block border" for="chk1">
                                                <span class="text">Monday</span>
                                            </label>
                                        </div> -->
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
        function updateCheckAll() {
            var allChecked = true;
            $('input[id^="chk"]').each(function() {
                if (!$(this).prop('checked')) {
                    allChecked = false;
                    return false; // Exit the loop early
                }
            });
            $('#checkAll').prop('checked', allChecked);
        } // This is a function to update checkAll checkbox based on condition
       
        $('#checkAll').change(function() {
            var isChecked = $(this).prop('checked');
            $('input[id^="chk"]').prop('checked', isChecked).trigger('change');
        }); // This is for checkAll checkbox
        
        $('.btn-check').each(function() {
            var checkbox = $(this);
            var label = $('label[for="' + checkbox.attr('id') + '"]');

            checkbox.change(function() {
                var isChecked = checkbox.prop('checked');

                if (isChecked) {
                    label.addClass('').removeClass('bg-white border-secondary text-primary');
                } else {
                    label.addClass('bg-white border border-secondary text-primary').removeClass('');
                }

                updateCheckAll();
            });

            // Initial check on page load
            if (checkbox.prop('checked')) {
                label.addClass('').removeClass('bg-white border-secondary text-primary');
            } else {
                label.addClass('bg-white border border-secondary text-primary').removeClass('');
            }
        }); // This is for checkbox of each day

        updateCheckAll();
    });
    
</script>