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
                    <h3>Month Customer Transactions</h3>
                    <p class="text-subtitle text-muted">Click on Transaction Name to View Entire Details</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            @if (auth()->user()->hasrole('Admin') ||
                auth()->user()->can('transaction_customers_management'))
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldFolder"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Transctions</h6>
                                        <a href="{{ route('transactions.customers.index') }}">
                                            <h6 class="font-extrabold mb-0">KES
                                                {{ $transactionsCustomer['totalTransactions'] }}
                                            </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldCalendar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Today Transactions</h6>
                                        <a href="{{ route('transactions.customers.index') }}">
                                            <h6 class="font-extrabold mb-0">KES
                                                {{ $transactionsCustomer['transactionsToday'] }}
                                            </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldBag"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Week Transactions</h6>
                                        <a href="{{ route('transactions.customers.week') }}">
                                            <h6 class="font-extrabold mb-0">KES
                                                {{ $transactionsCustomer['transactionsWeek'] }}
                                            </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Month Transactions</h6>
                                        <a href="{{ route('transactions.customers.month') }}">
                                            <h6 class="font-extrabold mb-0">KES
                                                {{ $transactionsCustomer['transactionsMonth'] }}
                                            </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Transactions
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary float-start float-lg-end">Purchase
                        Bulk</a>
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
                    <table class="table" id="TransactionTable">
                        <thead>
                            <tr>
                                <th>TransactionID</th>
                                <th>Phone</th>
                                <th>Sender Name</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse ($transactions as $item)
                                <tr>
                                    <td>{{ $item->reference }}</td>
                                    <td>{{ substr($item->msisdn, 0, 5) . '*****' . substr($item->msisdn, -2) }}</td>
                                    <td>{{ $item->mpesa_sender }}</td>
                                    <td>{{ $item->mpesa_account }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Transactions</td>
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
                $('#TransactionTable').DataTable({
                    aaSorting: [
                        [5, "desc"]
                    ],
                    processing: true,
                    method: 'GET',
                    serverSide: true,
                    ajax: "{{ route('transactions.customers.month') }}",
                    columns: [{
                            data: 'reference',
                            name: 'reference'
                        },

                        {
                            data: 'msisdn',
                            name: 'msisdn'
                        },
                        {
                            data: 'mpesa_sender',
                            name: 'mpesa_sender'
                        },
                        {
                            data: 'mpesa_account',
                            name: 'mpesa_account'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
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
        <script>
            var minDate, maxDate;

            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDate.val();
                    var max = maxDate.val();
                    var date = new Date(data[5]);

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
                var table = $('#TransactionTable').DataTable();

                // Refilter the table
                $('#min, #max').on('change', function() {
                    table.draw();
                });
            });
        </script>
    @endsection
