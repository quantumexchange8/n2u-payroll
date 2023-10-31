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
                                <h4 class="font-20">Employee Table</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row" style="margin-top: 12px; margin-right: 20px;">
                                <div class="col-12 text-right ">
                                    <a href="{{Route('createEmployee')}}" class="btn long">Create</a>
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
                                    <th>Employee Type</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->employee_id }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->employee_type ?? null }}</td>
                                        <td>{{ $user->position->position_name ?? null }}</td>
                                        <td>
                                            <a href="{{ route('editEmployee', ['id' => $user->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deleteEmployee', ['id' => $user->id]) }}" method="POST" style="display: inline;">
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