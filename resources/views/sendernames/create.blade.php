@extends('layouts.backend')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css') }}">
@endsection
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Import Messages</h3>
                    <p class="text-subtitle text-muted">Import Information to send SMS</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import SMS Messages</li>
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
                            <h4 class="card-title">Select Client Details & Import Data</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="{{ route('sendernames.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SENDER NAME</label>
                                            <input type="text" class="form-control" name="short_code"placeholder="Sender Name">
                                            @error('short_code')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SDP SERVICE ID</label>
                                            <input type="text" class="form-control" name="sdpserviceid"placeholder="XXXXX">
                                            @error('sdpserviceid')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SDP ACCESS CODE</label>
                                            <input type="text" class="form-control" name="sdpaccesscode"placeholder="XXXXXXXXXXXXXX">
                                            @error('sdpaccesscode')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SP ID</label>
                                            <input type="text" class="form-control" name="spid"placeholder="XXXXX" required>
                                            @error('spid')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SERVICE PROVIDER</label>
                                            <input type="text" class="form-control" name="serviceprovider"placeholder="SAFARICOM,TELCOM,AIRTEL">
                                            @error('short_code')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">SELECT CLIENT</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicClient" id="dynamicClient" name="client_id"
                                                    data-dependent="brand_id" data-campaign="sender-name" required>
                                                    <option selected value="" disabled>Click Here</option>
                                                    @forelse ($clients as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @empty
                                                        <option disabled>No Clients Added</option>
                                                    @endforelse
                                                </select>
                                            </fieldset>
                                            @error('client_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
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

