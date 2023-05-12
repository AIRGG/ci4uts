<?= $this->extend('uts/templates/base_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h2>Mahasiswa</h2>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary" onclick="addForm()">Tambah</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-responsive table-striped">
            <thead>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi</th>
                <th>Foto</th>
                <th>Action</th>
            </thead>
            <tbody id="mainTBody">
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM -->
<div class="modal fade" id="formProdi" tabindex="-1" aria-labelledby="formProdi" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formProdi">Form Mahasiswa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="event.preventDefault()" id="mainForm">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <input type="hidden" name="oldid" value="" />
                    <input type="hidden" name="foto-old" value="" />
                    <label>NIM</label><br>
                    <input class="form-control" type="text" name="nim" required><br><br>
                    <label>Nama</label><br>
                    <input class="form-control" type="text" name="nama" required><br><br>
                    <label>Prodi</label><br>
                    <select class="form-select" name="prodi" id="prodiSelect" required>
                    </select><br><br>
                    <label for="formFile" class="form-label">Foto</label><br>
                    <input class="form-control" type="file" name="foto" id="formFile">
                    <img id="imgEdit" src="" style="height: 100px; width: auto;">
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" onclick="prosesForm()">Save</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('jscustom') ?>
<script>
    let act = 'add'
    var myModal = new bootstrap.Modal(document.getElementById("formProdi"), {});
    let datanya = []
    let datanyaProdi = []

    function getDataAll() {
        $.get('/mahasiswa/get-all').then((data) => {
            let htmlTBody = ''
            data.forEach((dt, idx) => {
                htmlTBody += `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${dt['nim']}</td>
                    <td>${dt['nama']}</td>
                    <td>${dt['nama_prodi']}</td>
                    <td>
                        <img src="/foto/${dt['foto']}" style="height: 100px; width: auto;" />
                    </td>
                    <td>
                        <button class="btn btn-success" onclick="editForm(${idx})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteForm(${idx})">Hapus</button>
                    </td>
                </tr>
                `
            })
            datanya = data
            $('#mainTBody').html(htmlTBody)
        })
    }
    function getDataProdi() {
        $.get('/prodi/get-all').then((data) => {
            let htmlTBody = ''
            data.forEach((dt, idx) => {
                htmlTBody += `
                <option value="${dt['id']}">${dt['nama_prodi']}</option>
                `
            })
            datanyaProdi = data
            $('#prodiSelect').html(htmlTBody)
        })
    }

    function setData(idx) {
        let datanow = datanya[idx]
        $('input[name="oldid"]').val(datanow['nim'])
        $('input[name="nim"]').val(datanow['nim'])
        $('input[name="nama"]').val(datanow['nama'])
        $('select[name="prodi"]').val(datanow['id_prodi'])
        $('input[name="foto-old"]').val(datanow['foto'])
        $('#imgEdit').attr('src', `/foto/${datanow['foto']}`)
    }
    function clearData() {
        $('input[name="oldid"]').val('')
        $('input[name="nim"]').val('')
        $('input[name="nama"]').val('')
        $('select[name="prodi"]').val('')
        $('input[name="foto"]').val('')
        $('input[name="foto-old"]').val('')
        $('#imgEdit').attr('src', ``)
    }

    function addForm() {
        act = 'add'
        myModal.show();
        clearData()
    }

    function editForm(idx) {
        setData(idx)
        act = 'edit'
        myModal.show();
    }

    function deleteForm(idx) {
        setData(idx)
        act = 'delete'
        Swal.fire({
            title: 'Yakin?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                prosesForm()
            }
        })
    }

    function prosesForm() {
        let url = '/mahasiswa/' + act
        let formdata = new FormData(document.getElementById("mainForm"))
        $.ajax({
            url: url,
            method: 'post',
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response)
                Swal.fire(
                    'Success',
                    response.message,
                    'success'
                );
                myModal.hide()
                getDataAll();
            }
        });
    }

    getDataAll();
    getDataProdi()
</script>
<?= $this->endSection() ?>
