<?php $this->extend('template') ?>

<?php $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <h2 class="card-title">Laporan</h2>
                <label id="pesanError" class="badge badge-danger"></label>
            </div>
            <div class="col-lg-2">
                <select class="form-control" onChange="tampilkan()" id="jenisLaporan">
                    <option value="1" selected>Semua</option>
                    <option value="2">Menu</option>
                    <option value="3">Pelanggan</option>
                </select>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="pillInput">Tanggal</label>
                    <input type="date" class="form-control input-pill" id="tanggalMulai" onChange="tampilkan()" placeholder="Rp">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="pillInput">Sampai</label>
                    <input type="date" class="form-control input-pill" onChange="tampilkan()" id="tanggalSelesai" placeholder="Rp">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <p>Pemasukan :</p>
                    <h5 class="card-title" id="pemasukan">Rp. 0</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive" id="tempatTabel">
            <!-- Tabel akan ditampilkan di sini -->
        </div>
        <!-- Tombol Print -->
        <div class="text-right">
            <button class="btn btn-primary" onclick="cetakLaporan()">Cetak Laporan</button>
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
                <button type="button" class="btn btn-warning" onclick="tutupModalRincian()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    settanggal()
    tampilkan()

    function settanggal() {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear() + "-" + (month) + "-" + (day);

        $("#tanggalMulai").val(today)
        $("#tanggalSelesai").val(today)
    }

    function tampilkan() {
        var tanggalMulai = $("#tanggalMulai").val()
        var tanggalSelesai = $("#tanggalSelesai").val()

        if (tanggalMulai > tanggalSelesai) {
            $("#pesanError").html("Tanggal Mulai tidak Boleh <br> Melebihi tanggal Selesai")
        } else {
            if ($("#jenisLaporan").val() == 1) {
                laporanSemua(tanggalMulai, tanggalSelesai);
            } else if ($("#jenisLaporan").val() == 2) {
                laporanMenu(tanggalMulai, tanggalSelesai)
            } else {
                laporanAntrian(tanggalMulai, tanggalSelesai)
            }
        }
    }

    function laporanSemua(tanggalMulai, tanggalSelesai) {
        $("#pesanError").html("")
        var keuntungan = 0;
        var totalKeuntungan = 0;
        var tabel = '<table id="tabelLaporan" class="display table table-striped table-hover" ><thead><tr><th>NO</th><th>TANGGAL</th><th>NAMA</th><th>HARGA</th><th>JUMLAH</th><th>TOTAL</th><th>KARYAWAN</th></tr></thead><tbody>'

        $.ajax({
            url: '<?= base_url() ?>/laporan/laporanSemua',
            method: 'post',
            data: "tanggalMulai=" + tanggalMulai + "&tanggalSelesai=" + tanggalSelesai,
            dataType: 'json',
            success: function(data) {
                for (let i = 0; i < data.length; i++) {
                    keuntungan = (data[i].harga * data[i].jumlah)
                    totalKeuntungan += keuntungan
                    tabel += '<tr>'
                    tabel += '<td>' + (i + 1) + '</td>'
                    tabel += '<td>' + data[i].tanggal + '</td>'
                    tabel += '<td>' + data[i].namaMenu + '</td>'
                    tabel += '<td>' + formatRupiah(data[i].harga) + '</td>'
                    tabel += '<td>' + data[i].jumlah + '</td>'
                    tabel += '<td>' + formatRupiah((data[i].harga * data[i].jumlah).toString()) + '</td>'
                    tabel += '<td>' + data[i].namaUser + '</td>'
                    tabel += '</tr>'
                }

                if (data.length == 0) {
                    tabel += "<td colspan='7' class='text-center'>Data Masih Kosong</td>"
                }
                tabel += '</tbody></table>'
                $("#tempatTabel").html(tabel)
                $("#pemasukan").html('Rp. ' + formatRupiah(totalKeuntungan.toString()))
            }
        });
    }

    function laporanMenu(tanggalMulai, tanggalSelesai) {
        $("#pesanError").html("")
        var keuntungan = 0;
        var totalKeuntungan = 0;
        var tabel = '<table id="tabelLaporan" class="display table table-striped table-hover" ><thead><tr><th>NO</th><th>NAMA</th><th>HARGA</th><th>JUMLAH</th><th>TOTAL</th></tr></thead><tbody>'

        $.ajax({
            url: '<?= base_url() ?>/laporan/laporanMenu',
            method: 'post',
            data: "tanggalMulai=" + tanggalMulai + "&tanggalSelesai=" + tanggalSelesai,
            dataType: 'json',
            success: function(data) {
                for (let i = 0; i < data.length; i++) {
                    keuntungan = (data[i].harga * data[i].jumlah)
                    totalKeuntungan += keuntungan
                    tabel += '<tr>'
                    tabel += '<td>' + (i + 1) + '</td>'
                    tabel += '<td>' + data[i].namaMenu + '</td>'
                    tabel += '<td>' + formatRupiah(data[i].harga) + '</td>'
                    tabel += '<td>' + data[i].jumlah + '</td>'
                    tabel += '<td>' + formatRupiah((data[i].harga * data[i].jumlah).toString()) + '</td>'
                    tabel += '</tr>'
                }

                if (data.length == 0) {
                    tabel += "<td colspan='5' class='text-center'>Data Masih Kosong</td>"
                }
                tabel += '</tbody></table>'
                $("#tempatTabel").html(tabel)
                $("#pemasukan").html('Rp. ' + formatRupiah(totalKeuntungan.toString()))
            }
        });
    }

    function laporanAntrian(tanggalMulai, tanggalSelesai) {
        $("#pesanError").html("")
        var keuntungan = 0;
        var totalKeuntungan = 0;
        var tabel = '<table id="tabelLaporan" class="display table table-striped table-hover" ><thead><tr><th>NO</th><th>TANGGAL</th><th>NAMA</th><th>HARGA</th><th>JUMLAH</th><th>TOTAL</th><th>STATUS</th></tr></thead><tbody>'

        $.ajax({
            url: '<?= base_url() ?>/laporan/laporanAntrian',
            method: 'post',
            data: "tanggalMulai=" + tanggalMulai + "&tanggalSelesai=" + tanggalSelesai,
            dataType: 'json',
            success: function(data) {
                for (let i = 0; i < data.length; i++) {
                    keuntungan = (data[i].harga * data[i].jumlah)
                    totalKeuntungan += keuntungan
                    tabel += '<tr>'
                    tabel += '<td>' + (i + 1) + '</td>'
                    tabel += '<td>' + data[i].tanggal + '</td>'
                    tabel += '<td>' + data[i].namaMenu + '</td>'
                    tabel += '<td>' + formatRupiah(data[i].harga) + '</td>'
                    tabel += '<td>' + data[i].jumlah + '</td>'
                    tabel += '<td>' + formatRupiah((data[i].harga * data[i].jumlah).toString()) + '</td>'
                    tabel += '<td>' + data[i].status + '</td>'
                    tabel += '</tr>'
                }

                if (data.length == 0) {
                    tabel += "<td colspan='7' class='text-center'>Data Masih Kosong</td>"
                }
                tabel += '</tbody></table>'
                $("#tempatTabel").html(tabel)
                $("#pemasukan").html('Rp. ' + formatRupiah(totalKeuntungan.toString()))
            }
        });
    }

    function formatRupiah(angka) {
        var number_string = angka.replace(",", "").toString();
        var sisa = number_string.length % 3;
        var rupiah = number_string.substr(0, sisa);
        var ribuan = number_string.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        return rupiah ? "Rp. " + rupiah : "";
    }

    function cetakLaporan() {
        var content = document.getElementById('tempatTabel').innerHTML;
        var pemasukan = document.getElementById('pemasukan').innerText;

        // Buat window baru untuk mencetak laporan
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Laporan</title>');
        printWindow.document.write('<style>body {font-family: Arial, sans-serif; padding: 20px;} table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid #ddd; padding: 8px; text-align: center;} th {background-color: #f2f2f2;}</style>');
        printWindow.document.write('</head><body>');
        
        printWindow.document.write('<h2>Laporan Penjualan</h2>');
        printWindow.document.write('<p><strong>Pemasukan: </strong>' + pemasukan + '</p>');
        printWindow.document.write(content); // Menambahkan konten tabel laporan
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.print();
    }
</script>
<?php $this->endSection() ?>
