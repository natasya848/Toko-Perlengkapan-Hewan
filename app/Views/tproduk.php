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
                <h3>Input Produk</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">

            <div class="card-body">
                <form action="<?= base_url('home/tproduk') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Upload Gambar Produk</label>
                                <input type="file" name="gambar" id="gambar" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Kode Produk</label>
                                <input type="text" class="form-control" name="kode_produk" required>
                            </div>

                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" required>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Aksesoris">Aksesoris</option>
                                    <option value="Kesehatan">Kesehatan</option>
                                    <option value="Mainan">Mainan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga (Rp)</label>
                                <input type="text" id="harga" name="harga" class="form-control" required>
                            </div>

                        	<div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control" name="stok" required>
                            </div>

                            <div class="mb-3">
                              <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Tulis deskripsi produk di sini"></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Input</button>
                    <a href="<?= base_url('home/produk') ?>" class="btn btn-danger mt-3">Batal</a>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let hargaInput = document.getElementById("harga");

    hargaInput.addEventListener("input", function () {
        let value = this.value.replace(/\D/g, "");

        this.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.querySelector("form").addEventListener("submit", function () {
        hargaInput.value = hargaInput.value.replace(/\./g, "");
    });
});
</script>
<?= $this->endSection() ?>
