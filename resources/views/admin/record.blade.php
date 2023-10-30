@extends('layouts.master')
@section('content')

{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Today's Attendance</h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap hoverable dh-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
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
                                            <td>{{$punchRecord->employee_id}}</td>
                                            <td>{{$punchRecord->user->full_name}}</td>
                                            <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->format('d F Y') }}</td>
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
