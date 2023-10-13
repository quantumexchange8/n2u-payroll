@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body pt-30">
                        <h4 class="font-20 ">Hoverable Table</h4>
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
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>Christine McDonald</td>
                                    <td>$1564.32</td>
                                    <td>26 November 2019</td>
                                    <td>UX Solutions</td>
                                    <td><a href="invoice-details.html" class="details-btn">View Details <i class="icofont-arrow-right"></i></a></td>
                                </tr> --}}
                                @foreach ($punchRecords as $punchRecord)
                                    <tr>
                                        <td>{{$punchRecord->employee_id}}</td>
                                        <td>{{$punchRecord->users->name}}</td>
                                        <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->toDateString() }}</td>
                                        <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->toTimeString() }}</td>
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