<?= $this->extend('uts/templates/base_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h2>Prodi</h2>
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
                <th>Nama Prodi</th>
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
                <h1 class="modal-title fs-5" id="formProdi">Form Prodi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="event.preventDefault()" id="mainForm">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <input type="hidden" name="oldid" value="" />
                    <label>Nama Prodi</label><br>
                    <input class="form-control" type="text" name="nama_prodi" required><br><br>
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

    function getDataAll() {
        $.get('/prodi/get-all').then((data) => {
            let htmlTBody = ''
            data.forEach((dt, idx) => {
                htmlTBody += `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${dt['nama_prodi']}</td>
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

    function setData(idx) {
        let datanow = datanya[idx]
        $('input[name="oldid"]').val(datanow['id'])
        $('input[name="nama_prodi"]').val(datanow['nama_prodi'])
    }
    function clearData() {
        $('input[name="oldid"]').val('')
        $('input[name="nama_prodi"]').val('')
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
        let url = '/prodi/' + act
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
</script>
<?= $this->endSection() ?>
