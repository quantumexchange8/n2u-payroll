@extends('layouts.master')
@section('content')

{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
        <div class="row" style="display: flex;justify-content:center;flex-direction: column;align-items: center;flex-wrap: wrap;gap: 20px;">

            <form action="{{ route('clock_in') }}" method="POST" id="clockForm">
                @csrf
                <input type="hidden" id="statusInput" name="status" value="Clock In">
                @php
                    $shift = App\Models\Schedule::where('employee_id', Auth::user()->id)
                    ->whereDate('date', now()->toDateString()) // Filter by the current date
                    ->get();

                @endphp
                {{-- @if($shift->isEmpty())
                        <button type="button" id="clockButton" class="btn" style="width:100%">
                            {{$clock}}
                        </button>
                @else
                    <button type="button" id="clockButton" class="btn" style="width:100%;
                    @if ($status == 1)
                        background-color: #6045E2;
                        color: #FFFFFF;
                        border: 2px solid #6045E2;
                    @else
                        background-color: #b04654;
                        color: #FFFFFF;
                        border: 2px solid #b04654;
                    @endif
                    ">
                        {{ $status == 1 ? 'Clock In' : 'Clock Out' }}
                    </button>
                @endif --}}

                <button type="button" id="clockButton" class="btn" style="width:100%;
                @if ($status == 1)
                    background-color: #6045E2;
                    color: #FFFFFF;
                    border: 2px solid #6045E2;
                @else
                    background-color: #b04654;
                    color: #FFFFFF;
                    border: 2px solid #b04654;
                @endif
                ">
                    {{ $status == 1 ? 'Clock In' : 'Clock Out' }}
                </button>

            </form>

            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <h4 class="font-20">Today's Shift</h4>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <div id="data-container">
                            <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Get the current date
                                    $currentDate = \Carbon\Carbon::now();

                                    // Filter schedules for today
                                    $todaySchedules = $schedules->filter(function ($schedule) use ($currentDate) {
                                        $scheduleDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->date);
                                        // Filter out schedules that match the current date
                                        return $scheduleDate->isSameDay($currentDate);
                                    });

                                    // Sort today's schedules by shift start time
                                    $todaySchedules = $todaySchedules->sortBy(function ($schedule) {
                                        // Check if 'shift' relationship is null before accessing 'shift_start'
                                        $shiftStart = optional($schedule->shift)->shift_start;

                                        // Handle cases where shift_start is null
                                        if ($shiftStart === null) {
                                            return ''; // You can use an empty string or another default value
                                        }

                                        // Calculate shift start time
                                        return \Carbon\Carbon::parse($shiftStart)->format('H:i');
                                    });
                                @endphp

                                    @foreach ($todaySchedules as $schedule)
                                    <tr>
                                        <td>{{ $currentDate->format('d M Y') }}</td>
                                        <td>
                                            @if ($schedule->off_day == 1)
                                                Off Day
                                            @else
                                                {{ \Carbon\Carbon::parse($schedule->shift->shift_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->shift->shift_end)->format('h:i A') }}
                                            @endif
                                        </td>
                                        <td>{{ $schedule->remarks }}</td>
                                    </tr>
                                    @endforeach
                            </tbody>
                            </table>
                        </div>

                        <!-- End Invoice List Table -->
                    </div>

                </div>
            </div>

            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <h4 class="font-20">Today's Task</h4>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <div id="data-container">
                        <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Task</th>
                                    <th>Time</th>
                                    <th>Duty</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $todayTasks = $tasks->filter(function ($task) {
                                    // Check if the task date is today
                                    return \Carbon\Carbon::parse($task->date)->isToday();
                                });
                            @endphp

                                @foreach ($todayTasks as $task)
                                    @php
                                    $time = null;
                                    if ($task->start_time && $task->end_time) {
                                        $startTime = new DateTime($task->start_time);
                                        $endTime = new DateTime($task->end_time);
                                        $time = $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A');
                                    }
                                    @endphp

                                    <tr>
                                        <td>{{ $task->period->period_name ?? null }}</td>
                                        <td>{{ $time ?? null }}</td>
                                        <td>{{ $task->duty->duty_name ?? null }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        <!-- End Invoice List Table -->
                    </div>
                </div>
            </div>

            @php
                $user = Auth::user();
                $user_id = $user ? $user->id : null;
                $currentDate = now()->toDateString();
            @endphp

            <div class="col-12">
              <div class="card mb-30">
                  <div class="card-body pt-30">
                      <h4 class="font-20 ">Clock In and Clock Out</h4>
                  </div>
                  <div class="table-responsive">
                      <!-- Invoice List Table -->
                      <table id="punchRecordsTable" class="text-nowrap table-bordered dh-table">
                        <thead>
                            <tr style="text-align: center;">
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>In</th>
                                <th>Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($punchRecords as $punchRecord)
                                @php
                                    $recordDate = Carbon\Carbon::parse($punchRecord->created_at)->toDateString();
                                    $currentDate = now()->toDateString();
                                @endphp
                                @if ($recordDate == $currentDate)
                                    @if ($punchRecord->user->id === $user_id)
                                        <tr>
                                            <td>{{ $punchRecord->user->full_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($recordDate)->format('d M Y') }}</td>
                                            <td>
                                                @if ($punchRecord->in == 'Clock In')
                                                    {{ \Carbon\Carbon::parse($punchRecord->clock_in_time)->format('g:i A') }}
                                                @elseif ($punchRecord->out == 'Clock Out')
                                                    {{ \Carbon\Carbon::parse($punchRecord->clock_out_time)->format('g:i A') }}
                                                @else
                                                    Not Available
                                                @endif
                                            </td>
                                            <td>{{ $punchRecord->in }}</td>
                                            <td>{{ $punchRecord->out }}</td>
                                        </tr>
                                    @endif
                                @endif
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

{{-- Clock in --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  // Get references to the button and form.
  const clockButton = document.getElementById('clockButton');
  const clockForm = document.getElementById('clockForm');

  // Add a click event listener to the button.
  clockButton.addEventListener('click', async function (e) {
    e.preventDefault(); // Prevent the default form submission.

    // Get the current button text.
    const buttonText = clockButton.innerText;

    const userStatus = clockButton.getAttribute('data-status');

    // Determine the new status value.
    const status = buttonText === 'Clock In' ? 'Clock In' : 'Clock Out';

    // Update the form input with the new status.
    const statusInput = document.getElementById('statusInput');
    statusInput.value = status;

     // Disable the button.
    //  clockButton.disabled = true;

    // Use try-catch to handle form submission errors.
    try {
        // if ('{!! $shift->isEmpty() !!}') {
        //     // Display an error alert if $shift is empty.
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Error',
        //         text: 'You cannot clock in or out because there is no scheduled shift for today. Please contact admin.',
        //     });
        //     return; // Exit the function to prevent further execution.
        // }

        const response = await fetch('{{ route('clock_in') }}', {
            method: 'POST',
            body: new FormData(clockForm),
        });

        if (response.ok) {
            // Update the button text to the opposite.
            clockButton.innerText = status === 'Clock In' ? 'Clock Out' : 'Clock In';

            // Apply styles based on the status
            console.log(status)
            if (status === 'Clock In') {
            clockButton.style.backgroundColor = '#b04654';
            clockButton.style.color = '#FFFFFF';
            clockButton.style.border = '2px solid #b04654';
            } else {
            clockButton.style.backgroundColor = '#6045E2';
            clockButton.style.color = '#FFFFFF';
            clockButton.style.border = '2px solid #6045E2';
            }

            // Display a success alert
            Swal.fire({
            icon: 'success',
            title: 'Success',
            text: status === 'Clock In' ? 'You have successfully clocked in.' : 'You have successfully clocked out.',
            }).then((result) => {
                if (result.isConfirmed) {

                // Refresh the page
                location.reload(); // This will reload the current page
                }
            });

        } else {
            // Display an error alert
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Form submission failed. An error occurred while processing your request.',
            });
        }
    } catch (error) {
      console.error('Error:', error);
    }

    // Set a timeout to enable the button after 5 minutes.
    // setTimeout(function () {
    //     clockButton.disabled = false;
    // }, 60000); // 300,000 milliseconds = 5 minutes

  });

</script>

{{-- <script>
  $(document).ready(function() {
        // Function to set the disabled state based on the status
        function setButtonState() {
            var status = $('#statusInput').val();
            console.log('Initial Status:', status);

            if (status == 1) {
                // Clock In is allowed
                $('#clockButton').prop('disabled', false);
            } else {
                // Clock Out is not allowed, disable for 5 minutes
                $('#clockButton').prop('disabled', true);

                // Enable the button after 5 minutes (300,000 milliseconds)
                setTimeout(function() {
                    $('#clockButton').prop('disabled', false);
                    console.log('Button enabled.');
                }, 300000);
            }
        }

        // Call the function to set the initial button state
        setButtonState();

        // Add an event listener for the button click
        $('#clockButton').on('click', function() {
            // Update the status to "Clock Out" (status 2) and send it to the server
            // $('#statusInput').val(2);
            // $('#clockForm').submit();

            // Disable the button and update the server
            $('#clockButton').prop('disabled', true);

            // Enable the button after 5 minutes
            setTimeout(function() {
                $('#clockButton').prop('disabled', false);
                // Update the status to "Clock In" (status 1) and send it to the server
                // $('#statusInput').val(1);
                // $('#clockForm').submit();
            }, 300000);
        });
  });
</script> --}}


{{-- <script>
  $(document).ready(function() {
      // Function to fetch and update data
      function fetchData() {
          $.ajax({
              url: '{{ route('getdata') }}', // Replace with your actual API endpoint
              method: 'GET', // Use GET or POST as appropriate
              dataType: 'json',
              success: function(data) {
                  // Clear the table body
                  $('#punchRecordsTable tbody').empty();

                  // Iterate through the fetched data and append rows to the table
                  $.each(data, function(index, punchRecord) {
                      var row = '<tr>' +
                          '<td>' + punchRecord.user.full_name + '</td>' +
                          '<td>' + punchRecord.date + '</td>' +
                          '<td>' + punchRecord.time + '</td>' +
                          '<td>' + punchRecord.in + '</td>' +
                          '<td>' + punchRecord.out + '</td>' +
                          '</tr>';
                      $('#punchRecordsTable tbody').append(row);
                  });
              },
              error: function() {
                  console.log('Failed to fetch data.');
              }
          });
      }

      // Call fetchData function initially to load data on page load
      fetchData();

      // Set up a timer to refresh data every N seconds (e.g., every 30 seconds)
      setInterval(fetchData, 30000); // 30,000 milliseconds = 30 seconds
  });
</script> --}}

@endsection


