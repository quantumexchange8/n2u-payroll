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
                            <h4 class="font-20 ">Attendance</h4>

                            <div class="d-flex flex-wrap">
                                <!-- Date Picker -->
                                <div class="dashboard-date style--six mr-20 mt-3 mt-sm-0">
                                     <input type="date" id="date-filter" value=""/>
                                 </div>
                                <!-- End Date Picker -->


                                <!-- Dropdown Button -->
                                <div class="dropdown-button mt-3 mt-sm-0">
                                    <button class="btn style--two orange" type="button" id="filter-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Status <i class="icofont-simple-down"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="filter-dropdown">
                                        <a class="dropdown-item" href="#" data-status="all">All</a>
                                        <a class="dropdown-item" href="#" data-status="On-Time">On-Time</a>
                                        <a class="dropdown-item" href="#" data-status="Late">Late</a>
                                        <a class="dropdown-item" href="#" data-status="Overtime">Overtime</a>
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
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Invoice List Table -->
                        <table class="text-nowrap bg-white dh-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($punchRecords as $punchRecord)
                                    @php
                                        $recordDate = Carbon\Carbon::parse($punchRecord->created_at)->toDateString();
                                        // $currentDate = now()->toDateString();
                                    @endphp

                                    {{-- @if ($recordDate == $currentDate) --}}
                                    @if ($recordDate)
                                        <tr class="status-{{ $punchRecord->status }}" data-date="{{ $recordDate }}">
                                            <td>{{ Carbon\Carbon::parse($punchRecord->created_at)->format('d M Y') }}</td>
                                            <td>{{$punchRecord->user->nickname}}</td>
                                            <td>
                                                @if ($punchRecord->in == 'Clock In')
                                                    {{ \Carbon\Carbon::parse($punchRecord->clock_in_time)->format('g:i A') }}
                                                @elseif ($punchRecord->out == 'Clock Out')
                                                    {{ \Carbon\Carbon::parse($punchRecord->clock_out_time)->format('g:i A') }}
                                                @else
                                                    Not Available
                                                @endif
                                            </td>
                                            <td>{{$punchRecord->in}}</td>
                                            <td>{{$punchRecord->out}}</td>
                                            <td style ="{{ $punchRecord->status === 'Overtime' ? 'color: orange; font-weight: bold;' : ($punchRecord->status === 'On-Time' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                                {{$punchRecord->status}}
                                            </td>
                                            {{-- <td>
                                                <a href="{{ route('editAttendance', ['id' => $punchRecord->id]) }}" class="details-btn">
                                                    Edit <i class="icofont-arrow-right"></i>
                                                </a>
                                            </td> --}}
                                            <td>
                                                <a href="#" class="edit-attendance details-btn"
                                                   data-toggle="modal"
                                                   data-target="#editAttendanceModal"
                                                   data-id="{{ $punchRecord->id }}">
                                                    Edit <i class="icofont-arrow-right"></i>
                                                </a>
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

<div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAttendanceForm">
                <div class="modal-body">
                    <div class="form-group" id="clockInGroup">
                        <label for="clockIn">Clock In:</label>
                        <input type="time" class="form-control" id="clockIn" name="clock_in_time">
                    </div>
                    <div class="form-group" id="clockOutGroup">
                        <label for="clockOut">Clock Out:</label>
                        <input type="time" class="form-control" id="clockOut" name="clock_out_time">
                    </div>
                    <div class="form-group" id="statusGroup">
                        <label for="status">Status:</label>
                        <input type="text" class="form-control" id="status" name="status">
                    </div>
                    <!-- Additional form fields can be added here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

