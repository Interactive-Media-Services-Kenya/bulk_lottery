@extends('layouts.backend')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Campaign</h3>
                <p class="text-subtitle text-muted">Edit  Details</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Campaign</li>
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
                        <h4 class="card-title">Edit {{$campaign->name}} Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('campaigns.update',[$campaign->id])}}" method="POST">
                                @method('put')
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Campaign Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="Campaign Name" name="name" value="{{$campaign->name}}">
                                        </div>
                                        @error('name')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                            <label for="first-name-column">Select Client</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicClient" id="basicSelect" name="client_id" data-dependent="brand_id" required>
                                                    <option selected value="" disabled>Click Here</option>
                                                    @forelse ($clients as $item )
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @empty
                                                        <option disabled>No Clients Added</option>
                                                    @endforelse
                                                </select>
                                            </fieldset>
                                        @error('client_id')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="first-name-column">Select Brand</label>
                                        <fieldset class="form-group">
                                            <select class="form-select dynamicBrand" id="brand_id" name="brand_id"  required>
                                                <option selected value="" >Select Brand</option>
                                            </select>
                                        </fieldset>
                                    @error('brand_id')<p class="text-danger">{{ $message }}</p>@enderror
                                </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city-column">Start Date</label>
                                            <input type="datetime-local" id="city-column" class="form-control" placeholder="Start Date" required
                                                name="start_date">
                                        </div>
                                        @error('start_date')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country-floating">End Date</label>
                                            <input type="datetime-local" id="country-floating" class="form-control"
                                                name="end_date" placeholder="End Date">
                                        </div>
                                        @error('end_date')<p class="text-danger">{{ $message }}</p>@enderror
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

    $(document).ready(function(){

      // Client Change
      $('.dynamicClient').change(function(){
            if ($(this).val() != '') {
                var select = $(this).attr('name');
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('fetch.brands') }}",
                    method:"POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                        $('#'+dependent).html(result);
                    }

                    })
                }
        });

        $('.dynamicClient').change(function(){
            $('.dynamicBrand').val('');
        });

      });

    </script>
@endsection
