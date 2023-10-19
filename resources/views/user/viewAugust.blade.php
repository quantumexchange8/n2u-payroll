@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="font-20">Schedule for August</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('viewJuly') }}" class="btn btn-primary">Previous</a>
                                <a href="{{ route('viewSeptember') }}" class="btn btn-primary">Next</a>
                            </div>
                        </div>
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
                                        $shiftInfo = $schedule->shift->shift_start->format('h:i A') . ' - ' . $schedule->shift->shift_end->format('h:ia');
                                    }
                                    // Check if the schedule date falls within November (month number 11)
                                    if ($scheduleDate->month === 8) {
                                        // Determine the day of the week (0 = Sunday, 1 = Monday, etc.)
                                        $dayOfWeek = $scheduleDate->dayOfWeek;
                                    @endphp
            
                                    <tr>
                                        <td>{{ $scheduleDate->format('d F Y') }}</td>
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
            
                                    @php
                                    } // Close the if statement for checking the month
                                    @endphp
            
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