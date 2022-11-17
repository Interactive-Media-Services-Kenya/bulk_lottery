@extends('layouts.backend')
@section('styles')
    <link rel="stylesheet" href="assets/css/pages/fontawesome.css">
    <link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/pages/datatables.css">
@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Contacts</h3>
                <p class="text-subtitle text-muted">Click on Contact Name to View Entire Details</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Contacts
                <a href="{{route('contacts.create')}}" class="btn btn-primary float-start float-lg-end">Add Contact</a>
            </div>
            <div class="card-body">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>PhoneBook</th>
                            <th>Phone Number</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->phoneBook->name??'Not In Any PhoneBook'}}</td>
                            <td>{{$item->phone}}</td>
                            <td>{{$item->created_at}}</td>
                               <td><a href="{{route('contacts.show',[$item->id])}}" class="btn btn-sm btn-primary"><i class="bi-eye"></i></a>&nbsp;<a href="{{route('contacts.edit',[$item->id])}}" class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>&nbsp;<a class="btn btn-sm btn-danger" href="{{ route('contacts.index') }}"
                                onclick="
                                confirm('Are you sure you want to Delete this Contact?');
                                event.preventDefault();
                                 document.getElementById(
                                   'delete-form-{{$item->id}}').submit();"><i class="bi-trash"></i></a></td>
                               <form id="delete-form-{{$item->id}}"
                                + action="{{route('contacts.destroy', $item->id)}}"
                                method="post">
                              @csrf @method('DELETE')
                          </form></tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center lead">No Contacts Added</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection
@section('scripts')
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="assets/js/pages/datatables.js"></script>
@endsection
