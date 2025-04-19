<?= $this->extend('layout1'); ?>
<?= $this->section('content'); ?>

<style>
    .keranjang-box {
        background-color: #fffaf7;
        padding: 24px;
        border-radius: 16px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .keranjang-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 24px;
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

    .btn-primary {
        background-color: #a7d5f2;
        color: #333;
        border-radius: 8px;
        padding: 8px 16px;
        border: none;
    }

    .btn-primary:hover {
        background-color: #c9e7ff;
        border-color: #c9e7ff;
        color: #333;
    }

    .modal-content {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background-color: #fffafc;
    }

    .modal-header {
        background-color: #e3f2fd;
        border-bottom: none;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .modal-footer {
        border-top: none;
    }

    .modal-title {
        font-weight: 600;
        color: #333;
    }

    .modal-body label {
        font-weight: 500;
        color: #444;
    }

    .btn-success {
        background-color: #b2f2bb !important;
        border: none;
        color: #2e4d3f;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-success:hover {
        background-color: #a3e5ad !important;
        color: #2e4d3f;
    }

    .btn-secondary {
        background-color: #f3d9fa !important;
        border: none;
        color: #5a4d59;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #e8c8f1 !important;
        color: #5a4d59;
    }

    @media (max-width: 576px) {
        .keranjang-box {
            padding: 16px;
        }
    }
</style>

	
<div class="keranjang-box mt-4">
    <div class="keranjang-title">📦 Riwayat Pengiriman Produk</div>

<?php if (!empty($pengiriman)): ?>
    <div class="pastel-scroll">
        <?php foreach ($pengiriman as $kirim): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div><strong>Tanggal Pesanan:</strong> <?= $kirim->created_at ?></div>
                        <div><strong>Total Produk:</strong> Rp<?= number_format($kirim->total_produk, 0, ',', '.') ?></div>
                        <div><strong>Biaya Pengiriman:</strong> Rp<?= number_format($kirim->biaya_pengiriman, 0, ',', '.') ?></div>
                        <div><strong>Total:</strong> Rp<?= number_format($kirim->total_harga, 0, ',', '.') ?></div>
                        <div><strong>Status Pengiriman:</strong> <?= $kirim->status ?></div>
                    </div>
                    <div class="text-end">
                        <?php if ($kirim->status == 'Diproses'): ?>
                            <span class="badge bg-warning text-white">Diproses</span>
                        <?php elseif ($kirim->status == 'Dikemas'): ?>
                            <span class="badge bg-info text-dark">Dikemas</span>
                        <?php elseif ($kirim->status == 'Dalam Proses Pengiriman'): ?>
                            <span class="badge bg-primary">Dalam Pengiriman</span>
                        <?php elseif ($kirim->status == 'Sampai'): ?>
                            <a href="<?= base_url('home/konfirmasi_sampai/' . $kirim->id_pengiriman) ?>"
                               class="btn btn-sm btn-success"
                               onclick="return confirm('Apakah produk sudah Anda terima?')">
                                ✔️ Diterima
                            </a>
                        <?php elseif ($kirim->status == 'Selesai'): ?>
                           <span class="badge btn-success text-white">Selesai</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <strong>Produk yang Dipesan:</strong>
                    <ul>
                        <?php foreach ($kirim->produk_dipesan as $item): ?>
                            <li><?= $item->nama_produk ?> (<?= $item->jumlah ?> x Rp<?= number_format($item->harga, 0, ',', '.') ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-center text-muted">Belum ada riwayat pengiriman.</p>
<?php endif; ?>

</div>

<?= $this->endSection(); ?>
