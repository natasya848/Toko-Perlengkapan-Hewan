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

    @media (max-width: 576px) {
        .keranjang-box {
            padding: 16px;
        }
    }
</style>

    <section class="section">
        <div class="keranjang-box">
            <div class="keranjang-title">Detail Pesanan</div>
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h6><strong>Nama Pelanggan:</strong> <?= $pesanan->nama_pelanggan ?></h6>
                    <h6><strong>Status:</strong> <?= $pesanan->status ?></h6>
                    <h6><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($pesanan->created_at)) ?></h6>
                </div>
            </div>

            <div class="pastel-scroll">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($detail as $d): 
                            $total += $d->subtotal;
                        ?>
                        <tr>
                            <td><?= $d->kode_produk ?></td>
                            <td><?= $d->nama_produk ?></td>
                            <td>Rp <?= number_format($d->harga, 0, ',', '.') ?></td>
                            <td><?= $d->jumlah ?></td>
                            <td>Rp <?= number_format($d->subtotal, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-center mt-4">
                <a href="<?= base_url('home/riwayat') ?>" class="btn btn-pastel">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection(); ?>
