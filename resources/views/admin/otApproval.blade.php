@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                <div class="card mb-30">

                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20">OT Approval</h4>
    
                            <div class="d-flex flex-wrap">
                                <!-- Date Picker -->
                                <div class="dashboard-date style--six mr-20 mt-3 mt-sm-0">
                                   {{-- <span class="input-group-addon">
                                      <img src="../../assets/img/svg/calender-color.svg" alt="" class="svg">
                                    </span> --}}
    
                                    <input type="date" id="date-picker" value=""/>

                                </div>
                                <!-- End Date Picker -->
    
    
                                <!-- Dropdown Button -->
                              <div class="dropdown-button mt-3 mt-sm-0">
                                <button type="button" class="btn style--two orange" data-toggle="dropdown">Status <i class="icofont-simple-down"></i></button>
    
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Pending</a>
                                    <a class="dropdown-item" href="#">Approved</a>
                                    <a class="dropdown-item" href="#">Rejected</a>
                                </div>
                             </div>
                             <!-- End Dropdown Button -->
                            </div>
                        </div>
       
                        <div class="table-responsive">
                            <!-- Attendance Table -->
                            <table class="text-nowrap hoverable dh-table">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($punchRecords as $punchRecord)
                                        @if (!empty($punchRecord->ot_approval))
                                            <tr>
                                                <td>{{ $punchRecord->employee_id }}</td>
                                                <td>{{ $punchRecord->user->full_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($punchRecord->created_at)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($punchRecord->created_at)->format('H:i:s') }}</td>
                                                <td style="{{ $punchRecord->ot_approval === 'Pending' ? 'color: orange; font-weight: bold;' : ($punchRecord->ot_approval === 'Approved' ? 'color: green; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                                    {{ $punchRecord->ot_approval }}
                                                </td>                                            
                                                <td>
                                                    <a href="{{ route('editOtApproval', ['id' => $punchRecord->id]) }}" class="details-btn">
                                                        Edit <i class="icofont-arrow-right"></i>
                                                    </a>
                                                    <form action="{{ route('deleteOtApproval', ['id' => $punchRecord->id]) }}" method="POST" style="display: inline;">
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
                            <!-- End Attendance Table -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Main Content -->

<script>
    // Get the date input element by its ID
    const dateInput = document.getElementById('date-picker');
    
    // Add an event listener to detect date changes
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value; // Get the selected date
    
        // Make an AJAX request to the server to retrieve punch records for the selected date
        $.ajax({
            url: '/otApproval', // Update the URL to match your server route
            type: 'GET',
            data: { date: selectedDate }, // Send the selected date as a parameter
            success: function(data) {
                // Update the table with the fetched data
                updateTable(data);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    // Function to update the table with fetched data
    function updateTable(data) {
        // Remove the old table rows
        $('.punch-record-row').remove();
        
        // Iterate over the fetched data and append new rows to the table
        for (let record of data) {
            let newRow = `<tr class="punch-record-row">
                <td>${record.employee_id}</td>
                <td>${record.user.full_name}</td>
                <td>${record.created_at}</td>
                <td>${record.status}</td>
                <td>${record.ot_approval}</td>
            </tr>`;
            
            $('table tbody').append(newRow);
        }
    }
</script>



@endsection