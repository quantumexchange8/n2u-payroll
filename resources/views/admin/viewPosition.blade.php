@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Position Table</h4>
                            <div class="d-flex flex-wrap">
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                            <a href="{{route('createPosition')}}" class="btn long">Create</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr>
                                    <th>Position Name</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td>{{ $position->position_name }}</td>
                                        <td>{{ $position->department->department_name }}</td>
                                        <td>
                                            <a href="{{ route('editPosition', ['id' => $position->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deletePosition', ['id' => $position->id]) }}" method="POST" style="display: inline;">
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