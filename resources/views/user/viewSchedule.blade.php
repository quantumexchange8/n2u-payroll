@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<!-- Main Content -->
{{-- <div class="main-content d-flex flex-column flex-md-row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>

    <!-- Button to trigger the modal -->
    <button id="openModalButton" style="display: none;" data-toggle="modal" data-target="#scheduleModal"></button>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedules for Selected Date</h5>
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
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <!-- Schedule data will be displayed here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div> --}}

<div class="main-content">
    <div class="container-fluid">
        <div class="row">



            <div class="col-md-3 col-sm-6">
                <!-- Card -->
                <div class="card mb-30" style="background-color: #E9E7FF; border-color: #E9E7FF;">
                    <div class="card-body">
                        <h4 class="progress-title">Pending OT Approval</h4>
                        <div class="mb-4">
                            <p style="font-size: 30px;"></p>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>

        </div>
    </div>
</div>

<!-- End Main Content -->

@endsection

<script>
    var userId = {{ $user->id }};
</script>
