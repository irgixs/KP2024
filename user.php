<?php $this->extend('template') ?>

<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tambah User</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-lg-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-lg-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-lg-3 col-form-label">Jabatan</label>
                        <div class="col-lg-9">
                            <select name="jabatan" id="jabatan" class="form-control">
                                <option value="0">Kasir</option>
                                <option value="1">Admin</option>
                                <option value="2">Owner</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="form-group row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-info" onclick="tambah()" id="tambah">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar User</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class=" text-info">
                        <th>
                            ID
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            JABATAN
                        </th>
                        <th>
                            Aksi
                        </th>
                    </thead>
                    <tbody id="tabelUser">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
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
                <button type="button" class="btn btn-secondary" onclick="$('#modalHapus').modal('hide')">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="idEdit" name="idEdit">
                <div class="form-group">
                    <label for="editNama">Nama</label>
                    <input type="text" class="form-control" id="editNama" name="editNama">
                </div>
                <div class="form-group">
                    <label for="editPassword">Password</label>
                    <input type="password" class="form-control" id="editPassword" name="editPassword">
                </div>
                <div class="form-group">
                    <label for="editJabatan">Jabatan</label>
                    <select name="editJabatan" id="editJabatan" class="form-control">
                        <option value="0">Kasir</option>
                        <option value="1">Admin</option>
                        <option value="2">Owner</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="updateUser()" class="btn btn-info">Update</button>
                <button type="button" class="btn btn-secondary" onclick="$('#modalEdit').modal('hide')">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    muatData()

function muatData() {
    $("#tambah").html('<i class="fa fa-spinner fa-pulse"></i> Memproses...')
    $.ajax({
        url: '<?= base_url() ?>/user/muatData',
        method: 'post',
        dataType: 'json',
        success: function(data) {
            var tabel = ''
            for (let i = 0; i < data.length; i++) {
                tabel += "<tr><td>" + data[i].id + "</td><td>" + data[i].nama + "</td><td>"
                tabel += data[i].rule == 1 ? "Admin" : (data[i].rule == 2 ? "Owner" : "Kasir");
                tabel += "</td><td><a href='#' onclick='tryHapus(" + data[i].id + ", \"" + data[i].nama + "\")' ><i class='mdi mdi-delete'></i></a> | ";
                tabel += "<a href='#' onclick='editUser(" + data[i].id + ", \"" + data[i].nama + "\", \"" + data[i].password + "\", " + data[i].rule + ")' ><i class='mdi mdi-pencil'></i></a></td></tr>";
            }
            if (!tabel) {
                tabel = '<td class="text-center" colspan="4">Data Masih kosong :)</td>';
            }
            $("#tabelUser").html(tabel);
            $("#tambah").html('Tambah');
        }
    });
}

function tambah() {
    if ($("#nama").val() == "") {
        $("#nama").focus();
    } else if ($("#password").val() == "") {
        $("#password").focus();
    } else {
        $.ajax({
            type: 'POST',
            data: 'nama=' + $("#nama").val() + '&password=' + $("#password").val() + '&jabatan=' + $("#jabatan").val(),
            url: '<?= base_url() ?>/user/tambah',
            dataType: 'json',
            success: function(data) {
                $("#nama").val("");
                $("#password").val("");
                $("#jabatan").val(0);
                muatData();
            }
        });
    }
}

    function tryHapus(id, nama) {
        $("#idHapus").val(id)
        $("#detailHapus").html(nama + " (" + id + ") ")
        $("#modalHapus").modal('show')
    }

    function hapus() {
        $("#hapus").html('<i class="fa fa-spinner fa-pulse"></i> Memproses..')
        var id = $("#idHapus").val()
        $.ajax({
            url: '<?= base_url() ?>/user/hapus',
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

    function editUser(id, nama, password, jabatan) {
        $("#idEdit").val(id);
        $("#editNama").val(nama);
        $("#editPassword").val(password);
        $("#editJabatan").val(jabatan);
        $("#modalEdit").modal('show');
    }

    function updateUser() {
        var id = $("#idEdit").val();
        var nama = $("#editNama").val();
        var password = $("#editPassword").val();
        var jabatan = $("#editJabatan").val();

        if (nama == "" || password == "") {
            if (nama == "") {
                $("#editNama").focus();
            } else {
                $("#editPassword").focus();
            }
        } else {
            $.ajax({
                type: 'POST',
                data: 'id=' + id + '&nama=' + nama + '&password=' + password + '&jabatan=' + jabatan,
                url: '<?= base_url() ?>/user/update',
                dataType: 'json',
                success: function(data) {
                    $("#modalEdit").modal('hide');
                    muatData();
                }
            });
        }
    }
</script>
<?php $this->endSection() ?>
