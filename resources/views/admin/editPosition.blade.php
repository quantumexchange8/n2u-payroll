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
                    <form action="{{ route('updatePosition', $position->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position ID</label>
                                    <input type="text" class="theme-input-style" id="position_id" name="position_id" autocomplete="off" placeholder="Position ID" value="{{$position->position_id}}">
                                </div>
                                <!-- End Form Group --> 
                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position</label>
                                    <input type="text" class="theme-input-style" id="position" name="position" autocomplete="off" placeholder="Position" value="{{$position->position}}">
                                </div>
                                <!-- End Form Group -->                                          
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-group pt-1">
                            <div class="d-flex align-items-center mb-3">
                                <!-- Custom Checkbox -->
                                <label class="custom-checkbox position-relative mr-2">
                                    <input type="checkbox" id="check5">
                                    <span class="checkmark"></span>
                                </label>
                                <!-- End Custom Checkbox -->
                                
                                <label for="check5">Remember me</label>
                            </div>
                        </div>
                        <!-- End Form Row -->

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