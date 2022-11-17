@extends('layouts.backend')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add Contact</h3>
                <p class="text-subtitle text-muted">Add Contact Details</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Contact</li>
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
                        <h4 class="card-title">Edit Contact Information</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('contacts.update',[$contact->id])}}" method="POST">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Contact Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="Contact Name" name="name" value="{{$contact->name}}">
                                        </div>
                                        @error('name')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">Contact Email</label>
                                            <input type="email" id="first-name-column" class="form-control"
                                                placeholder="somebody@example.com" name="email" value="{{$contact->email}}">
                                        </div>
                                        @error('email')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city-column">Company Phone</label>
                                            <input type="text" id="city-column" class="form-control" placeholder="2547XXXXXXXX"
                                                name="phone" value="{{$contact->phone}}">
                                        </div>
                                        @error('phone')<p class="text-danger">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="first-name-column">Select PhoneBook</label>
                                        <fieldset class="form-group">
                                            <select class="form-select dynamicBrand" id="phone_book_id" name="phone_book_id">
                                                <option value="">Select PhoneBook</option>
                                                @forelse ($phoneBooks as $phoneBook)
                                                    <option value="{{$phoneBook->id}}">{{$phoneBook->name}}</option>
                                                @empty
                                                <option deselected>No PhoneBooks</option>
                                                @endforelse
                                            </select>
                                        </fieldset>
                                    @error('phone_book_id')<p class="text-danger">{{ $message }}</p>@enderror
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
