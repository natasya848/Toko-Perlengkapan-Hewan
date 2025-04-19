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

    .btn-success {
        background-color: #a8e6cf; 
        border-color: #a8e6cf; 
        color: #333; 
    }

    .btn-success:hover {
        background-color: #c8f0d3;
        border-color: #c8f0d3; 
        color: #333; 
    }

    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
    }

    .custom-modal-content {
        position: relative;
        max-width: 80%;
        max-height: 80%;
        background: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .custom-modal img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 5px;
    }

    .custom-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 25px;
        cursor: pointer;
        color: black;
    }
</style>


<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Pembayaran</h3>
            </div>
        </div>
    </div>

<section class="section">
    <div class="card p-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table14">
                    <thead class="text-white">
                        <tr>
                            <th>No</th>
                            <th>Jenis Transaksi</th>
                            <th>ID Transaksi</th>
                            <th>Metode</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Bukti Pembayaran</th>
                            <th>Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pembayaran)) : ?>
                            <?php foreach ($pembayaran as $i => $row) : ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><span class="badge bg-info"><?= ucfirst($row->jenis_transaksi) ?></span></td>
                                    <td>#<?= $row->id_transaksi ?? '-' ?></td>
                                    <td><?= $row->metode ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($row->created_at)) ?></td>
                                    <td>
                                        <?php if ($row->status == 'Dikonfirmasi') : ?>
                                            <span class="badge bg-primary"><?= $row->status ?></span>
                                        <?php elseif ($row->status == 'Ditolak') : ?>
                                            <span class="badge bg-danger"><?= $row->status ?></span>
                                        <?php else : ?>
                                            <span class="badge bg-warning"><?= $row->status ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row->bukti_pembayaran) : ?>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="showModal('<?= base_url('uploads/bukti/' . $row->bukti_pembayaran) ?>')">
                                                    📄 Lihat
                                                </button>
                                        <?php else : ?>
                                            <em class="text-muted">Tidak ada</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row->status == 'Menunggu Konfirmasi') : ?>
                                            <form action="<?= base_url('home/konfirmasi/' . $row->id_pembayaran) ?>" method="post" class="d-inline">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Konfirmasi pembayaran ini?')">✔</button>
                                            </form>
                                            <form action="<?= base_url('home/tolak/' . $row->id_pembayaran) ?>" method="post" class="d-inline">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')">❌</button>
                                            </form>
                                        <?php elseif ($row->status == 'Dikonfirmasi' && $row->jenis_transaksi != 'grooming') : ?>
                                            <form action="<?= base_url('home/proses_pengiriman/' . $row->id_pembayaran) ?>" method="post">
                                                <button type="submit" class="btn btn-info btn-sm">Proses Pengiriman</button>
                                            </form>
                                        <?php elseif ($row->status == 'Ditolak') : ?>
                                            <span>-</span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data pembayaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>



<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="custom-close" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid">
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table14').DataTable({
            "paging": true,         
            "searching": true,      
            "info": true,           
            "lengthChange": true   
        });
    });
</script>
<script>
    function showModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('customModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar gambar
    window.onclick = function(event) {
        var modal = document.getElementById('customModal');
        if (event.target === modal) {
            closeModal();
        }
    };
</script>
<?= $this->endSection() ?>