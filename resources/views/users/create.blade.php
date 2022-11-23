@extends('layouts.backend')
@section('styles')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add User</h3>
                    <p class="text-subtitle text-muted">Add User Details</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- // Basic multiple Column Form section start -->
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Enter User Company Information</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="{{ route('users.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Name</label>
                                                <input type="text" id="first-name-column" class="form-control"
                                                    placeholder="FirstName LastName" name="name">
                                            </div>
                                            @error('name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column">Email</label>
                                                <input type="text" id="last-name-column" class="form-control"
                                                    placeholder="somebody@example.com" name="email">
                                            </div>
                                            @error('email')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="city-column">Phone</label>
                                                <input type="text" id="city-column" class="form-control"
                                                    placeholder="2547XXXXXXXX" name="phone">
                                            </div>
                                            @error('phone')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="country-floating">Job Title</label>
                                                <input type="text" id="country-floating" class="form-control"
                                                    name="title" placeholder="Sales Representative">
                                            </div>
                                            @error('title')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <label for="first-name-column">Select Department</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicBrand" id="client_department_id"
                                                    name="client_department_id">
                                                    <option selected value="">Select Department</option>
                                                    @forelse ($departments as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @empty
                                                        <option>No Departments Added</option>
                                                    @endforelse
                                                </select>
                                            </fieldset>
                                            @error('client_department_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @if (auth()->user()->hasrole('Admin') ||
                                            auth()->user()->can('assign_permissions'))
                                            <div class="col-md-12 col-12">
                                                <label for="first-name-column">Select Permissions</label>
                                                <fieldset class="form-group">
                                                    <select class="form-select selectPermissions" id="client_department_id"
                                                        name="client_department_id[]" multiple="multiple">
                                                        @forelse ($permissions as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @empty
                                                            <option>No Departments Added</option>
                                                        @endforelse
                                                    </select>
                                                </fieldset>
                                                @error('client_department_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic multiple Column Form section end -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectPermissions').select2({
                placeholder: 'Select Permissions',
                allowClear: true
            });
        });
    </script>
@endsection
