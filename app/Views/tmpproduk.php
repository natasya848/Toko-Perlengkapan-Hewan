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
    <h4 class="mb-4 fw-bold">🛒 Semua Produk</h4>

    <!-- Filter Kategori & Pencarian -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="<?= base_url('home/tampilproduk') ?>">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="Makanan" <?= (isset($kategori_selected) && $kategori_selected == 'Makanan') ? 'selected' : '' ?>>Makanan</option>
                            <option value="Aksesoris" <?= (isset($kategori_selected) && $kategori_selected == 'Aksesoris') ? 'selected' : '' ?>>Aksesoris</option>
                            <option value="Kesehatan" <?= (isset($kategori_selected) && $kategori_selected == 'Kesehatan') ? 'selected' : '' ?>>Kesehatan</option>
                            <option value="Mainan" <?= (isset($kategori_selected) && $kategori_selected == 'Mainan') ? 'selected' : '' ?>>Mainan</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="cari" class="form-control" placeholder="Cari nama produk..." value="<?= esc($cari ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($produk)) : ?>
            <?php foreach ($produk as $p) : ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>" 
                             class="card-img-top" 
                             alt="<?= $p['nama_produk'] ?>" 
                             style="height: 250px; object-fit: cover;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <!-- Bagian Judul dan Harga -->
                            <div class="mb-3">
                                <h6 class="card-title mb-1"><?= $p['nama_produk'] ?></h6>
                                <p class="fw-semibold text-success mb-2">Rp<?= number_format($p['harga'], 0, ',', '.') ?></p>
                            </div>

                            <?php $deskripsi = strip_tags($p['deskripsi']); ?>
                            <div class="deskripsi-wrapper mt-2">
                                <div class="deskripsi-text" id="desc-<?= $p['id_produk'] ?>" style="display: none;">
                                    <?= esc($deskripsi) ?>
                                </div>
                                <?php if (!empty($deskripsi)) : ?>
                                    <a href="javascript:void(0);" onclick="toggleFullText(<?= $p['id_produk'] ?>)" id="link-<?= $p['id_produk'] ?>">Lihat Selengkapnya</a>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2 mt-auto">
                                <?php if (session()->get('isLoggedIn')) : ?>
                                    <a href="<?= base_url('home/pesan/' . $p['id_produk']) ?>" class="btn btn-sm btn-primary" style="height: 30px; width: 200px;">Beli Sekarang</a>
                                <?php else : ?>
                                    <a href="<?= base_url('home/login') ?>" class="btn btn-sm btn-primary" style="height: 30px; width: 200px;">Beli Sekarang</a>
                                <?php endif; ?>

                                <form action="<?= base_url('home/tambahKeKeranjang') ?>" method="post">
								    <?= csrf_field() ?>
								    <input type="hidden" name="id_produk" value="<?= $p['id_produk'] ?>">
								    <input type="hidden" name="id_layanan" value="<?= $p['id_layanan'] ?? null ?>">
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
                <p>Produk tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

