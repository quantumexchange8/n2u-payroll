@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row"></div>
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Admin</h4>

                    <!-- Form -->
                    <form action="{{ route('updateAdmin', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" value="{{$user->employee_id}}" readonly>
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <input type="text" class="theme-input-style" id="full_name" name="full_name" autocomplete="off" placeholder="Full Name" value="{{$user->full_name}}">
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Role</label>
                                    <select class="theme-input-style" id="role" name="role">
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nickname</label>
                                    <input type="text" class="theme-input-style" id="nickname" name="nickname" autocomplete="off" placeholder="Nickname" value="{{$user->nickname}}">
                                    @error('nickname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Number / Passport</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number" value="{{$user->ic_number}}">
                                    @error('ic_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Nationality</label>
                                    <select class="theme-input-style" id="nation" name="nation">
                                        <option value="">Select Nationality</option>
                                        <option value="Malaysia" {{ $user->nation === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Thailand" {{ $user->nation === 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Cambodia" {{ $user->nation === 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                                        <option value="Nepal" {{ $user->nation === 'Nepal' ? 'selected' : '' }}>Nepal</option>
                                        <option value="Myanmar" {{ $user->nation === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                        <option value="Laos" {{ $user->nation === 'Laos' ? 'selected' : '' }}>Laos</option>
                                        <option value="Vietnam" {{ $user->nation === 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        <option value="Philippines" {{ $user->nation === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                        <option value="Pakistan" {{ $user->nation === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="Sri Lanka" {{ $user->nation === 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                                        <option value="Turkmenistan" {{ $user->nation === 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
                                        <option value="Uzbekistan" {{ $user->nation === 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
                                        <option value="Kazakhstan" {{ $user->nation === 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                                        <option value="India" {{ $user->nation === 'India' ? 'selected' : '' }}>India</option>
                                        <option value="Indonesia" {{ $user->nation === 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Bangladesh" {{ $user->nation === 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" id="password" name="password" placeholder="Password" value="{{$user->password}}">
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

            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Change Password</h4>

                    <!-- Form -->
                    <form action="{{ route('updateEmployeePassword', $user->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group mb-4">
                                    <label for="new-pass" class="bold font-14 mb-2">New Password</label>
                                    <input type="number" class="theme-input-style" id="new-pass" name="new_password">
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Update Password</button>
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


