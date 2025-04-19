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

<div class="keranjang-box">
    <div class="keranjang-title">🕒 Riwayat Pesanan Produk</div>

    <?php if (!empty($pesanan)): ?>
        <div class="pastel-scroll">
            <?php foreach ($pesanan as $p): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div><strong>Tanggal:</strong> <?= $p->created_at ?></div>
                            <div><strong>Total:</strong> Rp<?= number_format($p->total_harga, 0, ',', '.') ?></div>
                            <div><strong>Status:</strong> <?= $p->status ?></div>
                        </div>
                        <div class="text-end">
                            <?php if ($p->status == 'Menunggu Pembayaran'): ?>
                                <button class="btn btn-sm btn-pastel btn-bayar"
                                        data-id="<?= $p->id_pesanan ?>"
                                        data-nama="<?= $p->nama_pelanggan ?>"
                                        data-total="<?= $p->total_harga ?>">
                                    💰 Bayar
                                </button>

                            <?php elseif ($p->status == 'Menunggu Konfirmasi'): ?>
                                <em>Menunggu konfirmasi</em>

                            <?php elseif ($p->status == 'Dikonfirmasi'): ?>
                                <a href="<?= base_url('home/selesai_pesanan/' . $p->id_pesanan) ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Tandai pesanan ini selesai?')">
                                    <i class="bi bi-check-circle"></i> Selesai
                                </a>

                            <?php elseif ($p->status == 'Dibatalkan'): ?>
                                <em class="text-danger">Dibatalkan</em>

                            <?php elseif ($p->status == 'Selesai'): ?>
                                <a href="<?= base_url('home/detail_pesanan/' . $p->id_pesanan) ?>" 
                                   class="btn btn-sm btn-primary btn-lg">🧾 Detail</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Belum ada riwayat pesanan.</p>
    <?php endif; ?>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaranProduk" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('home/simpan_pbyrpsn') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_pesanan" id="inputIdPesanan">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran Produk - <span id="namaPelangganPesanan"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Metode Pembayaran</label>
                        <select name="metode" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Jenis Pengiriman</label>
                        <select name="pengiriman_diperlukan" class="form-control" required>
                            <option value="1">Pengiriman</option>
                            <option value="0">Pick-Up</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control">
                        <small class="text-muted">Wajib upload jika bukan Cash</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Simpan</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-bayar').forEach(function (button) {
        button.addEventListener('click', function () {
            const idPesanan = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            document.getElementById('inputIdPesanan').value = idPesanan;
            document.getElementById('namaPelangganPesanan').textContent = nama;

            const modal = new bootstrap.Modal(document.getElementById('modalPembayaranProduk'));
            modal.show();
        });
    });

</script>

<div class="keranjang-box mt-4">
    <div class="keranjang-title">🐾 Riwayat Layanan Grooming</div>

    <?php if (!empty($grooming)): ?>
        <div class="pastel-scroll">
            <?php foreach ($grooming as $g): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div><strong>Tanggal Booking:</strong> <?= $g->tanggal ?></div>
                            <div><strong>Hewan:</strong> <?= $g->nama_hewan ?></div>
                            <div><strong>Status:</strong> <?= $g->status ?></div>

                          <?php
$today = date('Y-m-d');
$now = time(); // gunakan timestamp sekarang
$jadwalTime = strtotime($g->tanggal . ' ' . $g->jam_mulai); // waktu booking grooming
$selisih = $jadwalTime - $now;
?>


                        <?php if ($g->tanggal == $today && $g->status == 'Menunggu Konfirmasi'): ?>
                            <?php if ($g->konfirmasi_kedatangan == 'Belum Dikonfirmasi'): ?>
                                <?php if ($selisih <= 3600 && $selisih > 0): ?>
                                    <form method="post" action="<?= base_url('home/konfirmasiKedatangan/' . $g->id_booking) ?>">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="Akan Datang" required>
                                            <label class="form-check-label">Akan Datang</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" value="Batal Datang" required>
                                            <label class="form-check-label">Batal Datang</label>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Konfirmasi</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <div><strong>Status Konfirmasi:</strong>
                                    <?php if ($g->konfirmasi_kedatangan == 'Akan Datang'): ?>
                                        <span class="badge bg-info text-dark">✅ Akan Datang</span>
                                    <?php elseif ($g->konfirmasi_kedatangan == 'Batal Datang'): ?>
                                        <span class="badge bg-secondary">❌ Batal Datang</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <?php if ($g->status == 'Menunggu Konfirmasi'): ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>

                            <?php elseif ($g->status == 'Dikonfirmasi'): ?>
                                <span class="badge bg-success">Dikonfirmasi</span>

                            <?php elseif ($g->status == 'Selesai'): ?>
                                <a href="<?= base_url('home/detail_gr/' . $g->id_booking) ?>" class="btn btn-sm btn-primary">🧾 Detail</a>

                            <?php elseif ($g->status == 'Dibatalkan'): ?>
                                <span class="badge bg-danger">Dibatalkan</span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p class="text-center text-muted">Belum ada riwayat layanan grooming.</p>
    <?php endif; ?>
</div>


<script>
    document.querySelectorAll('.btn-bayar').forEach(function (button) {
        button.addEventListener('click', function () {
            const idPesanan = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            document.getElementById('inputIdPesanan').value = idPesanan;
            document.getElementById('namaPelangganPesanan').textContent = nama;

            const modal = new bootstrap.Modal(document.getElementById('modalPembayaranProduk'));
            modal.show();
        });
    });

</script>

<?= $this->endSection(); ?>
