@extends('layouts.master')
@section('content')

{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
{{-- JQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include SweetAlert script here -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Report</h4>

                            <div class="d-flex flex-wrap">
                                <!-- Date Picker -->
                                <div class="dashboard-date style--six mr-20 mt-3 mt-sm-0">
                                    <input type="month" id="month-year-filter" value=""/>
                                </div>
                                <!-- End Date Picker -->


                                <!-- Dropdown Button -->
                                <div class="dropdown-button mt-3 mt-sm-0">
                                    <button class="btn style--two orange" type="button" id="user-filter-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        All Users <i class="icofont-simple-down"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="user-filter-dropdown">
                                        <a class="dropdown-item" href="#" data-user-filter="all" data-nickname="All Users">All Users</a>
                                        @foreach ($users as $user)
                                            <a class="dropdown-item" href="#" data-user-filter="{{ $user->id }}" data-nickname="{{ $user->nickname }}">{{ $user->nickname }}</a>
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
                                        <div class="col-12">
                                            <a href="#" class="btn long recalculate-btn" id="recalculateButton">Recalculate</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="data-table" class="table-responsive">

                        {{-- <table class="text-nowrap table-bordered dh-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Total Work</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->sortBy(function ($user) use ($punchRecords) {
                                    return $punchRecords->where('employee_id', $user->id)->min('created_at');
                                }) as $user)
                                    @php
                                        $userPunchRecords = $punchRecords->where('employee_id', $user->id);
                                        $groupedRecords = $userPunchRecords->groupBy(function ($record) {
                                            return \Carbon\Carbon::parse($record->created_at)->format('d M Y');
                                        });
                                    @endphp

                                    @foreach ($groupedRecords as $date => $records)
                                        <tr data-date="{{ \Carbon\Carbon::parse($date ?? null )->format('Y-m-d') }}" data-full-name="{{ $user->full_name }}">
                                            <td>{{ $date }}</td>
                                            <td>{{ $user->full_name }}</td>
                                            <td>{{ $records->where('in', 'Clock In')->first()->created_at->format('h:i:s A') ?? '' }}</td>
                                            <td>{{ $records->where('out', 'Clock Out')->first()->created_at->format('h:i:s A') ?? '' }}</td>
                                            <td>
                                                @if ($records->count() == 1)
                                                    {{ $records->first()->total_work ?? '' }}
                                                @elseif ($loop->last)
                                                    {{ $records->last()->total_work ?? '' }}
                                                @else
                                                    <!-- Leave this cell empty for intermediate rows -->
                                                @endif
                                            </td>
                                            <td>
                                                @if ($records->count() == 1)
                                                    {{ $records->first()->remarks ?? '' }}
                                                @elseif ($loop->last)
                                                    {{ $records->last()->remarks ?? '' }}
                                                @else
                                                    <!-- Leave this cell empty for intermediate rows -->
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('updateTotalWork', $records->last()->id) }}" method="POST" style="display: flex; justify-content: space-between; margin-top: 15px;" id="update-form-{{$records->last()->id}}">
                                                    @csrf
                                                    <button type="button" class="edit-button details-btn" data-punchrecord-id="{{ $records->last()->id }}">
                                                        Edit <i class="icofont-arrow-right"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table> --}}

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
                                    <th>Shift</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Total Hour</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    @php
                                        $userPunchRecords = $punchRecords->where('employee_id', $user->id)->sortBy('created_at');
                                    @endphp

                                    @php
                                        $combinedRecords = collect([]);
                                        $currentPair = collect([]);
                                    @endphp

                                    @foreach ($userPunchRecords as $record)
                                        @php
                                            // Check if the current record is the start of a new pair
                                            if ($record->status_clock % 2 == 1) {
                                                $currentPair = collect([$record]);
                                            } else {
                                                // If it's an even status_clock, add it to the current pair
                                                $currentPair->push($record);

                                                // If it's the end of a pair, add the pair to the combined records
                                                if ($record->status_clock % 2 == 0) {
                                                    $combinedRecords->push($currentPair);
                                                }
                                            }
                                        @endphp
                                    @endforeach

                                    @foreach ($combinedRecords as $pair)
                                    <tr data-date="{{ \Carbon\Carbon::parse($pair->first()->created_at ?? null )->format('Y-m-d') }}" data-nickname="{{ $user->nickname }}">
                                            {{-- @php
                                                dd($user->nickname);
                                            @endphp --}}
                                            <td>
                                                <!-- Custom Checkbox -->
                                                <label class="custom-checkbox">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <!-- End Custom Checkbox -->
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($pair->first()->created_at)->format('d M Y') }}</td>
                                            <td>{{ $user->nickname }}</td>
                                            {{-- <td>
                                                @php

                                                    $shift = App\Models\Schedule::join('shifts', 'schedules.shift_id', 'shifts.id')
                                                    ->where('employee_id', $user->id)
                                                    ->where('date', \Carbon\Carbon::parse($pair->first()->created_at)->format('Y-m-d')) // Filter by the current date
                                                    ->orderBy('shifts.shift_start')
                                                    ->first();

                                                @endphp

                                                @if ($shift)
                                                    {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                                @endif
                                            </td> --}}

                                            @php
                                                // Determine if the user has two shifts (status_clock 1 and 2 and 3 and 4)
                                                $hasTwoShifts = $pair->first()->status_clock > 2;

                                                // Determine which shift to use based on $hasTwoShifts
                                                $shiftIndex = $hasTwoShifts ? 1 : 0;

                                                // Query the database for the shift
                                                $shift = App\Models\Schedule::join('shifts', 'schedules.shift_id', 'shifts.id')
                                                    ->where('employee_id', $user->id)
                                                    ->where('date', \Carbon\Carbon::parse($pair->first()->created_at)->format('Y-m-d'))
                                                    ->orderBy('shifts.shift_start')
                                                    ->when($hasTwoShifts, function ($query) use ($shiftIndex) {
                                                        // If the user has two shifts, skip to the appropriate shift
                                                        return $query->skip($shiftIndex);
                                                    })
                                                    ->first();
                                            @endphp

                                            <td data-shift-id="{{ $shift->id }}">
                                                @if ($shift)
                                                    {{-- Display shift information, e.g., {{ $shift->id }} --}}
                                                    {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pair->first()->in === 'Clock In' ? \Carbon\Carbon::parse($pair->first()->clock_in_time)->format('h:i A') : '' }}
                                            </td>

                                            <td>
                                                {{ $pair->last()->out === 'Clock Out' ? \Carbon\Carbon::parse($pair->last()->clock_out_time)->format('h:i A') : '' }}
                                            </td>
                                            <td data-punchrecord-id="{{ $pair->last()->id }}">
                                                @if ($pair->last()->status_clock % 2 == 0)
                                                    {{ $pair->last()->total_work }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pair->last()->status_clock % 2 == 0)
                                                    {{ $pair->last()->remarks }}
                                                @endif
                                            </td>

                                            <td>
                                                <form action="{{ route('updateTotalWork', $pair->last()->id) }}" method="POST" style="display: flex; justify-content: space-between; margin-top: 15px;" id="update-form-{{$pair->last()->id}}">
                                                    @csrf
                                                    <button type="button" class="edit-button details-btn" data-punchrecord-id="{{ $pair->last()->id }}">
                                                        Edit <i class="icofont-arrow-right"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
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

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

