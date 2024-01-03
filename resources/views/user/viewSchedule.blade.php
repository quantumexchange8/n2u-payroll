@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<!-- Main Content -->
{{-- <div class="main-content d-flex flex-column flex-md-row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>

    <!-- Button to trigger the modal -->
    <button id="openModalButton" style="display: none;" data-toggle="modal" data-target="#scheduleModal"></button>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedules for Selected Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <!-- Schedule data will be displayed here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div> --}}

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            @php
                $displayedDates = [];
                $remainingSchedules = 8; // Change this to the total number of schedules you want to display
            @endphp

            @foreach ($schedules->where('date', '>=', now()->toDateString())->sortBy('date') as $schedule)
                @if (!in_array($schedule->date, $displayedDates))
                    @php
                        $displayedDates[] = $schedule->date;
                        $remainingSchedules--;
                    @endphp

                    <div class="col-md-4 col-sm-6">
                        <!-- Card -->
                        <div class="card mb-30">
                            <div class="card-body">
                                <h4 class="progress-title">Date: {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</h4>
                                <div class="mb-4">
                                    <div class="card-body">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <h4 class="font-18">Shift:</h4>
                                        </div>
                                    </div>
                                    <ul>
                                        @if ($schedules->where('date', $schedule->date)->isNotEmpty())
                                            @foreach ($schedules->where('date', $schedule->date)->sortBy('shift_start') as $shift)
                                                <li>
                                                    {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                                </li>
                                            @endforeach
                                        @else
                                            <li>No shifts assigned for this schedule</li>
                                        @endif
                                    </ul>

                                    <div class="card-body">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <h4 class="font-18">Task:</h4>
                                        </div>
                                    </div>
                                    <ul>
                                        @if ($tasks->where('date', $schedule->date)->isNotEmpty())
                                            @foreach ($tasks->where('date', $schedule->date)->sortBy('start_time') as $task)
                                                <li>
                                                    <strong>{{ $task->period_name }}</strong>
                                                    <ul>
                                                        <li>
                                                            {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} -
                                                            {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                                                        </li>
                                                        <li>{{ $task->duty_name }}</li>
                                                    </ul>
                                                </li>
                                            @endforeach
                                        @else
                                            <li>No tasks assigned for this schedule</li>
                                        @endif
                                    </ul>


                                    {{-- <div class="card-body">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <h4 class="font-18">Task</h4>
                                            <br>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="text-nowrap table-bordered dh-table">
                                                <thead>
                                                    <tr>
                                                        <th>Period</th>
                                                        <th>Time</th>
                                                        <th>Duty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($tasks->where('date', $schedule->date)->isNotEmpty())
                                                        @foreach ($tasks->where('date', $schedule->date)->sortBy('start_time') as $task)
                                                            <tr>
                                                                <td>{{ $task->period_name }}</td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} -
                                                                    {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                                                                </td>
                                                                <td>{{ $task->duty_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <td>No tasks assigned for this schedule</td>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                @endif

                @if ($remainingSchedules == 0)
                    @break
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- End Main Content -->

@endsection

<script>
    var userId = {{ $user->id }};
</script>
