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

    <div id="createEventModal" class="modal fade" style="margin-left: 80px;">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{route('addSchedule')}}" method="POST">
            @csrf
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header pb-0 border-bottom-0 flex-column">
                        <div class="custom-select-box d-inline-flex align-items-center m_style" style="width: 227px; margin-top: 12px;">
                            <label for="employee_id"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <select name="employee_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="calendar-modal-title-wrap w-10 d-flex mt-10">
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
                        <form>

                            <div class="calendar-modal-location m_style mt-10" style="width: 228px;">
                                <label for="remark"><i class="icofont-tag"></i></label>
                                <select name="duty_id">
                                    @foreach ($duties as $duty)
                                        <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>  
                                    @endforeach
                                </select>
                            </div>

                            <div class="calendar-modal-location m_style mt-10" style="width: 228px;">
                                <label for="remarks"><i class="icofont-pen-alt-4"></i></label>
                                <input type="text" id="remarks" name="remarks" placeholder="Remarks">
                            </div>

                            <div class="calendar-modal-dates mt-10 d-flex">
                                <div class="calendar-modal-start-date m_style mr-2">
                                    <label for="formGroupExampleInput2"></label>
                                    <input type="date" name="date_start" placeholder="Date">
                                </div>
                                
                                <div class="calendar-modal-end-date m_style mr-2">
                                    <label for="formGroupExampleInput3"></label>
                                    <input type="date" name="date_end" placeholder="Date">
                                </div>
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
                    <div>
                        <h6 id="modalDate"></h6>
                        <h6 id="modalFullName"></h6>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="display: none">ID</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Duty</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="modalScheduleId" class="font-14" style="display: none"></td>
                                <td id="modalShiftStart"></td>
                                <td id="modalShiftEnd"></td>
                                <td id="modalDutyName"></td>
                                <td id="modalRemarks"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer justify-content-around pb-2 border-top-0">
                    <button class="edit-btn" data-toggle="modal" data-target="#editEventModal" data-dismiss="modal" id="editEventButton">
                        <i class="icofont-ui-edit"></i> Edit
                    </button>
    
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

    <div id="editEventModal" class="modal fade" style="margin-left: 120px;">
        <div class="modal-dialog modal-dialog-centered">
            <form id="scheduleForm" action="{{ route('updateSchedule', ['id' => 0]) }}" method="POST" data-schedule-id="">
                @csrf
                <input type="hidden" id="editEventId" name="id" value="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header pb-0 border-bottom-0 flex-column">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>

                        <div class="custom-select-box d-none align-items-center m_style ">
                            <label for="employee_id"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <input type="text" id="edit_employee_id" name="employee_id">
                        </div>
    
                        <div class="calendar-modal-title-wrap w-100 d-none mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="shift_id"><i class="icofont-clock-time"></i></label>
                                <input type="text" id="edit_shift_id" name="shift_id">
                            </div>
                        </div>
                        

                        <div class="custom-select-box d-inline-flex align-items-center m_style">
                            <label for="edit_employee_id"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <select id="edit_employee_id" name="edit_employee_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id === $user->employee_id ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>                               
                                @endforeach
                            </select>
                        </div>
    
                        <div class="calendar-modal-title-wrap w-100 d-flex mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="edit_shift_id"><i class="icofont-clock-time"></i></label>
                                <select id="edit_shift_id" name="edit_shift_id">
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $shift->id === $shift->shift_id ? 'selected' : '' }}>
                                            {{ $shift->formatted_shift_time }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="calendar-modal-title-wrap w-100 d-flex mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="edit_duty_id"><i class="icofont-clock-time"></i></label>
                                <select id="edit_duty_id" name="edit_duty_id">
                                    @foreach ($duties as $duty)
                                        <option value="{{ $duty->id }}" {{ $duty->id === $duty->duty_id ? 'selected' : '' }}>
                                            {{ $duty->duty_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="calendar-modal-location m_style mt-10">
                            <label for="edit_remarks"><i class="icofont-location-pin"></i></label>
                            <input type="text" id="edit_remarks" name="remarks">
                         </div>
                    </div>
                    <!-- End Modal Header -->
    
                    <!-- Modal Body -->
                    <div id="modalBody2" class="modal-body border-bottom-0 pt-0 pb-0">
                        <form id="scheduleForm">
                            <div class="calendar-modal-location m_style mt-10">
                                <label for="date"><i class="icofont-calendar"></i></label>
                                <input type="date" id="edit_date" name="date">
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
</div>

<!-- End Main Content -->

<script>
    $(document).ready(function(){

        $('#editEventButton').click(function() {
            // Get the values from the data attributes set in fullCalModal
            var fullName = $('#modalFullName').data('full-name');
            var date = $('#modalDate').data('date');
            var shiftStart = $('#modalShiftStart').data('shift-start');
            var shiftEnd = $('#modalShiftEnd').data('shift-end');
            var dutyName = $('#modalDutyName').data('duty-name');
            var scheduleId = $('#modalScheduleId').text();
            var remarks = $('#modalRemarks').data('remarks');

            // Format the shift times
            var formattedShiftTime = formatShiftTime(shiftStart, shiftEnd);

            // Set the scheduleId as a data attribute on the form
            $('#scheduleForm').data('schedule-id', scheduleId);

            console.log(fullName, date, shiftStart, shiftEnd, dutyName, scheduleId, remarks);

            // Set the values in the editEventModal
            $('#edit_employee_id').val(fullName);
            $('#edit_shift_id').val(formattedShiftTime);
            $('#edit_date').val(date);
            $('#edit_duty').val(dutyName);
            $('#edit_remarks').val(remarks);
            $('#editEventId').val(scheduleId); // Set the ID in a hidden input field

            // Dynamically set the form action based on the scheduleId
            var formAction = '{{ route('updateSchedule', ['id' => ':id']) }}';
            formAction = formAction.replace(':id', scheduleId);
            $('#scheduleForm').attr('action', formAction);

            // Open the editEventModal
            $('#editEventModal').modal();
        });

        // Function to format the shift time
        function formatShiftTime(shiftStart, shiftEnd) {
            // Assuming shiftStart and shiftEnd are in a 24-hour format, e.g., "14:00"
            // You may need to adjust this based on the actual format of your data
            var formattedShiftStart = formatTime(shiftStart);
            var formattedShiftEnd = formatTime(shiftEnd);

            return formattedShiftStart + ' - ' + formattedShiftEnd;
        }

        // Function to format a 24-hour time to AM/PM format
        function formatTime(time) {
            var hour = parseInt(time.split(':')[0]);
            var minute = time.split(':')[1];
            var ampm = hour >= 12 ? 'pm' : 'am';
            hour = hour % 12 || 12; // Convert 0 to 12

            return hour + ':' + minute + ampm;
        }

        $('#saveSchedule').click(function() {
            // Get other form values here
            var scheduleId = $('#scheduleForm').data('schedule-id');
            var employeeId = $('#edit_employee_id').val();
            var shiftId = $('#edit_shift_id').val();
            var dutyId = $('#edit_duty_id').val();
            var date = $('#edit_date').val();
            var remarks = $('#edit_remarks').val();

            // Set the schedule ID as the value for the hidden input field
            $('#scheduleForm input[name="id"]').val(scheduleId);

            // Submit the form
            $('#scheduleForm').submit();
        });

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
    });
</script>

@endsection


