<?php $this->extend('template') ?>

<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-sm btn-success" onclick="tryTambah()">Tambah</button>
            </div>
            <div class="card-body text-center">
                <h2>Daftar Menu</h2>
                <hr>
                <table class="table">
                    <thead class=" text-info">
                        <th>ID</th>
                        <th>NAMA</th>
                        <th>JENIS</th>
                        <th>HARGA</th>
                        <th>STATUS</th>
                        <th>DESKRIPSI</th>
                        <th>FOTO</th>
                        <th>AKSI</th>
                    </thead>
                    <tbody id="tabelUser"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="idEdit">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Nama</span>
                            </div>
                            <input type="text" id="editNama" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Harga</span>
                            </div>
                            <input type="number" id="editHarga" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group">
                            <select name="jenis" id="editJenis" class="form-control">
                                <option value="1">Makanan</option>
                                <option value="2">Snack</option>
                                <option value="3">Minuman Dingin</option>
                                <option value="4">Minuman Panas</option>
                            </select>
                        </div>
                    </div>
                    <!-- Deskripsi Menu -->
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Deskripsi</span>
                            </div>
                            <textarea id="editDeskripsi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="updateMenu()" class="btn btn-info">Update</button>
                <button type="button" class="btn btn-secondary" onclick="tutupEdit()">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Nama</span>
                            </div>
                            <input type="text" id="nama" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Harga</span>
                            </div>
                            <input type="number" id="harga" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group">
                            <select name="jenis" id="jenis" class="form-control">
                                <option value="1">Makanan</option>
                                <option value="2">Snack</option>
                                <option value="3">Minuman Dingin</option>
                                <option value="4">Minuman Panas</option>
                            </select>
                        </div>
                    </div>
                    <!-- Deskripsi Menu -->
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Deskripsi</span>
                            </div>
                            <textarea id="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="tambah()" class="btn btn-info">Tambah</button>
                <button type="button" class="btn btn-secondary" onclick="tutupTambah()">Batal</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="idHapus" name="idHapus">
                <p>Apakah anda yakin ingin menghapus <b id="detailHapus">....</b> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="hapus()" class="btn btn-info">Hapus</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="tutupHapus()">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Foto <b id="namaUploadFoto"></b></h5>
            </div>
            <div class="modal-body text-center">
                <form enctype="multipart/form-data">
                    <input type="hidden" value="" id="idUploadFoto" name="idUpload">
                    <img src="" id="fotoMenu" style="width:50%">
                    <br>
                    <br>
                    <div class='alert alert-danger mt-2 d-none' id="err_file"></div>
                    <div class="alert displaynone" id="responseMsg"></div>
                    <input type="file" id="uploadFotoMenu" class="form-control" name="uploadFotomenu" value="Pilih foto" accept="image/*" onchange="ubahFoto(event)">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="upload()" class="btn btn-info">Upload</button>
                <button type="button" class="btn btn-secondary" onclick="tutupUpload()">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    muatData();

    function muatData() {
    $.ajax({
        url: '<?= base_url() ?>/menu/muatData',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            var tabel = '';
            for (let i = data.length - 1; i > -1; i--) {
                tabel += "<tr><td>" + data[i].id + "</td><td>" + data[i].nama + "</td><td>";
                if (data[i].jenis == 1) {
                    tabel += "Makanan";
                } else if (data[i].jenis == 2) {
                    tabel += "Snack";
                } else if (data[i].jenis == 3) {
                    tabel += "Minuman Dingin";
                } else {
                    tabel += "Minuman Panas";
                }
                tabel += "<td>" + data[i].harga + "</td><td><select class='form-control text-dark' id='status" + data[i].id + "' onChange='ubahStatus(" + data[i].id + ")'>";
                if (data[i].status == 0) {
                    tabel += "<option value='0' selected>Habis</option>";
                    tabel += "<option value='1'>Tersedia</option>";
                } else {
                    tabel += "<option value='0'>Habis</option>";
                    tabel += "<option value='1' selected>Tersedia</option>";
                }
                tabel += "</select></td><td>" + data[i].deskripsi + "</td>";  // Add Deskripsi to Table
                tabel += "<td><a href='#' onClick='tryUpload(" + data[i].id + ", \"" + data[i].nama + "\", \"" + data[i].foto + "\")'><i class='mdi mdi-upload'></i></a></td>";
                tabel += "<td><a href='#' onclick='tryEdit(" + data[i].id + ")'><i class='mdi mdi-pencil'></i> Edit</a> | ";
                tabel += "<a href='#' id='hapus" + data[i].id + "' onclick='tryHapus(" + data[i].id + ", \"" + data[i].nama + "\")' ><i class='mdi mdi-delete'></i> Hapus</a></td></tr>";
            }
            if (!tabel) {
                tabel = '<td class="text-center" colspan="8">Data Masih kosong :)</td>';
            }
            $("#tabelUser").html(tabel);
            $(".table").addClass('table-responsive');
        }
    });
}


    function tryEdit(id) {
        $.ajax({
            url: '<?= base_url() ?>/menu/getMenu',
            method: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
                $("#idEdit").val(data.id);
                $("#editNama").val(data.nama);
                $("#editHarga").val(data.harga);
                $("#editJenis").val(data.jenis);
                $("#modalEdit").modal('show');
            }
        });
    }

    function updateMenu() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/menu/update',
            data: {
                id: $("#idEdit").val(),
                nama: $("#editNama").val(),
                harga: $("#editHarga").val(),
                jenis: $("#editJenis").val(),
                deskripsi: $("#editDeskripsi").val()
            },
            dataType: 'json',
            success: function(data) {
                muatData();
                tutupEdit();
            }
        });
    }

    function tutupEdit() {
        $("#modalEdit").modal('hide');
    }

    function tryTambah() {
        $("#modalTambah").modal('show');
    }

    function tutupTambah() {
        $("#modalTambah").modal('hide');
    }

    function tambah() {
    // Ambil nilai input
    const nama = $("#nama").val().trim();
    const harga = $("#harga").val().trim();
    const jenis = $("#jenis").val();
    const deskripsi = $("#deskripsi").val().trim(); // Tambahkan deskripsi

    // Validasi input
    if (nama === "") {
        alert("Nama tidak boleh kosong.");
        $("#nama").focus();
        return;
    }

    if (harga === "") {
        alert("Harga tidak boleh kosong.");
        $("#harga").focus();
        return;
    }

    if (isNaN(harga)) {
        alert("Harga harus berupa angka.");
        $("#harga").focus();
        return;
    }

    if (deskripsi === "") {
        alert("Deskripsi tidak boleh kosong.");
        $("#deskripsi").focus();
        return;
    }

    // Kirim data ke server dengan AJAX
    $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/menu/tambah',
        data: {
            nama: nama,
            harga: harga,
            jenis: jenis,
            deskripsi: deskripsi // Kirim deskripsi ke server
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                alert(response.message || "Data berhasil ditambahkan.");
                // Reset form setelah berhasil
                $("#nama").val("");
                $("#harga").val("");
                $("#jenis").val(1);
                $("#deskripsi").val("");
                muatData(); // Fungsi untuk memuat ulang data
                tutupTambah(); // Fungsi untuk menutup modal/form
            } else {
                alert(response.message || "Gagal menambahkan data.");
            }
        },
        error: function(xhr, status, error) {
            // Tangani error dari server atau jaringan
            alert("Terjadi kesalahan: " + error);
        }
    });
}


    // Fungsi lainnya tetap sama (hapus, upload foto, dsb...)


    function tryHapus(id, nama) {
        $("#idHapus").val(id)
        $("#detailHapus").html(nama + " (" + id + ") ")
        $("#modalHapus").modal('show')
    }

    function hapus() {
        $("#hapus").html('<i class="fa fa-spinner fa-pulse"></i> Memproses..')
        var id = $("#idHapus").val()
        $.ajax({
            url: '<?= base_url() ?>/menu/hapus',
            method: 'post',
            data: "id=" + id,
            dataType: 'json',
            success: function(data) {
                $("#idHapus").val("")
                $("#detailHapus").html("")
                $("#modalHapus").modal('hide')
                $("#hapus").html('Hapus')
                muatData()
            }
        });
    }

    function tutupHapus() {
        $("#modalHapus").modal("hide")
    }

    function ubahStatus(id) {
        $.ajax({
            url: '<?= base_url() ?>/menu/ubahStatus',
            method: 'post',
            data: "id=" + id + "&status=" + $("#status" + id).val(),
            dataType: 'json',
            success: function(data) {}
        });
    }

    function tryUpload(id, nama, foto) {
    console.log("Icon upload clicked");
    $("#idUploadFoto").val(id)
    $.ajax({
        url: '<?= base_url() ?>/menu/getMenu',
        method: 'post',
        data: "id=" + id,
        dataType: 'json',
        success: function(data) {
            $("#fotoMenu").attr('src', '<?= base_url() . "/public/images/menu/" ?>' + data.foto + "?=" + new Date().getTime())
            $("#namaUploadFoto").html(data.nama)
            $("#modalUpload").modal("show");
        }
    });
}


