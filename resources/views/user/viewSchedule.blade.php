@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <h4 class="font-20 ">Schedule Table</h4>
                    </div>
                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Date</th>
                                    <th>Sunday</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $schedules = $schedules->sortBy('date');
                                @endphp
                                @foreach ($schedules as $schedule)
                                    @php
                                    $scheduleDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->date);
                                    $shiftInfo = null;
                                    if ($schedule->shift && $schedule->shift->shift_start && $schedule->shift->shift_end) {
                                        $shiftInfo = $schedule->shift->shift_start->format('h:i A') . ' - ' . $schedule->shift->shift_end->format('h:i A');
                                    }
                                    // Determine the day of the week (0 = Sunday, 1 = Monday, etc.)
                                    $dayOfWeek = $scheduleDate->dayOfWeek;
                                    @endphp
                    
                                    <tr>
                                        <td>{{ $scheduleDate->format('Y-m-d') }}</td>
                                        @for ($i = 0; $i < 7; $i++)
                                        <td>
                                            @if ($i === $dayOfWeek)
                                                @php
                                                    $startTime = \Carbon\Carbon::parse($schedule->shift->shift_start);
                                                    $endTime = \Carbon\Carbon::parse($schedule->shift->shift_end);
                                                @endphp
                                                <div style="text-align: center;">
                                                    {{ $startTime->format('h:ia') }}<br>-<br>{{ $endTime->format('h:ia') }}
                                                </div>
                                            @endif
                                        </td>                                        
                                        @endfor
                                    </tr>
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