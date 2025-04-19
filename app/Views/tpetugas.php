<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<style>
    body {
        background: linear-gradient(to right, #e4e8cf, #e8cfe4);  
        color: #333;
        font-family: 'Nunito', sans-serif;
    }

    .page-heading h3 {
        color: #4a4a6a !important;
    }

    .card {
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(10px);
        border-radius: 10px;
    }

    .card-header {
        background: transparent !important;
        color: #333;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
    }

    table.dataTable {
        background: transparent !important;
    }

    table.dataTable thead {
        background-color: transparent !important;
    }

    table.dataTable thead th {
        background-color: transparent !important;
        color: #2f2f2f !important; /* abu tua */
        border-bottom: 1px solid rgba(0,0,0,0.2);
        font-weight: bold;
    }

    table.dataTable tbody tr {
        background-color: transparent !important;
        color: #2c2c2c !important; /* warna gelap agar kontras */
    }

    table.dataTable tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }

    table.dataTable td {
        background-color: transparent !important;
        color: #2c2c2c !important;
    }

    .btn-primary {
        background-color: #a7d5f2;
        border-color: #a7d5f2;
        color: #333;
    }

    .btn-primary:hover {
        background-color: #c9e7ff;
        border-color: #c9e7ff;
        color: #333;
    }

    .btn-warning {
        background-color: #fce38a;
        border-color: #fce38a;
        color: #333;
    }

    .btn-danger {
        background-color: #ff7e79;
        border-color: #ff7e79;
        color: white;
    }

    .btn-info {
        background-color: #a5d8ff;
        border-color: #a5d8ff;
        color: #333;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
    }

    .bg-danger {
        background-color: #ff6f61 !important;
    }

    .bg-info {
        background-color: #7fcdff !important;
    }

    .bg-success {
        background-color: #a8e6cf !important;
    }

    .bg-warning {
        background-color: #ffe066 !important;
    }

    .bg-secondary {
        background-color: #c0c0c0 !important;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Input Petugas</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">

            <div class="card-body">
                <form action="<?= base_url('home/tpetugas') ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Petugas</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input type="text" class="form-control" name="no_hp" required>
                            </div>

                        	<div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" required>
                            </div>

                            <div class="form-group">
                                <label>Level</label>
                                <input type="text" class="form-control" value="Petugas" readonly>
                                <input type="hidden" name="level" value="3">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Input</button>
                    <a href="<?= base_url('home/petugas') ?>" class="btn btn-danger mt-3">Batal</a>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
