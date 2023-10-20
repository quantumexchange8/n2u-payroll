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

    {{-- <div id="createEventModal" class="modal fade" style="margin-left: 120px;">
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
    </div> --}}

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
    
                {{-- <div class="modal-footer justify-content-around pb-2 border-top-0">
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
                </div> --}}
            </div>
        </div>
    </div>
    
    
</div>

<!-- End Main Content -->

@endsection
