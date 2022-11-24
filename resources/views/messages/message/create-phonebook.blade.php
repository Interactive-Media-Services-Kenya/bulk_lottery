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
                    <h3>Quick To PhoneBook</h3>
                    <p class="text-subtitle text-muted">Send SMS to PhoneBook (Contact Group)</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Send SMS To PhoneBook</li>
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
                            <h4 class="card-title">Select Phonebook & SenderName</h4>
                            <a href="{{ route('phonebooks.create') }}" class="btn btn-primary float-start float-lg-end">Add
                                PhoneBook</a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="{{ route('messages.message.phonebook.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <label for="first-name-column">Select PhoneBook</label>
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="phonebook_id" name="phonebook_id"
                                                        required>
                                                        <option selected value="">Select PhoneBook</option>
                                                        @forelse ($phoneBooks as $phoneBook)
                                                            <option value="{{ $phoneBook->id }}">{{ $phoneBook->name }} --
                                                                {{ $phoneBook->contacts->count() }} Contact(s)</option>
                                                        @empty
                                                            <option deselected>No Registred PhoneBook with Contacts</option>
                                                        @endforelse
                                                    </select>
                                                </fieldset>
                                                @error('phonebook_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label for="first-name-column">Select SenderName</label>
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="sender_id" name="sender_id" required>
                                                        <option selected value="">Select SenderName</option>
                                                        @forelse ($senderNames as $senderName)
                                                            )
                                                            <option value="{{ $senderName->id }}">
                                                                {{ $senderName->short_code }}</option>
                                                        @empty
                                                            <option deselected>No Registred Sender Names</option>
                                                        @endforelse
                                                    </select>
                                                </fieldset>
                                                @error('brand_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label>Message</label>
                                                    <textarea rows="10" class="form-control" placeholder="Start Typing Here ..." id="editor" name="message"></textarea>
                                                    <div id="informationchar"></div>
                                                    {{-- <div id="informationword"></div> --}}
                                                    <div id="informationparagraphs"></div>
                                                </div>
                                                @error('message')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
    <script src="{{ asset('assets/js/pages/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/ckeditor/plugins/wordcount/plugin.js') }}"></script>
    <script src="{{ asset('assets/js/pages/ckeditor.js') }}"></script>
@endsection
