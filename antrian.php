<?php $this->extend('template') ?>

<?php $this->section('content') ?>
<div class="row">
    <div class="col-lg-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pelanggan</h4>
                <p class="card-description">
                    Meja yang memiliki pesanan belum dihidangkan.
                </p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Meja</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Ubah</th>
                            </tr>
                        </thead>
                        <tbody id="tabelAntrian">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pelanggan Selesai</h4>
                <p class="card-description">
                    Pesanan pelanggan yang sudah dihidangkan hari ini.
                </p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Meja</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Rincian</th>
                            </tr>
                        </thead>
                        <tbody id="tabelAntrianSelesai">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalRincian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pesanan</h5>
            </div>
            <div class="modal-body p-0">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning text-white">Nama</span>
                                </div>
                                <input type="text" id="nama" class="form-control" disabled aria-label="Amount (to the nearest dollar)">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning text-white">No Meja</span>
                                </div>
                                <input type="number" id="noMeja" class="form-control" disabled aria-label="Amount (to the nearest dollar)">
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table text-center bg-white" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jml</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tabelRincian">
                        <td colspan="5">Memuat data....</td>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning text-white">Rp.</span>
                                </div>
                                <input type="number" id="totalHarga" class="form-control" disabled aria-label="Amount (to the nearest dollar)" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="idTransaksi">
                <input type="hidden" id="statusTransaksi">
                <button type="button" class="btn btn-secondary" onclick="tutupModalRincian()">Tutup</button>
                <button type="button" class="btn btn-warning" onclick="proses()" id="proses">Bayar</button>
                <!-- Tombol Cetak Struk -->
                <button type="button" class="btn btn-info" onclick="cetakStruk()" id="btnCetakStruk" style="display:none;">Cetak Struk</button>
            </div>
        </div>
    </div>
</div>

<script>
    tampilkanAntrian()
    tampilkanAntrianSelesai()

    function tampilkanAntrian() {
        var isiPesanan = ""
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/antrian/dataAntrian',
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    for (let i = 0; i < data.length; i++) {
                        isiPesanan += "<tr><td>" + data[i].noMeja + "</td><td>" + data[i].nama + "</td><td>"

                        if (data[i].status == 0) {
                            isiPesanan += "<label class='badge badge-danger'>Diproses"
                        } else {
                            isiPesanan += "<label class='badge badge-success'>Memasak"
                        }

                        isiPesanan += "</label></td><td><button href='#' class='btn btn-inverse-warning btn-sm' onClick='modalRincian(" + data[i].id + ", \"" + data[i].nama + "\", " + data[i].noMeja + "," + data[i].status + ")'><i class='mdi mdi-format-list-bulleted-type'></i><i class='mdi mdi-food-fork-drink'></i></button></td></tr>"
                    }
                } else {
                    isiPesanan = "<td colspan='4'>Antrian Masih Kosong :)</td>"
                }
                $("#tabelAntrian").html(isiPesanan)
            }
        });
    }

    function tampilkanAntrianSelesai() {
        var isiPesanan = ""
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/antrian/dataAntrianSelesai',
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    for (let i = 0; i < data.length; i++) {
                        isiPesanan += "<tr><td>" + data[i].noMeja + "</td><td>" + data[i].nama + "</td><td><label class='badge badge-success'>Selesai :)</label></td><td><button href='#' class='btn btn-inverse-success btn-sm' onClick='modalRincian(" + data[i].id + ", \"" + data[i].nama + "\", " + data[i].noMeja + "," + data[i].status + ")'><i class='mdi mdi-playlist-check'></i><i class='mdi mdi-food'></i></button></td></tr>"
                    }
                } else {
                    isiPesanan = "<td colspan='4' class='text-center'>Antrian Masih Kosong :)</td>"
                }
                $("#tabelAntrianSelesai").html(isiPesanan)
            }
        });
    }

    function modalRincian(id, nama, noMeja, status) {
    $("#nama").val(nama);
    $("#noMeja").val(noMeja);
    $("#proses").show();

    tampilkanRincian(id);
    
    if (status == 0) {
        $("#proses").html("Bayar");
        $("#btnCetakStruk").hide();
    } else if (status == 1) {
        $("#proses").html("Selesai");
        $("#btnCetakStruk").hide();
    } else {
        $("#proses").hide();
        $("#btnCetakStruk").show();
    }

    $("#idTransaksi").val(id);
    $("#statusTransaksi").val(status);

    // Menampilkan modal rincian
    $("#modalRincian").modal("show");
}


    function proses() {
        var id = $("#idTransaksi").val()
        var status = $("#statusTransaksi").val()

        $.ajax({
            url: '<?= base_url() ?>/antrian/proses',
            method: 'post',
            data: "idTransaksi=" + id + "&statusTransaksi=" + status,
            dataType: 'json',
            success: function(data) {
                tampilkanAntrian()
                tampilkanAntrianSelesai()
                tutupModalRincian()
            }
        });
    }

    function tampilkanRincian(id) {
        var isiPesanan = ""
        var totalHarga = 0
        $.ajax({
            url: '<?= base_url() ?>/antrian/rincianPesanan',
            method: 'post',
            data: "idAntrian=" + id,
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    for (let i = 0; i < data.length; i++) {
                        totalHarga += data[i].harga * data[i].jumlah
                        isiPesanan += "<tr><td>" + data[i].nama + "</td><td>" + data[i].jumlah + "</td><td>" + formatRupiah(data[i].harga.toString()) + "</td><td>" + formatRupiah((data[i].harga * data[i].jumlah).toString()) + "</td></tr>"
                    }
                } else {
                    isiPesanan = "<td colspan='4'>Antrian Masih Kosong :)</td>"
                }
                $("#tabelRincian").html(isiPesanan)
                $("#totalHarga").val(formatRupiah(totalHarga.toString()))

            }
        });
    }

    function tutupModalRincian() {
        $("#modalRincian").modal("hide")
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function cetakStruk() {
        var nama = $("#nama").val();
        var noMeja = $("#noMeja").val();
        var totalHarga = $("#totalHarga").val();
        var rincian = document.getElementById('tabelRincian').innerHTML;

        // Membuka window baru untuk mencetak struk
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Struk Pesanan</title>');
        printWindow.document.write('<style>body {font-family: Arial, sans-serif; padding: 20px;} table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid #ddd; padding: 8px; text-align: center;} th {background-color: #f2f2f2;} .text-right {text-align: right;} h3 {text-align: center;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3>Struk Pesanan</h3>');
        printWindow.document.write('<p><strong>Nama Pembeli: </strong>' + nama + '</p>');
        printWindow.document.write('<p><strong>No Meja: </strong>' + noMeja + '</p>');
        printWindow.document.write('<p><strong>Total Harga: </strong>' + totalHarga + '</p>');
        printWindow.document.write('<table>');
        printWindow.document.write('<thead><tr><th>Nama Menu</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr></thead>');
        printWindow.document.write('<tbody>' + rincian + '</tbody>');
        printWindow.document.write('</table>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
<?php $this->endSection() ?>
