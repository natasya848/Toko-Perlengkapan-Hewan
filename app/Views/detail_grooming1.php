<?= $this->extend('layout1'); ?>
<?= $this->section('content'); ?>

<style>
    .keranjang-box {
        background-color: #fffaf7;
        padding: 24px;
        border-radius: 16px;
        max-width: 900px;
        max-height: 700px;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-pastel {
        background-color: #bc8476;
        color: white;
        border-radius: 8px;
        padding: 8px 16px;
        border: none;
    }

    .btn-pastel:hover {
        background-color: #a96a63;
        color: white;
    }

    .list-group-item {
        background-color: #fff9f5;
        border: 1px solid #f5dad3;
        margin-bottom: 10px;
        border-radius: 10px;
        padding: 16px;
    }

    .keranjang-title {
        font-weight: bold;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #734c3d;
    }

    .pastel-scroll {
        max-height: 300px;
        overflow-y: auto;
    }

    .pastel-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .pastel-scroll::-webkit-scrollbar-thumb {
        background-color: #e7bfb3;
        border-radius: 10px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 1.5rem;
        color: #9c6b5b;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .col-left, .col-right {
        width: 40%; 
        margin-bottom: 1rem;
    }

    .table th, .table td {
        text-align: left;
        vertical-align: middle;
    }

    @media (max-width: 576px) {
        .keranjang-box {
            padding: 16px;
        }

    .col-left, .col-right {
            width: 100%; /
        }
    }
</style>

<section class="section">
    <div class="keranjang-box">
        <div class="keranjang-title">Detail Layanan Grooming</div>
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="col-left">
                <h6><strong>Nama Pelanggan:</strong> <?= $booking->nama_pelanggan ?></h6>
                <h6><strong>Nama Hewan:</strong> <?= $booking->nama_hewan ?> (<?= $booking->jenis ?>)</h6>
                <h6><strong>Status:</strong> <?= $booking->status ?></h6>
            </div>
            <div class="col-right">
                <h6><strong>Tanggal Booking:</strong> <?= date('d-m-Y', strtotime($booking->tanggal)) ?></h6>
                <h6><strong>Jam:</strong> <?= $jam_mulai ?> - <?= $jam_selesai ?></h6>
            </div>
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
            <div class="text-center mt-4">
                <a href="<?= base_url('home/riwayat') ?>" class="btn btn-pastel">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>