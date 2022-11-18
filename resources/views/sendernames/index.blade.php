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
                    <h3>All Sender Names</h3>
                    <p class="text-subtitle text-muted">View All Sender Names Registred</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sender Names</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Sender Names
                </div>
                <div class="card-body">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>Sender Name</th>
                                <th>Client</th>
                                <th>SDP AccessCode</th>
                                <th>SPID</th>
                                <th>Service Provicer</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sendernames as $item)
                                <tr>
                                    <td>{{ $item->short_code ?? '' }}</td>
                                    <td>{{ $item->client->name ?? '' }}</td>
                                    <th>{{$item->sdpaccesscode??''}}</th>
                                    <td>{{ $item->spid ?? '' }}</td>
                                    <td>{{ $item->serviceprovider ?? '' }}</td>
                                    <td>{{ $item->created_at ?? '' }}</td>

                                    <td><a href="{{ route('sendernames.edit', [$item->id]) }}"
                                            class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>&nbsp;<a
                                            class="btn btn-sm btn-danger" href="{{ route('sendernames.index') }}"
                                            onclick="
                                confirm('Are you sure you want to Delete this sendername?');
                                event.preventDefault();
                                 document.getElementById(
                                   'delete-form-{{ $item->id }}').submit();"><i
                                                class="bi-trash"></i></a></td>
                                    <form id="delete-form-{{ $item->id }}" +
                                        action="{{ route('sendernames.destroy', $item->id) }}" method="post">
                                        @csrf @method('DELETE')
                                    </form>
                                </tr>
                            @empty
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
