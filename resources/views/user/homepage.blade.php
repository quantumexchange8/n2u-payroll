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
              <button type="button" id="clockButton" class="btn" style="width:100%">
                Clock In
              </button>
            </form>

            <div class="col-12">
              <div class="card mb-30">
                  <div class="card-body pt-30">
                      <h4 class="font-20">Today</h4>
                  </div>
                  <div class="table-responsive">
                      <!-- Invoice List Table -->
                      <table class="text-nowrap table-bordered dh-table">
                          <thead>
                              <tr style="text-align: center;">
                                  <th>Date</th>
                                  <th>Shift</th>
                                  <th>Duty</th>
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
                              @endphp
          
                              @foreach ($todaySchedules as $schedule)
                                  @php
                                  $shiftInfo = null;
                                  if ($schedule->shift && $schedule->shift->shift_start && $schedule->shift->shift_end) {
                                      $shiftStart = new DateTime($schedule->shift->shift_start);
                                      $shiftEnd = new DateTime($schedule->shift->shift_end);
                                      $shiftInfo = $shiftStart->format('h:i A') . ' - ' . $shiftEnd->format('h:i A');
                                  }
                                  @endphp
          
                                  <tr>
                                      <td>{{ $currentDate->format('d F Y') }}</td>
                                      <td>{{ $shiftInfo }}</td>
                                      <td>{{ $schedule->duty->duty_name }}</td>
                                      <td>{{ $schedule->remarks}}</td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
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
                      <table class="text-nowrap table-bordered dh-table">
                        <thead>
                          <tr>
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
                                        <td>{{ $recordDate }}</td>
                                        <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->format('g:i A') }}</td>
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
<script>
  // Get references to the button and form.
  const clockButton = document.getElementById('clockButton');
  const clockForm = document.getElementById('clockForm');

  // Add a click event listener to the button.
  clockButton.addEventListener('click', async function (e) {
    e.preventDefault(); // Prevent the default form submission.

    // Get the current button text.
    const buttonText = clockButton.innerText;

    // Determine the new status value.
    const status = buttonText === 'Clock In' ? 'Clock In' : 'Clock Out';

    // Update the form input with the new status.
    const statusInput = document.getElementById('statusInput');
    statusInput.value = status;

    // Use try-catch to handle form submission errors.
    try {
      const response = await fetch('{{ route('clock_in') }}', {
        method: 'POST',
        body: new FormData(clockForm),
      });

      if (response.ok) {
        // Update the button text to the opposite.
        clockButton.innerText = status === 'Clock In' ? 'Clock Out' : 'Clock In';

        // Apply styles based on the status
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
  });
</script>

@endsection


