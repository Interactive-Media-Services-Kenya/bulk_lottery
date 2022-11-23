@extends('layouts.backend')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Import Contacts</h3>
                <p class="text-subtitle text-muted">Import Contacts To PhoneBook</p>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Import Contacts To PhoneBook</li>
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
                        <h4 class="card-title">Select PhoneBook and Import File Information</h4>
                        <a href="{{ route('contacts.phonebook.import.get') }}"
                                class="btn btn-primary float-start float-lg-end">
                                <i class="bi bi-download"></i> Get Sample Excel Import File
                            </a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('contacts.phonebook.import.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
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
                                <div class="col-md-6 col-12">
                                    <label for="first-name-column">Select Contact Import File</label>
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
