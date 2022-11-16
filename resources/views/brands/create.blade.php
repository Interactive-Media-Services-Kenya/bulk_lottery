@extends('layouts.backend')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Brand</h3>
                <p class="text-subtitle text-muted">Add Brand Details</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Brand</li>
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
                        <h4 class="card-title">Enter Brand Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('brands.store')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Brand Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="Campaign Name" name="name">
                                        </div>
                                        @error('name')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-12 col-12">
                                            <label for="first-name-column">Select Client</label>
                                            <fieldset class="form-group">
                                                <select class="form-select dynamicClient" id="basicSelect" name="client_id" required>
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
