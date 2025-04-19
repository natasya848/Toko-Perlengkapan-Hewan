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
                <h3>Edit Data Produk</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('home/edit3/' . $produk->id_produk) ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Upload Gambar Produk</label>
                            <input type="file" name="gambar" class="form-control">
                            <input type="hidden" name="gambar_lama" value="<?= esc($produk->gambar) ?>">
                            <?php if ($produk->gambar): ?>
                                <small class="text-muted">Gambar saat ini: <strong><?= $produk->gambar ?></strong></small><br>
                                <img src="<?= base_url('uploads/produk/' . $produk->gambar) ?>" width="100" class="mt-2">
                            <?php endif; ?>
                        </div>

                            <div class="form-group">
                                <label>Kode Produk</label>
                                <input type="text" name="kode_produk" value="<?= esc($produk->kode_produk) ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama_produk" value="<?= esc($produk->nama_produk) ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" name="kategori" value="<?= esc($produk->kategori) ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="text" id="harga" name="harga" value="<?= 'Rp ' . number_format($produk->harga, 0, ',', '.') ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" name="stok" value="<?= esc($produk->stok) ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" rows="4" class="form-control"><?= esc($produk->deskripsi) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update Produk</button>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const hargaInput = document.getElementById("harga");

    hargaInput.addEventListener("input", function () {
        let value = this.value.replace(/[^0-9]/g, "");
        this.value = formatRupiah(value, "Rp ");
    });

    function formatRupiah(angka, prefix) {
        let numberString = angka.replace(/[^,\d]/g, "").toString(),
            split = numberString.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        return prefix + rupiah;
    }

    document.querySelector("form").addEventListener("submit", function () {
        // Bersihkan format sebelum dikirim ke server
        hargaInput.value = hargaInput.value.replace(/[^\d]/g, "");
    });
});
</script>

<?= $this->endSection() ?>