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
                <h3>Pengiriman Produk</h3>
            </div>
        </div>
    </div>

   <section class="section">
        <div class="card p-3">
            <div class="card-body">
                <div class="table-responsive">  
                <table class="table table-striped" id="table16">
                    <thead class="text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Produk</th>
                            <th>Total Harga Produk</th>
                            <th>Biaya Pengiriman</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengiriman)): ?>
                            <?php $no = 1; foreach ($pengiriman as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $item->nama_pelanggan ?? '-' ?></td>
                                    <td><?= $item->daftar_produk ?? '-' ?></td>
                                    <td>Rp <?= isset($item->total_produk) ? number_format($item->total_produk, 0, ',', '.') : '0' ?></td>
                                    <td>Rp <?= isset($item->biaya_pengiriman) ? number_format($item->biaya_pengiriman, 0, ',', '.') : '0' ?></td>
                                    <td>Rp <?= isset($item->total_bayar) ? number_format($item->total_bayar, 0, ',', '.') : '0' ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= 
                                                $item->status == 'Diproses' ? 'bg-warning' : 
                                                ($item->status == 'Dikemas' ? 'bg-info' : 
                                                ($item->status == 'Dalam Proses Pengiriman' ? 'bg-primary' : 
                                                ($item->status == 'Sampai' ? 'bg-success' : 'bg-secondary')))
                                            ?>">
                                            <?= $item->status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (session()->get('level') == 3): ?>
                                            <form action="<?= base_url('home/update_status_pengiriman') ?>" method="post" class="d-flex">
                                                <input type="hidden" name="id_pengiriman" value="<?= $item->id_pengiriman ?>">
                                                <select name="status" class="form-select form-select-sm me-2">
                                                    <option value="Diproses" <?= $item->status == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                                    <option value="Dikemas" <?= $item->status == 'Dikemas' ? 'selected' : '' ?>>Dikemas</option>
                                                    <option value="Dalam Proses Pengiriman" <?= $item->status == 'Dalam Proses Pengiriman' ? 'selected' : '' ?>>Dalam Proses Pengiriman</option>
                                                    <option value="Sampai" <?= $item->status == 'Sampai' ? 'selected' : '' ?>>Sampai</option>
                                                    <option value="Selesai" <?= $item->status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
                                            </form>
                                        <?php elseif ($item->status == 'Sampai' && session()->get('level') == 2): ?>
                                            <form action="<?= base_url('pengiriman/diterima/' . $item->id_pengiriman) ?>" method="post">
                                                <button class="btn btn-success btn-sm" onclick="return confirm('Konfirmasi produk telah diterima?')">
                                                    Diterima
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data pengiriman produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table16').DataTable({
            "paging": true,         
            "searching": true,      
            "info": true,           
            "lengthChange": true   
        });
    });
</script>
<?= $this->endSection() ?>

