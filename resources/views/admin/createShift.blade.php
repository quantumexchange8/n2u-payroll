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
                    <form action="{{route('addShift')}}" method="POST" class="repeater-default">
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
                        </div>

                        <div class="form-element py-30">
                            <!-- Repeater Html Start -->
                            <div data-repeater-list="shift_schedules">
                                <!-- Repeater Items -->
                                @foreach (old('shift_schedules', [[]]) as $index => $schedule)
                                <div data-repeater-item>
                                    <!-- Repeater Content -->
                                    <div class="item-content align-items-center row">
                                        <!-- Form Group -->
                                        {{-- <div class="form-group col-lg-7">
                                            <div class="d-flex justify-content-between">
                                                <label class="font-14 bold">Shift Days</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="check-all">
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
                                                        <input hidden id="chk{{ $dayValue }}" name="shift_days" type="checkbox" class="btn-check" autocomplete="off" value="{{ $dayValue }}" {{ isset($schedule['shift_days']) && in_array($dayValue, $schedule['shift_days']) ? 'checked' : '' }}>
                                                        <label class="btn btn-block bg-white border border-secondary text-primary" style="box-shadow:none !important" for="chk{{ $dayValue }}">
                                                            <span class="text">{{ $dayName }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('shift_schedules.'.$index.'.shift_days')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                        <!-- End Form Group -->

                                        <!-- Form Group -->
                                        <div class="form-group col-lg-2">
                                            <label class="font-14 bold mb-2">Start Time</label>
                                            <input type="time" class="theme-input-style" name="shift_start" autocomplete="off" placeholder="Start Time" value="{{ $schedule['shift_start'] ?? '' }}">
                                            @error('shift_schedules.'.$index.'.shift_start')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- End Form Group -->

                                        <!-- Form Group -->
                                        <div class="form-group col-lg-2">
                                            <label class="font-14 bold mb-2">End Time</label>
                                            <input type="time" class="theme-input-style" name="shift_end" autocomplete="off" placeholder="End Time" value="{{ $schedule['shift_end'] ?? '' }}">
                                            @error('shift_schedules.'.$index.'.shift_end')
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
                                @endforeach
                                <!-- End Repeater Items -->

                            </div>
                            <!-- Repeater End -->
                            <button data-repeater-create type="button" class="repeater-add-btn btn-circle" >
                                <img src="{{ asset('assets/img/svg/plus_white.svg') }}" alt="" class="svg">
                            </button>
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
        function updateCheckAll(repeater) {
            var allChecked = true;
            repeater.find('.btn-check').each(function() {
                if (!$(this).prop('checked')) {
                    allChecked = false;
                    return false;
                }
            });
            repeater.find('.check-all').prop('checked', allChecked);
        }

        function updateLabel(checkbox) {
            var label = checkbox.next('label');

            if (checkbox.prop('checked')) {
                label.removeClass('bg-white border-secondary text-primary').addClass('');
            } else {
                label.removeClass('').addClass('bg-white border border-secondary text-primary');
            }

            updateCheckAll(checkbox.closest('[data-repeater-item]'));
        }

        $(document).on('change', '.check-all', function() {
            var isChecked = $(this).prop('checked');
            $(this).closest('[data-repeater-item]').find('.btn-check').prop('checked', isChecked).trigger('change');
        });
        
        $(document).on('click', '.btn-check + label', function(e) {
            e.preventDefault(); // Prevent default label behavior
            
            var checkbox = $(this).prev('.btn-check');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        });

        $(document).on('change', '.btn-check', function() {
            updateLabel($(this));
        });

        $('.btn-check').each(function() {
            updateLabel($(this));
        });

        $('.check-all').each(function() {
            var repeaterItem = $(this).closest('[data-repeater-item]');
            updateCheckAll(repeaterItem);
        });

    });
</script>