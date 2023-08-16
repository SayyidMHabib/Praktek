@extends('main')

@section('isidashboard')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Data Belanja</h5>
                </div>
                <div class="row">
                    <div class="col-md-2 col-xs-12 ml-3 mt-3">
                        <div class="form-group">
                            <a href="#" onclick="tambah()" class="btn btn-success btn-block tambah"><i
                                    class="fa fa-plus"></i>
                                Tambah</a>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 ml-3">
                        <p>
                            Note: Jika transaksi lebih dari Rp.1.000.000 akan mendapatkan voucher potongan harga Rp. 10.000
                        </p>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover display" id="table-belanja" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tanggal Transaksi</th>
                                    <th class="text-center">Invoice Transaksi</th>
                                    <th class="text-center">Total Belanja (Rp)</th>
                                    <th class="text-center">Voucher Dari Transaksi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit About --}}
    <div id="modal_belanja" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_belanjaLabel">Data belanja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="error"></div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="frm_belanja">
                        <div class="mb-3">
                            <label for="belanja">Daftar Belanja</label>
                            <div class="form-group" id="tampil"></div>
                            <input type="hidden" id="list_belanja" name="list_belanja">
                            <div class="form-group" id="tambah_belanja">
                                <div class="row">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="item" id="item"
                                            placeholder="Item Belanja">
                                    </div>
                                    <div class="col-5">
                                        <input type="number" class="form-control" name="harga" id="harga"
                                            placeholder="Harga Belanja">
                                    </div>
                                    <div class="col-2">
                                        <a class="badge bg-success mt-2 addMore" name="add_belanja" id="add_belanja"
                                            onclick="add();"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="tampil_voucher"></div>
                        <div class="mb-3" id="hasil_voucher"></div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total Belanja</label>
                            <input type="text" class="form-control" id="total" name="total" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn  btn-success" id="save_belanja">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toastr -->
    <script src="{{ asset('dashboard/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        let table = new DataTable('#table-belanja', {
            responsive: true,
            sort: true,
            processing: true,
            ajax: "{{ url('/belanja') }}",
            columnDefs: [{
                targets: 0,
                className: 'dt-body-center'
            }, {
                targets: 1,
                className: 'dt-body-center'
            }, {
                targets: 3,
                className: 'dt-body-right'
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
                name: 'Tanggal Transaksi'
            }, {
                data: 'kode_belanja',
                name: 'Invoice Transaksi',
                searchable: true
            }, {
                data: 'total',
                name: 'Total'
            }, {
                data: 'voucher',
                name: 'Voucher'
            }, {
                data: 'aksi',
                name: 'Aksi'
            }]
        });

        function tambah() {
            $("#frm_belanja").trigger("reset");
            $("#tampil").html('');
            $("#tampil_voucher").html('');
            $("#hasil_voucher").html('');
            localStorage.removeItem('belanja');
            $('#modal_belanja').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
            $('#save_belanja').click(function(e) {
                e.preventDefault();
                $id = '';
                simpan($id);
            })
        }

        function add() {
            var item = document.getElementById("item");
            var harga = document.getElementById("harga");
            var tampung_array = JSON.parse(localStorage.getItem("belanja"));
            if (!tampung_array) {
                var tampung_array = [];
            }
            tampung_array.push({
                item: item.value,
                harga: harga.value
            });
            item.value = "";
            harga.value = "";
            var data_array = JSON.stringify(tampung_array);
            localStorage.setItem("belanja", data_array);
            insert_array_to_list_belanja(data_array);
            show();
            show_voucher();
        }

        function insert_array_to_list_belanja(data) {
            $("#list_belanja").val(data);
        }

        function show() {
            var html = "";
            var tampung_array = JSON.parse(localStorage.getItem("belanja"));
            if (tampung_array) {
                let sum = 0;
                tampung_array.map((dt, i) => {
                    // console.log(dt);
                    html +=
                        "<ul class='list-group my-1'><li class='list-group-item d-flex justify-content-between align-items-start'><div class='me-auto'>" +
                        dt.item + ' Rp.' + dt.harga +
                        "</div><a class='d-flex justify-content-end' style='margin-right: 8px;' onclick='remove(" +
                        i +
                        ");' href='#'><span class='badge bg-danger rounded-pill'><i class='fas fa-trash'></i></span></a></li></ul>";

                    sum += parseInt(dt.harga);

                    var total = document.getElementById("total");
                    total.value = sum;
                });
            }
            var tampil = document.getElementById("tampil");
            tampil.innerHTML = html;
        }

        function show_voucher() {
            var tampil_voucher = document.getElementById("tampil_voucher");
            tampil_voucher.innerHTML =
                "<label for='voucher' class='form-label'>Voucher</label><div class='form-group'><a class='badge bg-success position-absolute end-0 mt-2' name='cek_voucher' id='cek_voucher' onclick='cek_voucher();'>Cek Voucher</a><input type='text' class='form-control' id='invoice' name='invoice' placeholder='Invoice Transaksi' required></div>";
        }

        function remove(prg) {
            let c = confirm("Hapus Belanja?");
            if (c) {
                var tampung_array = JSON.parse(localStorage.getItem("belanja"));
                if (tampung_array) {
                    tampung_array.splice(prg, 1);
                    localStorage.setItem("belanja", JSON.stringify(tampung_array));
                    insert_array_to_list_belanja(JSON.stringify(tampung_array));
                    show();
                }
            }
        }

        function cek_voucher() {
            var kode = document.getElementById('invoice').value;

            $.ajax({
                url: '/belanja/' + kode,
                type: 'GET',
                dataType: "json",
                success: function(response) {
                    console.log(response.result);
                    var hasil_voucher = document.getElementById('hasil_voucher');
                    if (response.result != null) {
                        hasil_voucher.innerHTML =
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>Voucher berhasil digunakan. Anda mendapat potongan harga Rp. 10.000</div>";

                        var total = document.getElementById("total");
                        if (total.value >= 10000) {
                            total.value = total.value - 10000;
                        } else {
                            total.value = 0;
                        }
                    } else {
                        hasil_voucher.innerHTML =
                            "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Voucher tidak tersedia !!!</div>";
                    }
                }
            });
        }

        function simpan(id) {
            if (id == '') {
                var ajax_type = 'POST';
                var ajax_url = '/belanja';
            } else {
                var ajax_type = 'POST';
                var ajax_url = '/belanja_edit/' + id;
            }

            var total = $('#total').val();
            var invoice = $('#invoice').val();

            var form_data = new FormData();
            form_data.append("total", total);
            form_data.append("invoice", invoice);

            $.ajax({
                type: ajax_type,
                url: ajax_url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                data: form_data,
                success: function(response) {
                    console.log(response.data);
                    toastr.success(response.message);
                    table.ajax.reload();
                    $("#modal_belanja").modal("hide");
                },
                error: function(response) {
                    // console.log(response.responseJSON.errors);
                    $.each(response.responseJSON.errors, function(key, value) {
                        $('.error').append(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            value +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                        );
                    });
                }
            });
        }

        function detele(id) {
            Swal.fire({
                title: 'Yakin hapus Data Belanja?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, hapus data!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/belanja/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            table.ajax.reload();
                        }
                    });
                }
            });
        }
    </script>
@endsection
