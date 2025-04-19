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
            <div class="col-md-6">
                <h3>Detail Grooming</h3>
            </div>
        </div>
    </div>

    <section class="section">
    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
            <div>
                <h6><strong>Nama Pelanggan:</strong> <?= $booking->nama_pelanggan ?></h6>
                <h6><strong>Nama Hewan:</strong> <?= $booking->nama_hewan ?> (<?= $booking->jenis ?>)</h6>
                <h6>Status: <?= $booking->status ?></h6>
                <h6>Tanggal Booking: <?= date('d-m-Y', strtotime($booking->tanggal)) ?></h6>
                <h6>Jam: <?= $jam_mulai ?> - <?= $jam_selesai ?></h6>
            </div>
            <a href="<?= base_url('home/cetak_nota1/' . $booking->id_booking) ?>" class="btn btn-info mt-2" target="_blank">
                <i class="bi bi-printer"></i> Cetak Bukti
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Petugas</th>
                        <th>Layanan</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                    </tr>
                </thead>
               <tbody>
                    <?php 
                    $total = 0;
                    foreach ($layanan as $lay) : 
                        $total += $lay->harga;
                    ?>
                        <tr>
                            <td><?= $lay->nama_petugas ?? '-' ?></td>
                            <td><?= $lay->nama_layanan ?></td>
                            <td><?= $lay->durasi ?> menit</td>
                            <td>Rp <?= number_format($lay->harga, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="position-fixed bottom-3 end-3">
                <a href="<?= base_url('home/rekap_grooming') ?>" class="btn btn-secondary shadow">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>


<?= $this->endSection() ?>
