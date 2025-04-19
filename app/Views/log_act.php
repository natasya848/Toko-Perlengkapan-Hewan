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
                <h3>Data Riwayat Aktivitas</h3>
            </div>
    </div>

    <section class="section">
        <div class="card p-3">
            <div class="card-body">
                <table class="table table-striped" id="table11">
					<thead class="text-white">
			                        <tr>
			                            <th>No</th>
			                            <th>ID User</th>
			                            <th>Nama User</th>
			                            <th>Username</th>
			                            <th>Aktivitas</th>
			                            <th>IP Address</th>
			                            <th>Waktu</th>
			                        </tr>
			                    </thead>
			                    <tbody>
								    <?php $no = 1; foreach ($logs as $log): ?>
								        <tr>
								            <td><?= $no++; ?></td>
								            <td><?= $log['id_user']; ?></td>
								            <td><?= $log['nama_user'] ?? 'User Tidak Diketahui'; ?></td>
								            <td><?= $log['username'] ?? '-'; ?></td>
								            <td><?= 'id_user=' . $log['id_user'] . ' ' . $log['aktivitas']; ?></td>
								            <td><?= $log['ip_address'] ?? 'Tidak Ada IP'; ?></td>
								            <td><?= date('d-m-Y H:i:s', strtotime($log['waktu'])); ?></td>
								        </tr>
								    <?php endforeach; ?>
								</tbody>
			                </table>
			            </div>
			        </div>
			    </section>
			</div>
		</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table11').DataTable({
            "paging": true,
            "searching": true,
            "info": true,
            "lengthChange": true
        });
    });
</script>
<?= $this->endSection() ?>
