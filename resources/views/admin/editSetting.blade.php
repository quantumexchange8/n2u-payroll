@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Setting</h4>

                    <!-- Form -->
                    <form action="{{ route('updateSetting', $setting->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Setting Name</label>
                                    <input type="text" class="theme-input-style" id="setting_name" name="setting_name" autocomplete="off" placeholder="Setting Name" value="{{$setting->setting_name}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Value</label>
                                    <input type="text" class="theme-input-style" id="value" name="value" autocomplete="off" placeholder="Value" value="{{ $setting->value }}">
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-lg-6">                               
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Description</label>
                                    <input type="text" class="theme-input-style" id="description" name="description" autocomplete="off" placeholder="Description" value="{{ $setting->description }}">
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