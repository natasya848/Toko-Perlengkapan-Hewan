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
                <h3>Edit Data Jadwal Layanan Grooming</h3>
            </div>
        </div>
    </div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('home/edit5/' . $jadwal->id_jadwal) ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hari">Hari</label>
                            <select name="hari" id="hari" class="form-control" required>
                                <option value="">-- Pilih Hari --</option>
                                <?php
                                    $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    foreach ($hari_list as $hari) {
                                        $selected = ($jadwal->hari == $hari) ? 'selected' : '';
                                        echo "<option value=\"$hari\" $selected>$hari</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="<?= $jadwal->jam_mulai ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="<?= $jadwal->jam_selesai ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Tersedia" <?= $jadwal->status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                <option value="Tidak Tersedia" <?= $jadwal->status == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Update</button>
                        <a href="<?= base_url('home/jadwal_grooming') ?>" class="btn btn-secondary mt-2">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
