@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <h4 class="font-20 ">Attendance</h4>
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
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($punchRecords as $punchRecord)
                                {{-- {{ dd($punchRecord->user) }} --}}
                                    <tr>
                                        <td>{{$punchRecord->employee_id}}</td>
                                        <td>{{$punchRecord->user->full_name}}</td>
                                        <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->toDateString() }}</td>
                                        <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->toTimeString() }}</td>
                                        <td>{{$punchRecord->in}}</td>
                                        <td>{{$punchRecord->out}}</td>
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