{{-- Filter by user's full name --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableRows = document.querySelectorAll('.invoice-list tbody tr');
        console.log('Table Rows:', tableRows);
        const dropdownItems = document.querySelectorAll('.dropdown-item[data-nickname]');

        dropdownItems.forEach(function(item) {
            item.addEventListener('click', filterTable);
        });

        function filterTable(event) {
            console.log('Filtering...');

            const selectedNickname = event.target.dataset.nickname;

            console.log('Number of Table Rows:', tableRows.length);

            tableRows.forEach(function(row) {
                console.log('Row data attributes:', row.dataset);
                const nickname = row.dataset.nickname;
                console.log('Row:', row);
                console.log('Nickname:', nickname);


                if (!selectedNickname || selectedNickname === 'All Users' || nickname === selectedNickname) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });

            console.log('Selected Nickname:', selectedNickname);
        }
    });
</script>

{{-- Filter by date --}}
{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateFilter = document.getElementById('date-filter');
        const tableRows = document.querySelectorAll('.dh-table tbody tr');

        dateFilter.addEventListener('input', filterTable);

        function filterTable() {
            const selectedDate = dateFilter.value;

            tableRows.forEach(function(row) {
                const date = row.dataset.date;

                if (!selectedDate || date === selectedDate) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }
    });
</script> --}}

