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

    .deskripsi-wrapper a {
        color: #1A3F59;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .deskripsi-wrapper a:hover {
        text-decoration: underline;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Produk</h3>
            </div>
        </div>
    </div>

        <div class="mb-3">
        <form method="get" action="<?= base_url('home/produk') ?>">
            <div class="input-group">
                <select name="kategori" class="form-select me-2">
                    <option value="">Semua Kategori</option>
                    <option value="Makanan" <?= (isset($kategori_selected) && $kategori_selected == 'Makanan') ? 'selected' : '' ?>>Makanan</option>
                    <option value="Aksesoris" <?= (isset($kategori_selected) && $kategori_selected == 'Aksesoris') ? 'selected' : '' ?>>Aksesoris</option>
                    <option value="Kesehatan" <?= (isset($kategori_selected) && $kategori_selected == 'Kesehatan') ? 'selected' : '' ?>>Kesehatan</option>
                    <option value="Mainan" <?= (isset($kategori_selected) && $kategori_selected == 'Mainan') ? 'selected' : '' ?>>Mainan</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

   <section class="section">
    <div class="card p-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <a href="<?= base_url('home/input_produk') ?>">
                    <button class="btn btn-info"><i class="bi bi-plus"></i> Input</button>
                </a>
                
                <?php if (session()->get('level') == 4): ?>
                    <a href="<?= base_url('home/produk1') ?>">
                        <button class="btn btn-primary">Data Produk yang Dihapus</button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table6">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($produk)): ?>
                        <?php $no = 1; foreach ($produk as $value): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                           <td>
                                <img src="<?= base_url('uploads/produk/' . $value->gambar) ?>" style="width: 100px; height: 100px; object-fit: cover;">
                            </td>
                            <td><?= $value->kode_produk ?></td>
                            <td><?= $value->nama_produk ?></td>
                            <td><?= $value->kategori ?></td>
                            <td>Rp <?= number_format($value->harga, 0, ',', '.') ?></td>
                            <td><?= $value->stok ?></td>
                            <td>
                                <?php
                                    $deskripsi = strip_tags($value->deskripsi);
                                ?>
                                <div class="deskripsi-wrapper">
                                    <span id="full-<?= $value->id_produk ?>" style="display: none;"><?= esc($deskripsi) ?></span>
                                        <a href="javascript:void(0);" onclick="toggleDeskripsi(<?= $value->id_produk ?>)" id="toggle-link-<?= $value->id_produk ?>">Lihat Selengkapnya</a>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                <a href="<?= base_url('home/edit_produk/' . $value->id_produk) ?>" class="btn btn-warning btn-sm me-2"><i class="fa fa-edit"></i></a>
                                <a href="<?= base_url('home/deleteProduk/' . $value->id_produk) ?>" 
                                class="btn btn-danger btn-sm me-2" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i class="fa fa-trash"></i></a>

                                <?php if (session()->get('level') == 4): ?>
                                <a href="<?= base_url('home/detail_produk/' . $value->id_produk) ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data produk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>

<script>
    function toggleDeskripsi(id) {
        const shortText = document.getElementById('short-' + id);
        const fullText = document.getElementById('full-' + id);
        const link = document.getElementById('toggle-link-' + id);

        if (fullText.style.display === "none") {
            fullText.style.display = "inline";
            shortText.style.display = "none";
            link.innerText = "Sembunyikan";
        } else {
            fullText.style.display = "none";
            shortText.style.display = "inline";
            link.innerText = "Lihat Selengkapnya";
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('#table6').DataTable({
            "paging": true,         
            "searching": true,      
            "info": true,           
            "lengthChange": true   
        });
    });
</script>
<?= $this->endSection() ?>

