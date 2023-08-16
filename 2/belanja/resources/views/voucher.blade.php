@extends('main')

@section('isidashboard')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Data Voucher</h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover display" id="table-voucher" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tanggal Voucher</th>
                                    <th class="text-center">Kode Voucher</th>
                                    <th class="text-center">Invoice Transaksi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let table = new DataTable('#table-voucher', {
            responsive: true,
            sort: true,
            processing: true,
            ajax: "{{ url('/datavoucher') }}",
            columnDefs: [{
                targets: 0,
                className: 'dt-body-center'
            }, {
                targets: 1,
                className: 'dt-body-center'
            }, {
                targets: 4,
                className: 'dt-body-center'
            }],
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'tanggal',
                name: 'Tanggal',
                searchable: true
            }, {
                data: 'kode_voucher',
                name: 'Kode Voucher',
                searchable: true
            }, {
                data: 'kode_belanja',
                name: 'Invoice Transaksi',
                searchable: true
            }, {
                data: 'status',
                name: 'Status'
            }]
        });
    </script>
@endsection
