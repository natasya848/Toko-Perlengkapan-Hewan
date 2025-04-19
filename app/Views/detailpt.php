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

<div class="container">
    <div class="card">
        <div class="card-header bg-info text-white text-center">
            <h4>Detail Petugas</h4>
        </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Nomor HP</th>
                            <th>Alamat</th>
                            <th>Level</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Deleted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= esc($petugas['nama']) ?></td>
                            <td><?= esc($petugas['email']) ?></td>
                            <td><?= esc($petugas['jenis_kelamin']) ?></td>
                            <td><?= esc($petugas['no_hp']) ?></td>
                            <td><?= esc($petugas['alamat']) ?></td>
                            <td><span class="badge bg-info"><?= ($petugas['level'] == 3) ? 'Petugas' : $petugas['level'] ?></span></td>
                            <td><?= esc($petugas['created_at']) ?></td>
                            <td><?= esc($petugas['updated_at'] ?? '-') ?></td>
                            <td><?= esc($petugas['deleted_at'] ?? '-') ?></td>
                        </tr>
                    </tbody>
                    </table>
                <a href="<?= base_url('home/petugas') ?>" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

<?= $this->endSection(); ?>