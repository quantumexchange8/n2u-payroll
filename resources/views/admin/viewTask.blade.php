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
                            <h4 class="font-20 ">Task Table</h4>

                            <div class="d-flex flex-wrap">
                                <!-- Date Picker -->
                                <div class="dashboard-date style--six mr-20 mt-3 mt-sm-0">
                                     <input type="date" id="date-filter" value=""/>
                                 </div>
                                <!-- End Date Picker -->

                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                            <a href="{{route('createTask')}}" class="btn long">Create</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-8">
                            <div class="card-body pt-20">
                                <h4 class="font-20">Task Table</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row" style="margin-top: 12px; margin-right: 20px;">
                                <div class="col-12 text-right">
                                    <a href="{{route('createTask')}}" class="btn long">Create</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nickname</th>
                                    <th>Period</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($task->date)->toDateString();
                                    @endphp

                                    <tr data-date="{{ $recordDate }}">
                                        <td>{{ Carbon\Carbon::parse($task->date)->format('d M Y') }}</td>
                                        <td>{{ $task->user->nickname }}</td>
                                        <td>{{ $task->period->period_name ?? null }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->start_time )->format('g:i A') ?? null }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') ?? null }}</td>
                                        <td>{{ $task->duty->duty_name ?? null }}</td>
                                        <td>
                                            <a href="{{ route('editTask', ['id' => $task->id]) }}" class="details-btn">
                                                Edit <i class="icofont-arrow-right"></i>
                                            </a>
                                            <form action="{{ route('deleteTask', ['id' => $task->id]) }}" method="POST" style="display: inline;">
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

{{-- Filter by date --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateFilter = document.getElementById('date-filter');
        const tableRows = document.querySelectorAll('.dh-table tbody tr');

        dateFilter.addEventListener('input', filterTable);

        function filterTable() {
            const selectedDate = dateFilter.value;

            tableRows.forEach(function(row) {
                const date = row.dataset.date; // You'll need to set the data-date attribute in your table rows

                if (!selectedDate || date === selectedDate) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }
    });
</script>
