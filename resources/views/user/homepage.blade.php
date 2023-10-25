@extends('layouts.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
          <div class="row" style="display: flex;justify-content:center;flex-direction: column;align-items: center;flex-wrap: wrap;gap: 20px;">

            <form action="{{ route('clock_in') }}" method="POST" id="clockForm">
                @csrf
                <input type="hidden" name="status" value="Clock In"> <!-- Initial value -->
                <button type="submit" id="clockButton" class="btn" style="width:100%">
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
                                      $shiftInfo = $schedule->shift->shift_start->format('h:i A') . ' - ' . $schedule->shift->shift_end->format('h:ia');
                                  }
                                  @endphp
          
                                  <tr>
                                      <td>{{ $currentDate->format('d F Y') }}</td>
                                      <td>
                                          <div style="text-align: center;">
                                              {{ $shiftInfo }}
                                          </div>
                                      </td>
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
                              <tr>
                                <td>{{ $punchRecord->user->full_name }}</td>
                                <td>{{ $recordDate }}</td>
                                <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->toTimeString() }}</td>
                                <td>{{ $punchRecord->in }}</td>
                                <td>{{ $punchRecord->out }}</td>
                              </tr>
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

<script>
  // Get references to the button, form, and the status input field.
  const clockButton = document.getElementById('clockButton');
  const clockForm = document.getElementById('clockForm');
  const statusInput = clockForm.querySelector('input[name="status"]');

  // Add a click event listener to the button.
  clockButton.addEventListener('click', async function (e) {
    e.preventDefault(); // Prevent the default form submission.

    // Toggle between "Clock In" and "Clock Out" in the button.
    if (statusInput.value === 'Clock In') {
      clockButton.innerText = 'Clock Out';
      clockButton.style.backgroundColor = '#FFFFFF';
      clockButton.style.color = '#6045E2';
      clockButton.style.border = '2px solid #6045E2';
    } else {
      clockButton.innerText = 'Clock In';
      clockButton.style.backgroundColor = '#6045E2';
      clockButton.style.color = '#FFFFFF';
      clockButton.style.border = 'none';
    }

    // Update the "status" input field based on the button text.
    statusInput.value = clockButton.innerText;

    // Add the CSRF token to the form data.
    const formData = new FormData(clockForm);
    formData.append('_token', '{{ csrf_token() }}');

    // Submit the form with the updated form data.
    await fetch('{{ route('clock_in') }}', {
      method: 'POST',
      body: formData,
    })
      .then((response) => {
        if (response.ok) {
          // Display a success alert with a dynamic message based on Clock In/Out.
          const successMessage =
            statusInput.value === 'Clock In'
              ? 'You have successfully clocked out.'
              : 'You have successfully clocked in.';
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: successMessage,
          });
        } else {
          // Display an error alert in case of a failure.
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your request.',
          });
        }
      })
      .catch((error) => {
        // Handle any network errors or exceptions here.
        console.error('Error:', error);
      });
  });
</script>

@endsection