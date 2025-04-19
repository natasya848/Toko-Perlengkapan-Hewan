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
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: transparent !important;
        color: #333;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
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

    .btn-danger {
        background-color: #ff7e79;
        border-color: #ff7e79;
        color: white;
    }

    .btn-danger:hover {
        background-color: #ff6f61;
        border-color: #ff6f61;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f0f8ff;
        font-weight: bold;
        color: #333;
    }

    .table td {
        background-color: transparent;
        color: #333;
    }

    .table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Pemesanan</h3>
            </div>
        </div>
    </div>

     <section class="section">
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cetak Laporan Pemesanan</h5>
                    <div class="container">
						<form action="<?= base_url('home/lpnctk') ?>" method="get">
						    <div class="mb-3">
						        <label for="tanggal_mulai" class="form-label">Tanggal Mulai:</label>
						        <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
						    </div>
						    <div class="mb-3">
						        <label for="tanggal_selesai" class="form-label">Tanggal Selesai:</label>
						        <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required>
						    </div>
						    <div class="mb-3">
					            <label for="id_produk" class="form-label">Pilih Produk:</label>
					            <select name="id_produk" class="form-control">
					                <option value="">Semua Produk</option>
					                <?php foreach ($produkResult as $item): ?>
					                    <option value="<?= $item['id_produk'] ?>" <?= $id_produk == $item['id_produk'] ? 'selected' : '' ?>>
					                        <?= $item['nama_produk'] ?>
					                    </option>
					                <?php endforeach; ?>
					            </select>
					        </div>
						    <button type="submit" class="btn btn-primary ml-3">
						        <i class="fas fa-print"></i> Cetak
						    </button>
						</form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">PDF Laporan Pemesanan</h5>
                    <div class="container">
                        <form action="<?= base_url('home/lpnpdf') ?>" method="get">
                            <div class="mb-3">
						        <label for="tanggal_mulai" class="form-label">Tanggal Mulai:</label>
						        <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
						    </div>
						    <div class="mb-3">
						        <label for="tanggal_selesai" class="form-label">Tanggal Selesai:</label>
						        <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required>
						    </div>
						    <div class="mb-3">
					            <label for="id_produk" class="form-label">Pilih Produk:</label>
					            <select name="id_produk" class="form-control">
					                <option value="">Semua Produk</option>
					                <?php foreach ($produkResult as $item): ?>
					                    <option value="<?= $item['id_produk'] ?>" <?= $id_produk == $item['id_produk'] ? 'selected' : '' ?>>
					                        <?= $item['nama_produk'] ?>
					                    </option>
					                <?php endforeach; ?>
					            </select>
					        </div>
                            <button type="submit" class="btn btn-danger me-2">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>