{{-- Filter by status --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the filter dropdown items
        const filterItems = document.querySelectorAll('.dropdown-item[data-status]');

        // Output the filterItems to the console to check if the selection is correct
        console.log(filterItems);

        // Add a click event listener to each filter item
        filterItems.forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                const selectedStatus = this.dataset.status;
                const tableRows = document.querySelectorAll('.dh-table tbody tr');

                // Output the selected status to the console to check if it's correct
                console.log(selectedStatus);

                // Iterate through table rows and hide/show based on the selected filter
                tableRows.forEach(function(row) {
                    const status = row.classList[0]; // Get the status class of the row
                    if (selectedStatus === 'all' || status === 'status-' + selectedStatus) {
                        row.style.display = ''; // Show the row
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            });
        });
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
        for (let i = 0; i < headerCells.length; i++) {
            headerData.push(headerCells[i].textContent.trim());
        }
        filteredData.push(headerData);

        // Iterate through table rows and cells to collect filtered data
        const tableRows = table.querySelectorAll('tbody tr');
        tableRows.forEach(function(row) {
            if (row.style.display !== 'none') {
                const rowData = [];
                const cells = row.cells;
                for (let i = 0; i < cells.length; i++) {
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
        XLSX.writeFile(workbook, `${currentFormattedDate}-Attendance.xlsx`);
    }
</script>

{{-- Edit clock in and clock out time --}}
<script>
    $(document).ready(function() {

        var attendanceData;

        $('.edit-attendance').click(function() {
            var id = $(this).data('id');

            // Set the data-id attribute on the form
            $('#editAttendanceForm').data('id', id);

            // Fetch data via AJAX
            $.ajax({
                url: '/getAttendanceData/' + id,
                method: 'GET',
                success: function(data) {
                    console.log('Retrieved Data:', data);
                    attendanceData = data;

                    // Update modal content with fetched data
                    var modalBody = $('#editAttendanceModal').find('.modal-body');
                    modalBody.find('.form-group').hide(); // Hide all form groups initially

                    if (data.clock_in_time !== null) {
                        var clockInTime = new Date(data.clock_in_time);
                        var hours = clockInTime.getHours().toString().padStart(2, '0');
                        var minutes = clockInTime.getMinutes().toString().padStart(2, '0');
                        var formattedClockInTime = hours + ':' + minutes;

                        modalBody.find('#clockIn').val(formattedClockInTime);
                        modalBody.find('#clockInGroup').show();
                    }

                    if (data.clock_out_time !== null) {
                        var clockOutTime = new Date(data.clock_out_time);
                        var hours = clockOutTime.getHours().toString().padStart(2, '0');
                        var minutes = clockOutTime.getMinutes().toString().padStart(2, '0');
                        var formattedClockOutTime = hours + ':' + minutes;

                        modalBody.find('#clockOut').val(formattedClockOutTime);
                        modalBody.find('#clockOutGroup').show();
                    }


                    // Try to find and show the status group
                    var statusGroup = modalBody.find('#statusGroup');
                    if (statusGroup.length > 0) {
                        statusGroup.show();
                    } else {
                        console.error('Status group not found');
                    }

                    modalBody.find('#status').val(data.status);

                    // If both clock_in_time and clock_out_time are null, you can add a default message
                    if (data.clock_in_time === null && data.clock_out_time === null) {
                        modalBody.append('<p>No clock-in or clock-out data available</p>');
                    }

                    $('#editAttendanceModal').modal('show');

                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });

        // Handle form submission
        $('#editAttendanceForm').submit(function(event) {
            event.preventDefault();

            var id = $(this).data('id');

            console.log(id);

            // Get the CSRF token value from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Check if attendanceData is defined
            if (attendanceData) {
                // Extract the date part from the existing clock_in_time
                var existingClockInDate = attendanceData.clock_in_time ? attendanceData.clock_in_time.split(' ')[0] : null;

                // Extract the date part from the existing clock_out_time
                var existingClockOutDate = attendanceData.clock_out_time ? attendanceData.clock_out_time.split(' ')[0] : null;

                // Extract the hours and minutes part from the new clockIn input
                var newInTime = $('#clockIn').val();

                // Extract the hours and minutes part from the new clockOut input
                var newOutTime = $('#clockOut').val();

                // Combine the existing date part with the new hours and minutes if not null
                var newClockInTime = existingClockInDate && newInTime ? existingClockInDate + ' ' + newInTime + ':00' : null;
                var newClockOutTime = existingClockOutDate && newOutTime ? existingClockOutDate + ' ' + newOutTime + ':00' : null;

                // Log the data to be sent
                console.log('Data to be sent:', {
                    clock_in_time: newClockInTime,
                    clock_out_time: newClockOutTime,
                    status: $('#status').val(),
                    _token: csrfToken
                });

                // Perform AJAX request to update the data
                $.ajax({
                    url: '/admin/update-attendance/' + id, // Replace with your update route
                    method: 'POST',
                    data: {
                        clock_in_time: newClockInTime,
                        clock_out_time: newClockOutTime,
                        status: $('#status').val(),
                        _token: csrfToken
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle success response
                        console.log('Update success:', response);

                        // Optionally, close the modal or show a success message
                        $('#editAttendanceModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Done',
                            text: 'Successfully Updated',
                        }).then(function() {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        });
                    },
                    error: function(error) {
                        // Handle error response
                        console.error('Update error:', error);
                        // Optionally, display an error message
                    }
                });
            } else {
                console.error('Error: attendanceData is null');
            }
        });
    });

</script>
