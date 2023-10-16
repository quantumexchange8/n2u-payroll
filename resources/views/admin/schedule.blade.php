@extends('layouts.master')
@section('content')


<!-- Main Content -->
<div class="main-content d-flex flex-column flex-md-row">
    <div class="d-none d-md-block">
        <!-- Tasks Aside -->
        <div class="tasks_aside calendar_aside">
            <div class="card">
                <!-- Aside Header -->
                <div class="aside-header">
                <div class="add-event-btn">
                    <a href="#" class="btn w-100" data-toggle="modal" data-target="#createEventModal">
                        <img src="../../assets/img/svg/plus_white.svg" alt="" class="svg mr-1"> 
                        Add New Event
                    </a>
                </div>
                </div>
                <!-- End Aside Header -->

                <!-- Aside Body -->
                <div id="external-events" class="aside-body external-events">
                <ul  id="external-events-listing" class="nav flex-column">
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color birthday"></span>Birthday</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color event"></span>Event</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color friend"></span>Friend</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color work"></span>Work</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color new_project"></span>New Project</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color anniversary"></span>Anniversary</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color meeting"></span>Meeting</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color travel"></span>Travel</a>
                    </li>
                    <li class="lavel fc-event">
                        <a href="#"><span class="tag_color rest"></span>Rest</a>
                    </li>
                </ul>
                </div>
                <!-- End Aside Body -->
            </div>
        </div>
        <!-- End Tasks Aside -->
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column border-bottom-0 pt-2 pb-0">
                    <h6 id="modalTitle1">Required his you </h6>
                    <p id="modalDate" class="font-14">17 . 10 . 2019</p>
                </div>
                <div id="modalBody1" class="modal-body border-bottom-0 pt-0 mt-10">
                    <div class="calendar-modal-location d-flex align-items-center">
                        <span class="icon"><img src="../../assets/img/svg/popup-location.svg" class="svg" alt=""></span>
                        <span id="modalLocation" class="content">Po Box 931, Sterling City, Malta</span>
                    </div>
                    <div class="calendar-modal-visibility d-flex align-items-center">
                        <span class="icon"><img src="../../assets/img/svg/popup-user.svg" class="svg" alt=""></span>
                        <span id="modalVisibility" class="content">Anyone</span>
                    </div>
                    <div class="calendar-modal-Event d-flex align-items-center">
                        <span class="color"></span>
                        <span id="modalEvent" class="content">Anniversary</span>
                    </div>
                </div>
                <div class="modal-footer justify-content-around pb-2 border-top-0">
                    <button class="edit-btn" data-toggle="modal" data-target="#createEventModal" data-dismiss="modal"><i class="icofont-ui-edit"></i> Edit</button>
                    <button type="button" class="delete-btn" data-dismiss="modal"><i class="icofont-ui-delete"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="createEventModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form action="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header pb-0 border-bottom-0 flex-column">
                        <div class="custom-select-box d-inline-flex align-items-center m_style">
                            <label for="formGroupExampleInput6"><img src="../../assets/img/svg/color.svg" alt="" class="svg"></label>
                            <select id="formGroupExampleInput6" name="employee_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

    
                        <div class="calendar-modal-title-wrap w-100 d-flex mt-10">
                            <div class="calendar-modal-title m_style flex-grow">
                                <label for="formGroupExampleInput"><i class="icofont-tag"></i></label>
                                <input type="text" id="formGroupExampleInput" placeholder="Ewe fuc dudbared.">
                            </div>
    
                            <div class="calendar-modal-private m_style">
                                <i class="icofont-unlock"></i>
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
                            <div class="calendar-modal-location m_style mt-10">
                                <label for="formGroupExampleInput1"><i class="icofont-location-pin"></i></label>
                                <input type="text" id="formGroupExampleInput1" placeholder="502 Ozisa Heights">
                            </div>
    
    
                            <div class="calendar-modal-dates mt-10 d-flex">
                                <div class="calendar-modal-start-date m_style mr-2">
                                    <label for="formGroupExampleInput2"><i class="icofont-calendar"></i></label>
                                    <input type="text" id="formGroupExampleInput2" placeholder="2020-01-13">
                                </div>
                                
                                <div class="calendar-modal-end-date m_style mr-2">
                                    <label for="formGroupExampleInput3"><i class="icofont-calendar"></i></label>
                                    <input type="text" id="formGroupExampleInput3" placeholder="2020-01-13">
                                </div>
                                
                                <div class="calendar-modal-checkbox d-flex align-items-center">
                                    <input type="checkbox" id="formGroupExampleInput4">
                                    <label for="formGroupExampleInput4">All Day</label>
                                </div>
                            </div>
    
                            <div class="calendar-modal-state d-inline-flex align-items-center mt-10 m_style">
                                <label for="formGroupExampleInput5"><i class="icofont-bag-alt"></i></label>
                                <select id="formGroupExampleInput5" class="m-state">
                                    <option value="busy">Busy</option>
                                    <option value="free">Free</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <!-- End Modal Body -->
    
                    <div class="modal-footer border-top-0 pt-10">
                    <button class="btn">Save</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End Main Content -->

@endsection
