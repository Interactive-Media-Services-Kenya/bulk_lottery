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
                    <h3>All Messages</h3>
                    <p class="text-subtitle text-muted">Click on Message Name to View Entire Details</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Messages</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Messages
                    <a href="{{route('messages.create')}}" class="btn btn-primary float-start float-lg-end">Add Messages</a>
                </div>
                <div class="card-body">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Sender Name</th>
                                <th>Client</th>
                                <th>Brand</th>
                                <th>Date Sent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bulkMessages as $item)
                                <tr>
                                    <td>{{ $item->destination ?? '' }}</td>
                                    <td>{{ \Str::of($item->message)->words(10,' ...') ?? '' }}</td>
                                    <th>{{$item->senderName->short_code??''}}</th>
                                    <td>{{ $item->client->name ?? '' }}</td>
                                    <td>{{ $item->brand->name ?? '' }}</td>
                                    <td>{{ $item->created_at ?? '' }}</td>

                                    <td><a href="{{ route('campaigns.edit', [$item->id]) }}"
                                            class="btn btn-sm btn-warning"><i class="bi-pencil"></i></a>&nbsp;<a
                                            class="btn btn-sm btn-danger" href="{{ route('campaigns.index') }}"
                                            onclick="
                                return confirm('Are you sure you want to Delete this campaign?');
                                event.preventDefault();
                                 document.getElementById(
                                   'delete-form-{{ $item->id }}').submit();"><i
                                                class="bi-trash"></i></a></td>
                                    <form id="delete-form-{{ $item->id }}" +
                                        action="{{ route('campaigns.destroy', $item->id) }}" method="post">
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
