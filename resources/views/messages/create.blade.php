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
                            <a href="{{ route('export.get-bulk-messages-excel') }}"
                                class="btn btn-primary float-start float-lg-end">
                                <i class="bi bi-download"></i> Get Excel Import File
                            </a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="{{ route('messages.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">Select Client</label>
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
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">Select Brand</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicBrand" id="brand_id" name="brand_id"
                                                    data-dependent="campaign_id" required>
                                                    <option selected value="">Select Brand</option>
                                                </select>
                                            </fieldset>
                                            @error('brand_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">Select Campaign</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicCampaign" id="campaign_id"
                                                    name="campaign_id" required>
                                                    <option selected value="">Select Campaign</option>
                                                </select>
                                            </fieldset>
                                            @error('campaign_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="first-name-column">Select Sender Name</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicShortCode" id="sender-name"
                                                    name="sender_id" required>
                                                    <option selected value="">Select Sender Name</option>
                                                </select>
                                            </fieldset>
                                            @error('sender_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <label for="first-name-column">Select SMS Import File</label>
                                            <fieldset class="form-group">

                                                <input type="file" name="file" class="form-control" required>
                                            </fieldset>
                                            @error('file')
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
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {

            // Client Change
            $('.dynamicClient').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr('name');
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('fetch.brands') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token,
                            dependent: dependent
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });
            // Brand Change
            $('.dynamicBrand').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr('name');
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('fetch.campaigns') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token,
                            dependent: dependent
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            // Brand Change
            $('#dynamicClient').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr('name');
                    var value = $(this).val();
                    var dependent = $(this).data('campaign');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('fetch.sender-names') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token,
                            dependent: dependent
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });


            $('.dynamicClient').change(function() {
                $('.dynamicBrand').val('');
                $('.dynamicShortCode').val('');
            });
            $('.dynamicBrand').change(function() {
                $('.dynamicCampaign').val('');
            });

        });
    </script>
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/js/pages/filepond.js') }}"></script>
@endsection
