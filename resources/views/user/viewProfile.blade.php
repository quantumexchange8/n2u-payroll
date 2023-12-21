@extends('layouts.master')
@section('content')
<!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">

<!-- Include SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Main Content -->
<div class="main-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="mx-2 mx-lg-4 mx-xl-5">

                    <!-- Form -->
                    <form action="{{ route('updateProfile') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <!-- Card -->
                                <div class="card">
                                    <div class="card-body p-30">

                                        <!-- Edit Personal Info -->
                                        <div class="edit-personal-info mb-5">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4 class="mb-3">Personal Information</h4>
                                                </div>
                                            </div>

                                            <!-- Form Group -->
                                            <div class="form-group row align-items-center">
                                                <div class="col-3">
                                                    <label for="full_name">Full Name</label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" id="full_name" name="full_name" class="form-control" autocomplete="off" value="{{$user->full_name}}">
                                                </div>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group row align-items-center">
                                                <div class="col-3">
                                                    <label for="address">Address</label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" id="address" name="address" class="form-control" autocomplete="off" value="{{$user->address}}">
                                                </div>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group row align-items-center">
                                                <div class="col-3">
                                                    <label for="email">Email</label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="email" id="email" name="email" class="form-control" autocomplete="off" value="{{$user->email}}">
                                                </div>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group row align-items-center">
                                                <div class="col-3">
                                                    <label for="edit-position">Position</label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" id="position" class="form-control" value="{{ optional($user->position)->position_name }}" readonly>
                                                </div>
                                            </div>
                                            <!-- End Form Group -->

                                        </div>
                                        <!-- End Edit Personal Info -->

                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-xl-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="card-body p-30">
                                        <!-- Change Password -->
                                        <div class="change-password">

                                            <div><h4 class="mb-4 pt-2">Change Password</h4></div>

                                            <!-- Form Group -->
                                            <div class="form-group mb-4">
                                                <label for="old-pass" class="bold font-14 mb-2 black">Old Password</label>
                                                <input type="password" class="theme-input-style" id="old-pass" name="old-pass" placeholder="********">
                                            </div>
                                            @error('old-pass')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-4">
                                                <label for="new-pass" class="bold font-14 mb-2 black">New Password</label>
                                                <input type="password" class="theme-input-style" id="new-pass" name="new-pass" placeholder="********">
                                            </div>
                                            @error('new-pass')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-10">
                                                <label for="retype-pass" class="bold font-14 mb-2 black">Retype Password</label>
                                                <input type="password" class="theme-input-style" id="retype-pass" name="retype-pass" placeholder="********">
                                            </div>
                                            @error('retype-pass')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <!-- End Form Group -->

                                        </div>
                                        <!-- End Change Password -->
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-12 text-right">
                                <!-- Button Group -->
                                <div class="button-group pt-1">
                                    <button type="reset" class="link-btn bg-transparent mr-3 soft-pink">Cancel</button>
                                    <button type="submit" class="btn">Save Changes</button>
                                </div>
                                <!-- End Button Group -->
                            </div>
                        </div>
                    </form>
                    <!-- End Form -->

                </div>
                
            </div>
        </div>
    </div>

</div>
<!-- End Main Content -->

@endsection
