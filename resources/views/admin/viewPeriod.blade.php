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
                            <h4 class="font-20 ">Period Table</h4>
                            <div class="d-flex flex-wrap">
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                            <a href="{{route('createPeriod')}}" class="btn long">Create</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap bg-white dh-table">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periods as $period)
                                    <tr>
                                        <td>{{ $period->period_name }}</td>
                                        <td>
                                            <a href="{{ route('editPeriod', ['id' => $period->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deletePeriod', ['id' => $period->id]) }}" method="POST" style="display: inline;">
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
