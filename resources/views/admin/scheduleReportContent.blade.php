@foreach ($schedules as $schedule)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($schedule->date)->toDateString();
                                        $employeeName = $schedule->user->nickname;
                                    @endphp

                                    @if ($recordDate)
                                        <tr data-schedule-id="{{ $schedule->id }}" data-tasks="{{ json_encode($schedule->tasks) }}" data-date="{{ $recordDate }}" data-full-name="{{ $schedule->user->nickname }}">
                                            <td>
                                                <!-- Custom Checkbox -->
                                                <label class="custom-checkbox">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <!-- End Custom Checkbox -->
                                            </td>
                                            <td>{{$schedule->id}}</td>
                                            <td>{{$schedule->shift_schedule_id}} </td>
                                            <td>{{ Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</td>
                                            <td>{{ $schedule->user->nickname }}</td>
                                            <td>
                                                @if ($schedule->shift_schedules && $schedule->shift_schedules->shift_start && $schedule->off_day === 0)
                                                    {{ Carbon\Carbon::parse($schedule->shift_schedules->shift_start)->format('h:i A') }}
                                                @else
                                                    <b>Off Day</b>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($schedule->shift_schedules && $schedule->shift_schedules->shift_end && $schedule->off_day === 0)
                                                    {{ Carbon\Carbon::parse($schedule->shift_schedules->shift_end)->format('h:i A') }}
                                                 @else
                                                    <b>Off Day</b>
                                                @endif
                                            </td>
                                            <td>{{ $schedule->remarks }}</td>
                                            <td style="text-align:center;">
                                                <button class="details-btn view-btn" data-toggle="modal" data-target="#viewModal{{ $schedule->id }}">
                                                    View <i class="icofont-eye"></i>
                                                </button>

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal{{ $schedule->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $schedule->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel{{ $schedule->id }}">View Details</h5>
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
                                                                            <th>Duty</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($schedule->tasks as $task)
                                                                            <tr>
                                                                                <td>{{ $task->period->period_name ?? null }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->start_time)->format('g:i A') ?? null }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->end_time)->format('g:i A') ?? null }}</td>
                                                                                <td>{{ $task->duty->duty_name ?? null}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <!-- Add more details as needed -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('editSchedule', ['id' => $schedule->id]) }}" class="details-btn">
                                                    Edit <i class="icofont-arrow-right"></i>
                                                </a>

                                                <form action="{{ route('deleteSchedule2', ['id' => $schedule->id]) }}" method="POST" style="display: inline;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="details-btn delete-btn" style="margin-left: 10px;">
                                                        Delete <i class="icofont-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach