@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4 class="font-20 ">Other Image or File</h4>
                            <div class="d-flex flex-wrap">
                                <div class="col-md-4">
                                    <div class="form-row">
                                        <div class="col-12 text-right">
                                             <!-- Triggering the modal when the "Add" button is clicked -->
                                             <a href="#" class="btn long" data-toggle="modal" data-target="#addModal">Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($otherImages as $image)
                <div class="col-md-3 col-sm-6">
                    <!-- Card -->
                    <div class="card mb-30">
                        <div class="card-body">
                            @if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'pdf')
                                <!-- Display PDF using an iframe -->
                                <iframe src="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" width="100%" height="300" style="border: none;"></iframe>
                            @elseif (in_array(pathinfo($image->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                <!-- Provide a download link for Word documents -->
                                <a href="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" target="_blank" download="{{ $image->file_name }}">Download DOC</a>
                            @else
                                <!-- Display the image -->
                                <img src="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" alt="Other Image">
                            @endif

                            <div class="row">
                                <!-- View button -->
                                <button type="button" class="btn mt-3" data-toggle="modal" data-target="#viewModal{{ $image->id }}" style="height: 60%; margin-right: 2%;">
                                    View
                                </button>

                                <!-- Delete button -->
                                <form action="{{ route('deleteOtherImage', ['employeeId' => $user->id, 'imageId' => $image->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn mt-3">
                                        Delete
                                    </button>
                                </form>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="viewModal{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel">View Image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Display image or file inside the modal -->
                                            @if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'pdf')
                                                <!-- Display PDF using an iframe -->
                                                <iframe src="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" width="100%" height="400" style="border: none;"></iframe>
                                            @elseif (in_array(pathinfo($image->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                <!-- Provide a download link for Word documents -->
                                                <a href="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" target="_blank" download="{{ $image->file_name }}">Download DOC</a>
                                            @else
                                                <!-- Display the image -->
                                                <img src="{{ asset('uploads/employee/otherImage/' . $image->file_name) }}" alt="Other Image" style="max-width: 100%; max-height: 400px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endforeach

        </div>
    </div>
</div>

<!-- End Main Content -->

<!-- Modal for adding images -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Other Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for uploading a new image -->
                <form id="addImageForm" action="{{ route('addOtherImage', ['employeeId' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="new_other_image" class="font-14 bold mb-2">Select Image or File</label>
                        <input type="file" class="theme-input-style" id="new_other_image" name="new_other_image" style="background: #ffffff;">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addButton').addEventListener('click', function () {
            // Triggering the click event on the hidden file input
            document.getElementById('other_image').click();
        });
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Triggering the modal when the "Add" button is clicked
        $('#addModal').on('shown.bs.modal', function () {
            $('#new_other_image').focus(); // Focus on the file input when the modal is shown
        });
    });
</script>
