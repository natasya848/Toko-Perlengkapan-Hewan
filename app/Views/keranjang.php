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

<div class="keranjang-box mt-4">
  <div class="keranjang-box">
    <h4 class="mb-3 fw-bold">🛒 Keranjang Anda</h4>

    <?php if (!empty($keranjang['produk'])) : ?>
      <h5 class="mt-3">Produk</h5>
      <div class="list-group pastel-scroll mb-3">
        <?php foreach ($keranjang['produk'] as $item) : ?>
          <div class="list-group-item d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-semibold"><?= esc($item['nama']) ?></h6>
              <p class="mb-1">Harga: Rp<?= number_format($item['harga'], 0, ',', '.') ?></p>
              <form action="<?= base_url('home/updateJumlahProduk') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_keranjang" value="<?= esc($item['id_keranjang']) ?>">
                <div class="d-flex align-items-center">
                  <button type="submit" name="action" value="minus" class="btn btn-sm btn-outline-secondary">-</button>
                  <input type="number" name="jumlah" value="<?= esc($item['jumlah']) ?>" class="form-control form-control-sm w-25 mx-2" min="1">
                  <button type="submit" name="action" value="plus" class="btn btn-sm btn-outline-secondary">+</button>
                </div>
              </form>
              <p class="mb-0">Total: Rp<?= number_format($item['jumlah'] * $item['harga'], 0, ',', '.') ?></p>
            </div>
            <div>
              <a href="<?= base_url('home/hapusDariKeranjang/' . $item['id_keranjang']) ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-end mt-3">
        <h6>Total Harga: Rp<?= number_format($totalProduk, 0, ',', '.') ?></h6>
        <a href="<?= base_url('home/checkoutProduk') ?>" class="btn btn-pastel">Checkout Produk</a>
      </div>
    <?php endif; ?>


    <?php if (!empty($keranjang['layanan'])) : ?>
      <h5 class="mt-4">Layanan Grooming</h5>
      <div class="list-group pastel-scroll mb-3">
        <?php foreach ($keranjang['layanan'] as $item) : ?>
          <div class="list-group-item d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-semibold"><?= esc($item['nama']) ?></h6>
              <p class="mb-1">Harga: Rp<?= number_format($item['harga'], 0, ',', '.') ?></p>
            </div>
            <div>
              <a href="<?= base_url('home/hapusDariKeranjang/' . $item['id_keranjang']) ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-end mt-3">
        <h6>Total Layanan: Rp<?= number_format($totalLayanan, 0, ',', '.') ?></h6>
        <a href="<?= base_url('home/checkoutLayanan') ?>" class="btn btn-pastel">Checkout Layanan</a>
      </div>
    <?php endif; ?>

    <?php if (empty($keranjang['produk']) && empty($keranjang['layanan'])) : ?>
      <p>Keranjang Anda kosong.</p>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>