<?= $this->extend('layout1'); ?>

<?= $this->section('content'); ?>

<style>
    .card {
        margin-bottom: 15px;
    }

    .d-grid {
        gap: 10px;
    }

    .deskripsi-wrapper a {
        color: #1A3F59;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .deskripsi-wrapper a:hover {
        text-decoration: underline;
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
</style>

<script>
    function toggleFullText(id) {
        const desc = document.getElementById('desc-' + id);
        const link = document.getElementById('link-' + id);

        if (desc.style.display === 'none') {
            desc.style.display = 'block';
            link.innerText = 'Sembunyikan';
        } else {
            desc.style.display = 'none';
            link.innerText = 'Lihat Selengkapnya';
        }
    }
</script>

<div class="container mt-4">
<h4 class="mb-3 fw-bold">🐾 Semua Layanan Grooming</h4>

<!-- Form Filter Kategori dan Pencarian -->
<div class="mb-3">
    <form method="get" action="<?= base_url('home/tampilgrooming') ?>">
        <div class="input-group">
            <input type="text" name="cari" class="form-control me-2" placeholder="Cari Layanan..." value="<?= isset($cari) ? esc($cari) : '' ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>
</div>

<div class="row">
    <?php if (!empty($grooming)) : ?>
        <?php foreach ($grooming as $g) : ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <!-- Judul dan Harga -->
                        <div class="mb-3">
                            <h6 class="card-title mb-1"><?= $g->nama_layanan ?></h6>
                            <p class="fw-semibold text-success mb-2">Rp<?= number_format($g->harga, 0, ',', '.') ?></p>
                        </div>

                        <?php $deskripsi = strip_tags($g->deskripsi); ?>
                        <div class="deskripsi-wrapper mt-2">
                            <div class="deskripsi-text" id="desc-<?= $g->id_layanan ?>" style="display: none;">
                                <?= esc($deskripsi) ?>
                            </div>
                            <?php if (!empty($deskripsi)) : ?>
                                <a href="javascript:void(0);" onclick="toggleFullText(<?= $g->id_layanan ?>)" id="link-<?= $g->id_layanan ?>">Lihat Selengkapnya</a>
                            <?php endif; ?>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2 mt-auto">
                            <?php if (session()->get('isLoggedIn')) : ?>
                                <a href="<?= base_url('home/pesangr/' . $g->id_layanan) ?>" class="btn btn-sm btn-primary" style="height: 30px; width: 200px;">Beli Sekarang</a>
                            <?php else : ?>
                                <a href="<?= base_url('home/login') ?>" class="btn btn-sm btn-primary" style="height: 30px; width: 200px;">Beli Sekarang</a>
                            <?php endif; ?>

                            <form action="<?= base_url('home/tambahKeKeranjang') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_produk" value="">
                                <input type="hidden" name="id_layanan" value="<?= $g->id_layanan ?>">
                                <input type="hidden" name="jumlah" value="1">
                                <?php if (session()->get('isLoggedIn')) : ?>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="height: 30px; width: 50px;">
                                        🛒
                                    </button>
                                <?php else : ?>
                                    <a href="<?= base_url('home/login') ?>" class="btn btn-sm btn-outline-secondary" style="height: 30px; width: 50px;">
                                        🛒
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12 text-center text-muted">
            <p>Layanan grooming tidak ditemukan.</p>
        </div>
    <?php endif; ?>
</div>


<?= $this->endSection() ?>
