@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card-body pt-20">
                                <h4 class="font-20">Salary Logs</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row" style="margin-top: 12px; margin-right: 20px;">
                                <div class="col-12 text-right ">
                                    {{-- <a href="{{Route('createEmployee')}}" class="btn long">Create</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap hoverable dh-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Full Name</th>
                                    <th>Basic Salary</th>
                                    <th>Total OT Hour</th>
                                    <th>Total OT Pay</th>
                                    <th>Total Payout</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Actions</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaryLogs as $salaryLog)
                                    <tr>
                                        <td>{{ $salaryLog->employee_id }}</td>
                                        <td>{{ $salaryLog->user->full_name }}</td>
                                        <td>{{ $salaryLog->user->salary }}</td>
                                        <td>{{ $salaryLog->total_ot_hour }}</td>
                                        <td>{{ $salaryLog->total_ot_pay }}</td>
                                        <td>{{ $salaryLog->total_payout }}</td>
                                        <td>{{ $salaryLog->month }}</td>
                                        <td>{{ $salaryLog->year }}</td>
                                        <td>
                                            <a href="{{ route('editSalaryLogs', ['id' => $user->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deleteSalaryLogs', ['id' => $user->id]) }}" method="POST" style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="details-btn delete-btn" style="margin-left: 10px;">
                                                    Delete <i class="icofont-trash"></i>
                                                </button>
                                            </form>
                                        </td>
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