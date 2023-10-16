@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Multiple Column</h4>

                    <!-- Form -->
                    <form action="{{ route('updatePosition', $positions->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position ID</label>
                                    <input type="text" class="theme-input-style" id="position_id" name="position_id" autocomplete="off" placeholder="Position ID" value="{{$positions->position_id}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position</label>
                                    <input type="text" class="theme-input-style" id="position" name="position" autocomplete="off" placeholder="Position" value="{{$positions->position}}">
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-lg-6">                               
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Department</label>
                                    <select class="theme-input-style" id="department_id" name="department_id" autocomplete="off">
                                        {{-- @foreach($positions as $position)
                                            <option >
                                                {{ $position->department }}
                                            </option>
                                        @endforeach --}}
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" name="department_id">
                                                {{ $department->department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group --> 
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Update</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->

@endsection