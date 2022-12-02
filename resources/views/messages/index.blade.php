@extends('layouts.backend')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css">
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
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
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
                    <a href="{{ route('messages.create') }}" class="btn btn-primary float-start float-lg-end">Add
                        Messages</a>
                </div>
                <div class="card-body">
                    <table border="0" cellspacing="5" cellpadding="5">
                        <tbody>
                            <tr>
                                <td>Start date:</td>
                                <td><input type="text" id="min" name="min"></td>
                            </tr>
                            <tr>
                                <td>End date:</td>
                                <td><input type="text" id="max" name="max"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table" id="ContactTable">
                        <thead>
                            <tr>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Sender Name</th>
                                {{-- <th>Client</th>
                                <th>Brand</th> --}}
                                <th>Date Sent</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse($bulkMessages as $item)
                                <tr>
                                    <td>{{ $item->destination ?? '' }}</td>
                                    <td>{{ \Str::of($item->message)->words(10, ' ...') ?? '' }}</td>
                                    <th>{{ $item->senderName->short_code ?? '' }}</th>
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
                $('#ContactTable').DataTable({
                    processing: true,
                    method: 'GET',
                    serverSide: true,
                    ajax: "{{ route('messages.index') }}",
                    columns: [{
                            data: 'destination',
                            name: 'destination'
                        },
                        {
                            data: 'message',
                            name: 'message'
                        },
                        {
                            data: 'senderName',
                            name: 'senderName'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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
                                    columns: [0, 1, 2, ':visible']
                                }
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Contact_list',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        'colvis'
                    ]
                });
            });
        </script>
        <script>
            var minDate, maxDate;

            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDate.val();
                    var max = maxDate.val();
                    var date = new Date(data[4]);

                    if (
                        (min === null && max === null) ||
                        (min === null && date <= max) ||
                        (min <= date && max === null) ||
                        (min <= date && date <= max)
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            $(document).ready(function() {
                // Create date inputs
                minDate = new DateTime($('#min'), {
                    format: 'MMMM Do YYYY'
                });
                maxDate = new DateTime($('#max'), {
                    format: 'MMMM Do YYYY'
                });

                // DataTables initialisation
                var table = $('#ContactTable').DataTable();

                // Refilter the table
                $('#min, #max').on('change', function() {
                    table.draw();
                });
            });
        </script>
    @endsection
