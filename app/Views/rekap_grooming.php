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
        color: #2f2f2f !important;
        border-bottom: 1px solid rgba(0,0,0,0.2);
        font-weight: bold;
    }

    table.dataTable tbody tr {
        background-color: transparent !important;
        color: #2c2c2c !important;
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
            <div class="col-md-6">
                <h3>Rekap Layanan Grooming</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('home/inputg') ?>">
                        <button class="btn btn-info"><i class="bi bi-plus"></i> Input</button>
                    </a>
                </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table11">
                    <thead class="text-white">  
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Hewan</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($booking as $b): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $b->nama_pelanggan ?></td>
                                <td><?= $b->nama_hewan ?></td>
                                <td><?= $b->layanan ?></td>
                                <td><?= date('d-m-Y', strtotime($b->tanggal)) ?></td>
                                <td><?= date('H:i', strtotime($b->jam_mulai)) ?> - <?= date('H:i', strtotime($b->jam_selesai)) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $b->status == 'Selesai' ? 'success' : 
                                        ($b->status == 'Dibatalkan' ? 'danger' : 
                                        ($b->status == 'Dikonfirmasi' ? 'success' : 
                                        ($b->status == 'Menunggu Pembayaran' ? 'primary' : 'secondary')))
                                    ?>">
                                        <?= $b->status ?>
                                    </span>
                                </td>
                                <td>
                        <?php if ($b->status == 'Menunggu'): ?>
                            <a href="<?= base_url('home/selesai_grooming/'.$b->id_booking) ?>" 
                                class="btn btn-sm btn-success"
                                onclick="return confirm('Tandai grooming ini selesai?')">✔ Tandai Selesai</a>

                        <?php elseif ($b->status == 'Menunggu Pembayaran'): ?>
                            <button class="btn btn-sm btn-primary btn-bayar"
                                data-id="<?= $b->id_booking ?>"
                                data-nama="<?= $b->nama_pelanggan ?>">
                                💰 Bayar</button>

                        <?php elseif ($b->status == 'Dikonfirmasi'): ?>
                        -

                        <?php elseif ($b->status == 'Menunggu Konfirmasi'): ?>
                        -

                        <?php elseif ($b->status == 'Selesai'): ?>
                            <a href="<?= base_url('home/detail_gr/'.$b->id_booking) ?>" class="btn btn-sm btn-info">🧾 Detail</a>

                            <?php elseif ($b->status == 'Dibatalkan'): ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
</section>

      <!-- Modal Pembayaran (Global) -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('home/simpan_pembayaran_grooming') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_booking" id="inputIdBooking">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran Grooming - <span id="namaPelanggan"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Metode Pembayaran</label>
                        <select name="metode" class="form-control" required>
                            <option value="">- Pilih -</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control">
                        <small class="text-muted">Wajib upload jika bukan Cash</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Simpan</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#table11').DataTable({
            "paging": true,
            "searching": true,
            "info": true,
            "lengthChange": true
        });

        document.querySelectorAll('.btn-bayar').forEach(function (button) {
        button.addEventListener('click', function () {
            const idBooking = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            document.getElementById('inputIdBooking').value = idBooking;
            document.getElementById('namaPelanggan').textContent = nama;

            const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
            modal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
