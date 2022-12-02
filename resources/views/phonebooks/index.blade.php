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
                    <h3>All PhoneBooks</h3>
                    <p class="text-subtitle text-muted">Click on PhoneBook Name to View Entire Details</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">PhoneBooks</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    PhoneBooks
                    <a href="{{ route('phonebooks.create') }}" class="btn btn-primary float-start float-lg-end">Add
                        PhoneBook</a>
                </div>
                <div class="card-body">
                    <table class="table" id="PhoneBookTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>No. Contacts</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse ($phoneBooks as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->client->name}}</td> --}}
                            {{-- <td>{{$item->contacts->count()}}</td> --}}
                            {{-- <td>{{$item->created_at}}</td>
                               <td><a href="{{route('phonebooks.show',[$item->id])}}" class="btn btn-sm btn-primary"><i class="bi-eye"></i></a>&nbsp;<a href="{{route('phonebooks.edit',[$item->id])}}" class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>&nbsp;<a class="btn btn-sm btn-danger" href="{{ route('phonebooks.index') }}"
                                onclick="
                                confirm('Are you sure you want to Delete this PhoneBook?');
                                event.preventDefault();
                                 document.getElementById(
                                   'delete-form-{{$item->id}}').submit();"><i class="bi-trash"></i></a></td>
                               <form id="delete-form-{{$item->id}}"
                                + action="{{route('phonebooks.destroy', $item->id)}}"
                                method="post">
                              @csrf @method('DELETE')
                          </form></tr>
                        @empty
                        <tr>
                            <td colspan="5">No PhoneBooks Added</td>
                        </tr>
                        @endforelse --}}

                        </tbody>
                    </table>
                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    @endsection
    @section('scripts')
        @include('layouts.partials.utilities.datatableScripts')
        <script>
            $(document).ready(function() {
                $('#PhoneBookTable').DataTable({
                    aaSorting: [
                        [3, "desc"]
                    ],
                    processing: true,
                    method: 'GET',
                    serverSide: true,
                    ajax: "{{ route('phonebooks.index') }}",
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'client',
                            name: 'client.name'
                        },
                        {
                            data: 'contacts',
                            name: 'contacts_count'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'actions',
                            name: 'actions'
                        },
                    ],
                    dom: 'lBfrtip',
                    pageLength: 100,
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Contact_list',
                            exportOptions: {
                                exportOptions: {
                                    columns: [0, 1, 2, 3, ':visible']
                                }
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Contact_list',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        'colvis'
                    ]
                });
            });
        </script>
    @endsection