{{-- Filter by month --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthYearFilter = document.getElementById('month-year-filter');
        const tableRows = document.querySelectorAll('.invoice-list tbody tr');

        monthYearFilter.addEventListener('input', filterTable);

        function filterTable() {
            const selectedMonthYear = monthYearFilter.value;

            tableRows.forEach(function(row) {
                const dateAttribute = row.getAttribute('data-date');
                const date = new Date(dateAttribute);
                const monthYear = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0'); // Convert date to "YYYY-MM" format

                if (!selectedMonthYear || monthYear === selectedMonthYear) {
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
        XLSX.writeFile(workbook, `${currentFormattedDate}-Total-Work.xlsx`);
    }
</script>

<!-- JavaScript code for the SweetAlert -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.edit-button').on('click', function() {
            var punchRecord_id = $(this).data('punchrecord-id');
            console.log(punchRecord_id);
            Swal.fire({
                title: 'Edit Remarks',
                input: 'text', // Use a text input
                inputLabel: 'Remark',
                inputPlaceholder: 'Enter your remark...',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    const remark = result.value;
                    if (remark !== null) {
                        // Create a new hidden input field with the new remark
                        const remarkInput = document.createElement('input');
                        remarkInput.type = 'hidden';
                        remarkInput.name = 'remark';
                        remarkInput.value = remark;

                        // Append the input field to the form
                        const form = document.getElementById('update-form-' + punchRecord_id);
                        if (form) {
                            form.appendChild(remarkInput);
                            form.submit();
                        } else {
                            console.error('Form not found: ' + 'update-form-' + punchRecord_id);
                        }
                    }
                }
            });
        });
    });
</script>


{{-- Recalculate the total hour --}}
<script>
    $(document).ready(function() {
        // Handle the Recalculate button click
        $('#recalculateButton').click(function() {

            // Log a message to the console to check if the click event is being triggered
            console.log('Recalculate button clicked');

            // Array to store selected checkbox values
            var selectedRows = [];

            // Loop through all checkboxes
            $('table.invoice-list tbody input[type="checkbox"]:checked').each(function() {
                // Get the value of the checkbox (you may need to adjust this based on your HTML structure)
                var id = $(this).closest('tr').data('id');

                // Get data associated with the selected row
                var rowData = {
                    id: id,
                    date: $(this).closest('tr').data('date'),
                    fullName: $(this).closest('tr').data('full-name'),
                    shift: $(this).closest('tr').find('td:eq(3)').text().trim(),  // Trim whitespace
                    checkIn: $(this).closest('tr').find('td:eq(4)').text().trim(),  // Trim whitespace
                    checkOut: $(this).closest('tr').find('td:eq(5)').text().trim(),  // Trim whitespace
                    totalHour: $(this).closest('tr').find('td:eq(6)').text().trim(),  // Trim whitespace
                    shiftId: $(this).closest('tr').find('td:eq(3)').data('shift-id'),
                    punchRecordId: $(this).closest('tr').find('td:eq(6)').data('punchrecord-id')
                };

                // Add the rowData to the array
                selectedRows.push(rowData);
            });

            // Log the selected rows to the console
            console.log('Selected Rows:', selectedRows);

            // Log the start of the AJAX request
            console.log('Sending AJAX request');

            // Send an AJAX request to your server with the selected checkbox ids
            $.ajax({
                url: '{{ route("recalculateTotalHour") }}', // Replace with your recalculate endpoint
                method: 'POST',
                data: {
                    selectedRows: selectedRows,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle the success response (if needed)
                    console.log('Recalculation success:', response);

                    Swal.fire({
                        icon: 'success',
                        title: 'Done',
                        text: 'Successfully Updated',
                    }).then(function() {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    });

                    console.log('SweetAlert displayed');

                },
                error: function(error) {
                    // Handle the error response (if needed)
                    console.error('Recalculation error:', error);
                }
            });
        });
    });

</script>
