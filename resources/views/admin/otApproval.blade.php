@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
{{-- Axios --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center" style="margin-bottom: 15px;">
                            <h4 class="font-20">OT Approval</h4>
    
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
                                        <a class="dropdown-item" href="#" data-status="Pending">Pending</a>
                                        <a class="dropdown-item" href="#" data-status="Approved">Approved</a>
                                        <a class="dropdown-item" href="#" data-status="Rejected">Rejected</a>
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
       
                        <div class="table-responsive">
                            <!-- Attendance Table -->
                            <table class="text-nowrap table-bordered dh-table">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Shift Start</th>
                                        <th>Shift End</th>
                                        <th>Clock out</th>
                                        <th>OT hours</th>
                                        <th>Status</th>
                                        {{-- @if($otapproval->remark != null) --}}
                                        <th>Remark</th>
                                        {{-- @endif --}}
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($otapproval as $otapproval)
                                        @php
                                            $recordDate = Carbon\Carbon::parse($otapproval->created_at)->toDateString();
                                            $currentDate = now()->toDateString();
                                        @endphp
                                        @if (!empty($otapproval))
                                            <tr class="status-{{ $otapproval }}" data-date="{{ $recordDate }}">
                                                <td>{{ $otapproval->employee_id }}</td>
                                                <td>{{ $otapproval->user->full_name }}</td>
                                                <td>{{ Carbon\Carbon::parse($otapproval->date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($otapproval->shift_start)->format('g:i A') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($otapproval->shift_end)->format('g:i A') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($otapproval->clock_out_time)->format('g:i A') }}</td>
                                                <td>
                                                    {{ $otapproval->ot_hour }}
                                                </td>  
                                                {{-- <td style="{{ $otapproval->status === 'Pending' ? 'color: orange; font-weight: bold;' : ($otapproval->status === 'Approved' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}"> --}}
                                                <td>
                                                    @if($otapproval->status == 'Pending')
                                                        <button class="status-btn on_hold">
                                                            {{ $otapproval->status }}
                                                        </button>
                                                    @elseif($otapproval->status == 'Rejected')
                                                        <button class="status-btn un_paid">
                                                            {{ $otapproval->status }}
                                                        </button>
                                                    @else
                                                        <button class="status-btn completed">
                                                            {{ $otapproval->status }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>{{ $otapproval->remark }}</td>                                                                                     
                                                <td>
                                                    <form action="{{ route('updateOtApproval', $otapproval->id) }}" method="POST" style="display: flex; justify-content: center;gap: 10px; margin-top: 15px;"  id="reject-form-{{$otapproval->id}}">
                                                        @csrf
                                                        
                                                        @if($otapproval->status == 'Approved')
                                                            <button type="submit" name="ot_approval" value="Approved" class="details-btn approved-btn" disabled style="color: #67CFA2">
                                                                Approved
                                                            </button>
                                                        @elseif($otapproval->status == 'Rejected')
                                                            <button type="button" name="ot_reject" value="Rejected" class="details-btn reject-button" data-punchrecord-id="{{ $otapproval->id }}" style="color: red" disabled>
                                                                Rejected
                                                            </button>
                                                        @else
                                                            <button type="submit" name="ot_approval" value="Approved" class="details-btn approved-btn" style="color: #67CFA2">
                                                                Approved
                                                            </button>

                                                            <input type="hidden" name="remark" id="remark-{{ $otapproval->id }}">

                                                            <button type="button" name="ot_reject" value="Rejected" class="details-btn reject-button" data-punchrecord-id="{{ $otapproval->id }}" style="color: red">
                                                                Rejected
                                                            </button>
                                                        @endif
                                                        
                                                    </form>
                                                </td>                                                
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Attendance Table -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Main Content -->

@endsection

{{-- Update OT Approval Function --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.reject-button').on('click', function() {
            var punchRecord_id = $(this).data('punchrecord-id');
            console.log(punchRecord_id);
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will reject the order. Are you sure you want to proceed?',
                icon: 'warning',
                input: 'text', // Use a text input
                inputLabel: 'Remark',
                inputPlaceholder: 'Enter your remark...',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    const remark = result.value;
                    if(remark) {
                        const form = document.getElementById('reject-form-' + punchRecord_id);
                        if (form) {
                            const remarkInput = document.createElement('input');
                            remarkInput.type = 'hidden';
                            remarkInput.name = 'remark';
                            remarkInput.value = remark;
                            form.appendChild(remarkInput);
                            form.submit();
                        } else {
                            console.error('Form not found: ' + 'reject-form-' + punchRecord_id);
                        }
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Remark cannot be empty.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        });
    });  
</script>

{{-- Filter by status --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the filter dropdown items
        const filterItems = document.querySelectorAll('.dropdown-item[data-status');
        
        // Output the filterItems to the console to check if the selection is correct
        console.log('filter items:',filterItems);

        // Add a click event listener to each filter item
        filterItems.forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                const selectedStatus = this.dataset.status;
                const tableRows = document.querySelectorAll('.dh-table tbody tr');

                // Output the selected status to the console to check if it's correct
                console.log('selected status:',selectedStatus);

                // Iterate through table rows and hide/show based on the selected filter
                tableRows.forEach(function(row) {
                    const status = row.querySelector('.status-btn').textContent.trim(); // Get the status class of the row
                    if (selectedStatus === 'all' || status === selectedStatus) {
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
{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterDropdown = document.getElementById('filter-dropdown');
        const dateFilter = document.getElementById('date-filter');
        const tableRows = document.querySelectorAll('.dh-table tbody tr');

        // Add a click event listener to the filter dropdown
        filterDropdown.addEventListener('click', filterTable);
        dateFilter.addEventListener('input', filterTable);

        function filterTable() {
            const selectedStatus = filterDropdown.dataset.status;
            const selectedDate = dateFilter.value;

            tableRows.forEach(function(row) {
                const status = row.classList[0];
                const date = row.dataset.date; // You'll need to set the data-date attribute in your table rows

                const statusFilter = selectedStatus === 'all' || status === selectedStatus;
                const dateFilter = !selectedDate || date === selectedDate;

                console.log('Selected Date:', selectedDate);
                console.log('Row Date:', date);

                if (statusFilter && dateFilter) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }


    });
</script> --}}

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
        XLSX.writeFile(workbook, `${currentFormattedDate}-OT-Approval.xlsx`);
    }
</script>



  
