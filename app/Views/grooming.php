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
                <h3>Data Layanan Grooming</h3>
            </div>
    </div>

    <section class="section">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                <a href="<?= base_url('home/input_layanan') ?>">
                    <button class="btn btn-info"><i class="bi bi-plus"></i> Input</button>
                </a>

                <a href="<?= base_url('home/jadwal_grooming') ?>">
                    <button class="btn btn-info"></i> Jadwal Grooming</button>
                </a>

                <?php if (session()->get('level') == 4): ?>
                        <a href="<?= base_url('home/grooming1') ?>">
                            <button class="btn btn-primary">Data Layanan Grooming yang Dihapus</button>
                        </a>
                    <?php endif; ?>
            </div>
        </div>
            <div class="card-body">
                <table class="table table-striped" id="table8">
                    <thead class="text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Layanan</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Deskripsi</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($layanan_grooming)): ?>
                        <?php $no = 1; foreach ($layanan_grooming as $value): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value->nama_layanan ?></td>
                            <td>Rp <?= number_format($value->harga, 0, ',', '.') ?></td>
                            <td><?= $value->durasi ?> Menit</td>
                            <td><?= $value->deskripsi ?></td>
                            <td><span class="badge bg-primary"><?= $value->nama ?></span></td>
                            <td>
                                <div class="d-flex">
                                <a href="<?= base_url('home/edit_grooming/' . $value->id_layanan) ?>" class="btn btn-warning btn-sm me-2"><i class="fa fa-edit"></i></a>

                                 <a href="<?= base_url('home/deleteGrooming/' . $value->id_layanan) ?>" class="btn btn-danger btn-sm me-2" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="fa fa-trash"></i></a>
                                
                                <?php if (session()->get('level') == 4): ?>
                                <a href="<?= base_url('home/detail_grooming/' . $value->id_layanan) ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                <?php endif; ?>
                                </div>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data layanan grooming.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table8').DataTable({
            "paging": true,         
            "searching": true,      
            "info": true,           
            "lengthChange": true   
        });
    });
</script>
<?= $this->endSection() ?>

