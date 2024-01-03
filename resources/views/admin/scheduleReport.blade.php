@extends('layouts.master')
@section('content')

{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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

                                <div class="col-md-2">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <a href="{{route('createSchedule')}}" class="btn long">Create</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <a href="#" class="btn long duplicate-btn">Copy</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap invoice-list">
                            <thead>
                                <tr>
                                    <th>
                                        <!-- Custom Checkbox -->
                                        <label class="custom-checkbox">
                                            <input type="checkbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Remarks</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($schedule->date)->toDateString();
                                        $employeeName = $schedule->user->nickname
                                    @endphp

                                    @if ($recordDate)
                                        <tr data-schedule-id="{{ $schedule->id }}" data-tasks="{{ json_encode($schedule->tasks) }}" data-date="{{ $recordDate }}" data-full-name="{{ $schedule->user->nickname }}">
                                            <td>
                                                <!-- Custom Checkbox -->
                                                <label class="custom-checkbox">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <!-- End Custom Checkbox -->
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</td>
                                            <td>{{ $schedule->user->nickname }}</td>
                                            <td>
                                                @if ($schedule->shift && $schedule->shift->shift_start)
                                                    {{ Carbon\Carbon::parse($schedule->shift->shift_start)->format('g:i A') }}
                                                @else
                                                    <b>Off Day</b>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($schedule->shift && $schedule->shift->shift_end)
                                                    {{ Carbon\Carbon::parse($schedule->shift->shift_end)->format('g:i A') }}
                                                @else
                                                    <b>Off Day</b>
                                                @endif
                                            </td>
                                            <td>{{ $schedule->remarks }}</td>
                                            <td style="text-align:center;">
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
                                                                            <th>Duty</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($schedule->tasks as $task)
                                                                            <tr>
                                                                                <td>{{ $task->period->period_name ?? null }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->start_time)->format('g:i A') ?? null }}</td>
                                                                                <td>{{ Carbon\Carbon::parse($task->end_time)->format('g:i A') ?? null }}</td>
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
        const tableRows = document.querySelectorAll('.invoice-list tbody tr');
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
        }
    });
</script>

{{-- Filter by date --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateFilter = document.getElementById('date-filter');
        const tableRows = document.querySelectorAll('.invoice-list tbody tr');

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
        const table = document.querySelector('.invoice-list');

        // Define an array to store the filtered table data
        const filteredData = [];

        // Get the table header (column names)
        const headerRow = table.querySelector('thead tr');
        const headerData = [];
        const headerCells = headerRow.cells;
        for (let i = 1; i < headerCells.length - 1; i++) {
            headerData.push(headerCells[i].textContent.trim());
        }
        filteredData.push(headerData);

        // Iterate through table rows and cells to collect filtered data
        const tableRows = table.querySelectorAll('tbody tr');
        tableRows.forEach(function(row) {
            if (row.style.display !== 'none') {
                const rowData = [];
                const cells = row.cells;
                for (let i = 1; i < cells.length - 1; i++) {
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


<!-- Include Bootstrap JS (Popper.js and Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


{{-- Copy Paste Record --}}

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {

        // Retrieve the user full names from the Blade template
        var users = @json($users);

        // Listen for Duplicate button click
        $('.duplicate-btn').click(function() {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Create an array to store selected rows
            var selectedRows = [];

            // Loop through each checkbox
            $('table.invoice-list tbody input[type="checkbox"]:checked').each(function() {
                // Get the parent row
                var row = $(this).closest('tr');

                // Get the data you want to duplicate (modify as needed)
                var scheduleId = row.data('schedule-id');
                var date = row.find('td:eq(1)').text().trim();
                var nickname = row.find('td:eq(2)').text().trim();
                var shiftStart = row.find('td:eq(3)').text().trim();
                var shiftEnd = row.find('td:eq(4)').text().trim();
                var remarks = row.find('td:eq(5)').text().trim();

                // Get tasks data from the data attribute
                var tasks = row.data('tasks');

                // Push the data to the selectedRows array
                selectedRows.push({
                    scheduleId: scheduleId,
                    date: date,
                    nickname: nickname,
                    shiftStart: shiftStart,
                    shiftEnd: shiftEnd,
                    remarks: remarks,
                    tasks: tasks
                });

                console.log('Selected Row:', {
                    scheduleId: scheduleId,
                    date: date,
                    nickname: nickname,
                    shiftStart: shiftStart,
                    shiftEnd: shiftEnd,
                    remarks: remarks,
                    tasks: tasks
                });
            });

            // Create the modal dynamically
            var modalContent = `
                <div class="modal fade" id="userSelectionModal" tabindex="-1" role="dialog" aria-labelledby="userSelectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="userSelectionModalLabel">Select User for Duplication</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2" for="userDropdown">Select User:</label>
                                    <select class="form-control" id="userDropdown" name="selectedUser">
                                        <option value="">Select User</option>
                                        ${users.map(user => `<option value="${user.id}">${user.nickname}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date</label>
                                    <input type="date" class="theme-input-style" id="datePick" name="selectedDate" autocomplete="off">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="duplicateDataBtn">Paste</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Append the modal content to the body
            $('body').append(modalContent);

            // Display the modal for user selection
            $('#userSelectionModal').modal('show');

            // Handle Duplicate Data button click
            $('#duplicateDataBtn').on('click', function() {
                // Retrieve the selected user ID
                var selectedUserId = $('#userDropdown').val();

                var selectedDate = $('#datePick').val();

                // Create an array to store filtered data from selected rows
                var filteredRows = [];

                // Loop through each selected row and filter the data
                selectedRows.forEach(function(row) {
                    // Extract only the necessary data from the row
                    var filteredData = {
                        scheduleId: row.scheduleId,
                        date: row.date,
                        nickname: row.nickname,
                        tasks:row.tasks,
                        // Add other properties you want to include
                    };

                    // Push the filtered data to the array
                    filteredRows.push(filteredData);
                });

                console.log({
                    selectedUserId: selectedUserId,
                    selectedDate: selectedDate,
                    filteredRows: filteredRows,
                });

                // Get the CSRF token from the meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Perform an AJAX request to send the selected user ID to the controller
                $.ajax({
                 url: '{{ route("duplicateSchedule") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        selectedUserId: selectedUserId,
                        selectedDate: selectedDate,
                        filteredRows: filteredRows,
                    },
                    success: function(response) {
                        // Close the user selection modal
                        $('#userSelectionModal').modal('hide');

                         // Display SweetAlert on success (use the promise to ensure the modal is hidden before showing SweetAlert)
                        $('#userSelectionModal').on('hidden.bs.modal', function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Done',
                                text: 'Successfully Duplicated',
                            }).then(function() {
                                // Reload the page after the SweetAlert is closed
                                location.reload();
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle the error response
                        console.error('Error:', xhr.responseText);

                        // Display SweetAlert on error with detailed error message
                        let errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An unexpected error occurred.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        }).then(function() {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        });
                    }

                });

            });

            // Handle Close button click
            $('.modal-header .close, .modal-footer .btn-secondary').on('click', function() {
                // Close the user selection modal
                $('#userSelectionModal').modal('hide');
            });

        });
    });
</script>