function tryUpload(id, nama, foto) {
        $("#idUploadFoto").val(id)
        $.ajax({
            url: '<?= base_url() ?>/menu/getMenu',
            method: 'post',
            data: "id=" + id,
            dataType: 'json',
            success: function(data) {
                $("#fotoMenu").attr('src', '<?= base_url() . "/public/images/menu/" ?>' + data.foto + "?=" + new Date().getTime())
                $("#namaUploadFoto").html(data.nama)
                $("#modalUpload").modal("show")
            }
        });
    }

    function upload() {
        var files = $('#uploadFotoMenu')[0].files;

        if (files.length > 0) {
            var fd = new FormData();
            fd.append('file', files[0]);
            fd.append('namaMenu', $("#namaUploadFoto").html());
            fd.append('idMenu', $("#idUploadFoto").val());

            $('#responseMsg').hide();

            $.ajax({
                url: '<?= base_url() ?>/menu/upload',
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $('#err_file').removeClass('d-block');
                    $('#err_file').addClass('d-none');

                    if (response.success == 1) {
                        $('#responseMsg').removeClass("alert-danger");
                        $('#responseMsg').addClass("alert-success");
                        $('#responseMsg').html(response.message);
                        $('#responseMsg').show();

                        $('#responseMsg').hide();
                        $('#uploadFotoMenu').val("")

                        tutupUpload()
                    } else if (response.success == 2) {
                        $('#responseMsg').removeClass("alert-success");
                        $('#responseMsg').addClass("alert-danger");
                        $('#responseMsg').html(response.message);
                        $('#responseMsg').show();
                    } else {
                        $('#err_file').text(response.message);
                        $('#err_file').removeClass('d-none');
                        $('#err_file').addClass('d-block');
                    }
                },
                error: function(response) {
                    console.log("error : " + JSON.stringify(response));
                }
            });
        } else {
            $('#responseMsg').removeClass("alert-success");
            $('#responseMsg').addClass("alert-danger");
            $('#responseMsg').html("Pilih foto dulu ya.");
            $('#responseMsg').show();
        }
    }

    function tutupUpload() {
        $("#modalUpload").modal("hide")
    }

    function ubahFoto(event) {
        $("#fotoMenu").attr("src", URL.createObjectURL(event.target.files[0]))
    }
</script>
<?php $this->endSection() ?>
