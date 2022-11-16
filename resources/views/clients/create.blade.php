@extends('layouts.backend')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Client</h3>
                <p class="text-subtitle text-muted">Add Client Details</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Client</li>
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
                        <h4 class="card-title">Enter Client Company Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('clients.store')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Company Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="Company Name" name="name">
                                        </div>
                                        @error('name')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Company Email</label>
                                            <input type="text" id="last-name-column" class="form-control"
                                                placeholder="info@company.com" name="email">
                                        </div>
                                        @error('email')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city-column">Company Phone</label>
                                            <input type="text" id="city-column" class="form-control" placeholder="2547XXXXXXXX"
                                                name="phone">
                                        </div>
                                        @error('phone')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country-floating">Address</label>
                                            <input type="text" id="country-floating" class="form-control"
                                                name="address" placeholder="1234 Nairobi">
                                        </div>
                                        @error('address')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="company-column">City</label>
                                            <input type="text" id="company-column" class="form-control"
                                                name="city" placeholder="Nairobi">
                                        </div>
                                        @error('city')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email-id-column">ZipCode</label>
                                            <input type="number" id="email-id-column" class="form-control"
                                                name="zip" placeholder="123456">
                                        </div>
                                        @error('zip')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
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
