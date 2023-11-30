@extends('layouts.master')
@section('content')

{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-4 col-md-8">
                <!-- Card -->
                <div class="card mb-30">
                   <div class="card-body" style="height: 290px;">
                      <div class="d-flex align-items-center justify-content-between">
                         <div class="increase">
                            <h3 class="card-subtitle mb-2">Notice Board</h3>
                            <p class="font-16">
                                @if($settings->isNotEmpty() && $settings->where('setting_name', 'Notice Board')->isNotEmpty())
                                    {{ $settings->where('setting_name', 'Notice Board')->first()->value }}
                                @else
                                    Have a nice day.
                                @endif
                            </p>
                         </div>
                      </div>
                   </div>
                </div>
                <!-- End Card -->
            </div>

            <div class="col-md-4 col-sm-6">
                <!-- Card -->
                <div class="card mb-30 progress_3">
                   <div class="card-body">
                      <h4 class="progress-title">Pending OT Approval</h4>

                        <div class="ProgressBar-wrap position-relative mb-4">
                            <div class="ProgressBar ProgressBar_1" data-progress="{{ $pendingOTCount2 }}">
                                <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                <!-- on défini le l'angle et le centre de rotation du cercle -->
                                <circle transform="rotate(135, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="8" />
                                <circle transform="rotate(135, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                </svg>
                                <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                            </div>
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
                        <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr>
                                    <th>Nickname</th>
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
