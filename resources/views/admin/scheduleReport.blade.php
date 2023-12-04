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
                            <h4 class="font-20 ">Schedule Summary</h4>

                            <div class="d-flex flex-wrap">
                                <!-- Date Picker -->
                                <div class="dashboard-date style--six mr-20 mt-3 mt-sm-0">
                                     <input type="date" id="date-filter" value=""/>
                                 </div>
                                <!-- End Date Picker -->


                                <!-- Dropdown Button -->
                                <div class="dropdown-button mt-3 mt-sm-0">
                                    <button class="btn style--two orange" type="button" id="user-filter-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        All Users <i class="icofont-simple-down"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="user-filter-dropdown">
                                        <a class="dropdown-item" href="#" data-user-filter="all" data-full-name="All Users">All Users</a>
                                        @foreach ($users as $user)
                                            <a class="dropdown-item" href="#" data-user-filter="{{ $user->id }}" data-full-name="{{ $user->nickname }}">{{ $user->nickname }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- End Dropdown Button -->

                                <!-- Dropdown Button -->
                                <div  class="dropdown-button mt-3 mt-sm-0  ml-2">
                                    <button class="btn style--two orange" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Export <i class="icofont-simple-down"></i>
                                    </button>

                                    <div id="exportButton" class="dropdown-menu" aria-labelledby="filter-dropdown">
                                        <a class="dropdown-item" href="#" data-export="excel">Excel</a>
                                    </div>
                                </div>
                                <!-- End Dropdown Button -->

                                <div class="col-md-1">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                            <a href="{{route('createSchedule')}}" class="btn long">Create</a>
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
                                    <th>Date</th>
                                    <th>Nickname</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($schedule->date)->toDateString();
                                        $employeeName = $schedule->user->nickname
                                    @endphp

                                    @if ($recordDate)
                                        <tr data-date="{{ $recordDate }}" data-full-name="{{ $employeeName }}">
                                            <td>{{ Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</td>
                                            <td>{{ $schedule->user->nickname }}</td>
                                            {{-- <td>{{ $schedule->shift->shift_start ?? null }}</td> --}}
                                            {{-- <td>{{ $schedule->shift->shift_end ?? null}}</td> --}}
                                            <td>
                                                @if ($schedule->shift && $schedule->shift->shift_start)
                                                    {{ Carbon\Carbon::parse($schedule->shift->shift_start)->format('g:i A') }}
                                                @else
                                                     <!-- or any other message you want to display -->
                                                @endif
                                            </td>
                                            <td>
                                                @if ($schedule->shift && $schedule->shift->shift_end)
                                                    {{ Carbon\Carbon::parse($schedule->shift->shift_end)->format('g:i A') }}
                                                @else

                                                @endif
                                            </td>
                                            <td>{{ $schedule->remarks }}</td>
                                            <td>
                                                <button class="details-btn view-btn" data-toggle="modal" data-target="#viewModal{{ $schedule->id }}">
                                                    View <i class="icofont-eye"></i>
                                                </button>

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal{{ $schedule->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $schedule->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel{{ $schedule->id }}">View Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Period</th>
                                                                            <th>Start Time</th>
                                                                            <th>End Time</th>
                                                                            <th>Duty Name</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($schedule->tasks as $task)
                                                                            <tr>
                                                                                <td>{{ $task->period->period_name }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->start_time)->format('g:i A') }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->end_time)->format('g:i A') }}</td>
                                                                                <td>{{ $task->duty->duty_name ?? null}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <!-- Add more details as needed -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('editSchedule', ['id' => $schedule->id]) }}" class="details-btn">
                                                    Edit <i class="icofont-arrow-right"></i>
                                                </a>

                                                <form action="{{ route('deleteSchedule2', ['id' => $schedule->id]) }}" method="POST" style="display: inline;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="details-btn delete-btn" style="margin-left: 10px;">
                                                        Delete <i class="icofont-trash"></i>
                                                    </button>
                                                </form>
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

{{-- Filter by user's full name --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableRows = document.querySelectorAll('.dh-table tbody tr');
        const dropdownItems = document.querySelectorAll('.dropdown-item[data-full-name]');

        dropdownItems.forEach(function(item) {
            item.addEventListener('click', filterTable);
        });

        function filterTable(event) {
            const selectedFullName = event.target.getAttribute('data-full-name');

            tableRows.forEach(function(row) {
                const fullName = row.dataset.fullName;

                if (!selectedFullName || selectedFullName === 'All Users' || fullName === selectedFullName) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });

            console.log('Selected Full Name:', selectedFullName);
        }
    });
</script>

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

{{-- Export Excel --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('exportButton').addEventListener('click', function() {
            exportFilteredDataToExcel();
        });
    });

    function getCurrentFormattedDate() {
        const currentDate = new Date();
        const day = currentDate.getDate();
        const monthNames = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        const month = monthNames[currentDate.getMonth()];
        const year = currentDate.getFullYear();
        return `${day}${month}${year}`;
    }

    function exportFilteredDataToExcel() {
        // Get the table element by its class or ID
        const table = document.querySelector('.dh-table');

        // Define an array to store the filtered table data
        const filteredData = [];

        // Get the table header (column names)
        const headerRow = table.querySelector('thead tr');
        const headerData = [];
        const headerCells = headerRow.cells;
        for (let i = 0; i < headerCells.length - 1; i++) {
            headerData.push(headerCells[i].textContent.trim());
        }
        filteredData.push(headerData);

        // Iterate through table rows and cells to collect filtered data
        const tableRows = table.querySelectorAll('tbody tr');
        tableRows.forEach(function(row) {
            if (row.style.display !== 'none') {
                const rowData = [];
                const cells = row.cells;
                for (let i = 0; i < cells.length - 1; i++) {
                    rowData.push(cells[i].textContent.trim());
                }
                filteredData.push(rowData);
            }
        });

        // Create a new workbook and add a worksheet
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.aoa_to_sheet(filteredData);

        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

        // Generate the Excel file and trigger a download with the current date in the filename
        const currentFormattedDate = getCurrentFormattedDate();
        XLSX.writeFile(workbook, `${currentFormattedDate}-Schedule.xlsx`);
    }
</script>

