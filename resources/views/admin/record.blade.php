@extends('layouts.master')
@section('content')

{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            {{-- <div class="col-md-6 col-sm-6">
                <!-- Card -->
                <div class="card mb-30 progress_3">
                   <div class="card-body">
                      <h4 class="progress-title">Pending OT Approval</h4>
                        <div class="ProgressBar-wrap position-relative mb-4">
                            <div class="ProgressBar ProgressBar_1" id="progressBar" data-progress="{{ $pendingOTCount2 }}">
                                <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                            </div>
                        </div>

                   </div>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-md-6 col-sm-6">
                <!-- Card -->
                <div class="card mb-30 progress_1">
                   <div class="card-body">
                      <h4 class="progress-title">Total Employees Working Today</h4>
                      <div class="ProgressBar-wrap position-relative mb-4">
                         <div class="ProgressBar ProgressBar_1" data-progress="{{ $totalEmployeesCount }}">
                            {{$totalEmployeesCount}}
                            <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                         </div>
                      </div>

                   </div>
                </div>
                <!-- End Card -->
             </div> --}}

            <div class="col-md-6 col-sm-6" onclick="redirectToOTApproval()">
                <!-- Card -->
                <div class="card mb-30" style="background-color: #cce5ff; border-color: #b8daff;">
                    <div class="card-body">
                        <h4 class="progress-title">Pending OT Approval</h4>
                        <div class="mb-4">
                            <p style="font-size: 30px; color: #004085; font-weight: bold; display: flex; flex-direction: column; align-items: center; justify-content: center;">{{ $pendingOTCount2 }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>


            <div class="col-md-6 col-sm-6">
                <!-- Card -->
                <div class="card mb-30" style="background-color: #d4edda; border-color: #c3e6cb;">
                    <div class="card-body">
                        <h4 class="progress-title">Total Employees Working Today</h4>
                        <div class="mb-4">
                            <p style="font-size: 30px; color: #155724; font-weight: bold; display: flex; flex-direction: column; align-items: center; justify-content: center;">{{ $totalEmployeesCount }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>



            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Today's Attendance</h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap bg-white dh-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($punchRecords as $punchRecord)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($punchRecord->created_at)->toDateString();
                                        $currentDate = now()->toDateString();
                                    @endphp

                                    @if ($recordDate == $currentDate)
                                    {{-- @if ($recordDate) --}}
                                        <tr class="status-{{ $punchRecord->status }}" data-date="{{ $recordDate }}">
                                            <td>{{$punchRecord->user->nickname}}</td>
                                            <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->format('d M Y') }}</td>
                                            <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->format('g:i A') }}</td>
                                            <td>{{$punchRecord->in}}</td>
                                            <td>{{$punchRecord->out}}</td>
                                            <td style="{{ $punchRecord->status === 'Overtime' ? 'color: orange; font-weight: bold;' : ($punchRecord->status === 'On-Time' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                                {{$punchRecord->status}}
                                            </td>
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

@endsection

<script>
    function redirectToOTApproval() {
        window.location.href = '{{ route('otApproval') }}';
    }
</script>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {

        // Get the progress bar element
        var progressBar = document.getElementById('progressBar');

        if (progressBar) {
            // Add a click event listener to the progress bar
            progressBar.addEventListener('click', function() {
                // Get the value of the data-progress attribute
                var progressValue = progressBar.getAttribute('data-progress');

                // Log a message to the console
                console.log('Progress bar clicked!');

                // Log the value of the data-progress attribute to the console
                console.log('Progress value:', progressValue);

                // Redirect to otApproval.blade.php
                window.location.href = 'otApproval';
            });
        } else {
            console.error('Element with id "progressBar" not found.');
        }
    });
</script> --}}
