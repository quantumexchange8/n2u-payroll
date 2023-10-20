@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<!-- Main Content -->
<div class="main-content d-flex flex-column flex-md-row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>

    <div id="createEventModal" class="modal fade" style="margin-left: 120px;">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{route('addSchedule')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header pb-0 border-bottom-0 flex-column">
                        <div class="custom-select-box d-inline-flex align-items-center m_style mt-3">
                            <label for="employee_id"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <select name="employee_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="calendar-modal-title-wrap w-100 d-flex mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="shift_id"><i class="icofont-clock-time"></i></label>
                                <select name="shift_id">
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->formatted_shift_time }}</option>  
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
  
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <!-- End Modal Header -->
    
                    <!-- Modal Body -->
                    <div id="modalBody2" class="modal-body border-bottom-0 pt-0 pb-0">
                        <form id="scheduleForm">
                            <div class="calendar-modal-location m_style mt-10">
                                <label for="date"><i class="icofont-calendar"></i></label>
                                <input type="date" name="date" placeholder="Date">
                            </div>
                        </form>
                    </div>
                    <!-- End Modal Body -->
    
                    <div class="modal-footer border-top-0 pt-10">
                        <button class="btn" id="saveSchedule">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column border-bottom-0 pt-2 pb-0">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                    {{-- <h6 id="modalTitle1">Details</h6> --}}
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="display: none">ID</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="modalScheduleId" class="font-14" style="display: none"></td>
                                <td id="modalFullName"></td>
                                <td id="modalDate" class="font-14"></td>
                                <td id="modalShiftStart"></td>
                                <td id="modalShiftEnd"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    
                <div class="modal-footer justify-content-around pb-2 border-top-0">
                    {{-- <button class="edit-btn" data-toggle="modal" data-target="#editEventModal" data-dismiss="modal" id="editEventButton">
                        <i class="icofont-ui-edit"></i> Edit
                    </button> --}}
    
                    <form id="deleteEventForm" method="POST" action="{{ route('deleteSchedule', ['id' => 0]) }}">
                        @csrf
                        @method('DELETE')
                    </form>
    
                    <button type="button" class="delete-btn" data-dismiss="modal" id="deleteEventBtn">
                        <i class="icofont-ui-delete"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- <div id="editEventModal" class="modal fade" style="margin-left: 120px;">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('updateSchedule', ['id' => 0]) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header pb-0 border-bottom-0 flex-column">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                        
                        <div class="custom-select-box d-inline-flex align-items-center m_style">
                            <label for="employee_id"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <input type="text" id="employee_id" name="employee_id">
                        </div>
    
                        <div class="calendar-modal-title-wrap w-100 d-flex mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="shift_id"><i class="icofont-clock-time"></i></label>
                                <input type="text" id="shift_id" name="shift_id">
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Header -->
    
                    <!-- Modal Body -->
                    <div id="modalBody2" class="modal-body border-bottom-0 pt-0 pb-0">
                        <form id="scheduleForm">
                            <div class="calendar-modal-location m_style mt-10">
                                <label for="date"><i class="icofont-calendar"></i></label>
                                <input type="date" id="date" name="date">
                            </div>
                        </form>
                    </div>
                    <!-- End Modal Body -->
    
                    <div class="modal-footer border-top-0 pt-10">
                        <button class="btn" id="saveSchedule">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
    
    
    
</div>

<!-- End Main Content -->

<script>
    // Get a reference to the delete button and the form containing the CSRF token.
    const deleteButton = document.getElementById('deleteEventBtn');
    const deleteForm = document.getElementById('deleteEventForm');

    // Add a click event listener to the delete button.
    deleteButton.addEventListener('click', async function (e) {
        e.preventDefault(); // Prevent the default form submission.

        const formData = new FormData(deleteForm);
        formData.append('_token', '{{ csrf_token() }}');

        // Submit the form with the updated form data.
        fetch('/admin/deleteSchedule/' + $('#modalScheduleId').text(), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .then((response) => {
            if (response.ok) {
                // Schedule deleted successfully, display a success alert.
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Schedule deleted successfully.',
                    
                }).then(() => {
                    // After successful deletion, you can perform additional actions.
                    // For example, refreshing the page.
                    location.reload(); // This reloads the page.
                });
            } else {
                // Display an error alert in case of a failure.
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the schedule.',
                });
            }
        })
        .catch((error) => {
            // Handle any network errors or exceptions here.
            console.error('Error:', error);
        });
    });

</script>

{{-- <script>
    // Get a reference to the "Edit" button.
    const editEventButton = document.getElementById('editEventButton');

    // Add a click event listener to the "Edit" button.
    editEventButton.addEventListener('click', function () {
        // Get data from the fullCalModal (you can customize this part based on your modal structure).
        const fullName = document.getElementById('modalFullName').textContent;
        const shiftStart = document.getElementById('modalShiftStart').textContent;
        const shiftEnd = document.getElementById('modalShiftEnd').textContent;
        // const date = document.getElementById('modalDate').textContent;

        // Populate the fields in the editEventModal.
        document.getElementById('employee_id').value = fullName; // Assuming the "employee_id" is an input field.
        document.getElementById('shift_id').value = shiftStart + ' - ' + shiftEnd; // Assuming the "shift_id" is a select element.

        // Set the date input to the date from fullCalModal.
        // document.getElementById('date').value = date; // Assuming the "date" is an input field.
    });
</script> --}}
  

@endsection
