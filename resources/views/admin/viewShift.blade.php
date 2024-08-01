@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Shift Table</h4>
                            <div class="d-flex flex-wrap">
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                            <a href="{{route('createShift')}}" class="btn long">Create</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap bg-white dh-table">
                            <thead>
                                <tr>
                                    <th>Shift</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
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

                                @foreach($shifts as $shift)
                                    @php
                                        $shiftName = true; // Flag to track the first schedule
                                        $shiftAction = true;
                                    @endphp
                                    @foreach ($shift->shift_schedules as $schedule)
                                        @php
                                            $selectedDays = explode('-', trim($schedule->shift_days, '-'));
                                        @endphp
                                        <tr>                                            
                                            @if ($shiftName)
                                                <td rowspan="{{ $shift->shift_schedules->count() }}">{{ $shift->shift_name }}</td>
                                                @php
                                                    $shiftName = false;
                                                @endphp
                                            @endif
                                            <td>{{ date('h:i A', strtotime($schedule->shift_start)) }}</td>
                                            <td>{{ date('h:i A', strtotime($schedule->shift_end)) }}</td>
                                            <td class="row">
                                                @foreach($days as $dayValue => $dayName)
                                                    @if(in_array($dayValue, $selectedDays))
                                                        <span class="d-inline-block border rounded px-1 py-1 col text-center">
                                                            {{ $dayName }}
                                                        </span>
                                                    @else
                                                        <span class="d-inline-block bg-light border border-light rounded px-1 py-1 col text-center">&nbsp;</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @if ($shiftAction)
                                                <td rowspan="{{ $shift->shift_schedules->count() }}">
                                                    <a href="{{ route('editShift', ['id' => $shift->id]) }}" class="details-btn">
                                                        Edit <i class="icofont-arrow-right"></i>
                                                    </a>
                                                    <form action="{{ route('deleteShift', ['id' => $shift->id]) }}" method="POST" style="display: inline;">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="details-btn delete-btn" style="margin-left: 10px;">
                                                            Delete <i class="icofont-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                @php
                                                    $shiftAction = false;
                                                @endphp
                                            @endif 
                                        </tr>
                                    @endforeach 
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Invoice List Table -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Main Content -->

@endsection
