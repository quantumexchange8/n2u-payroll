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
                                <h4 class="font-20">Shift Table</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row" style="margin-top: 12px; margin-right: 20px;">
                                <div class="col-12 text-right">
                                    <a href="{{Route('createShift')}}" class="btn long">Create</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap hoverable dh-table">
                            <thead>
                                <tr>
                                    {{-- <th>Shift ID</th> --}}
                                    <th>Shift Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shifts as $shift)
                                    <tr>
                                        {{-- <td>{{ $shift->shift_id }}</td> --}}
                                        <td>{{ $shift->shift_name }}</td>
                                        {{-- <td>{{ $shift->shift_start->format('h:i A') }}</td>
                                        <td>{{ $shift->shift_end->format('h:i A') }}</td> --}}
                                        <td>{{ date('h:i A', strtotime($shift->shift_start)) }}</td>
                                        <td>{{ date('h:i A', strtotime($shift->shift_end)) }}</td>
                                        <td>
                                            <a href="{{ route('editShift', ['id' => $shift->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deleteShift', ['id' => $shift->id]) }}" method="POST" style="display: inline;">
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
