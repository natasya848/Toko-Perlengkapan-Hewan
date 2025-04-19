<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            background: linear-gradient(to right, #e4e8cf, #e8cfe4); 
            font-family: 'Nunito', sans-serif;
            color: #333;
        }

        .card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            color: #333;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card h3 {
            font-weight: bold;
            font-size: 2rem;
        }

        .list-group-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 10px;
            margin-bottom: 8px;
            color: #333;
        }

        .badge {
            background-color: #f39ac7;
            color: white;
            font-weight: 500;
        }

        h5 {
            margin-top: 40px;
            font-weight: 600;
        }

        .section-title {
            font-size: 1.25rem;
            color: #555;
            margin-bottom: 15px;
        }

        .container {
            padding-bottom: 80px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4 text-dark"><i class="fas fa-user-cog me-2"></i> Dashboard Petugas</h3>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h5 class="section-title">Pesanan Produk</h5>
                    <h3><?= $totalPesanan ?></h3>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h5 class="section-title">Layanan Grooming</h5>
                    <h3><?= $totalGrooming ?></h3>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h5 class="section-title">Jadwal Hari Ini</h5>
            <ul class="list-group">
                <?php if (!empty($jadwalHariIni)): ?>
                    <?php foreach ($jadwalHariIni as $jadwal): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $jadwal->nama_hewan ?> - <?= $jadwal->nama_layanan ?>
                            <span class="badge"><?= $jadwal->jam_mulai ?> - <?= $jadwal->jam_selesai ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">Tidak ada jadwal hari ini</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="card p-4">
            <h5 class="section-title">Aktivitas Terakhir</h5>
            <ul class="list-group">
                <?php if (!empty($aktivitasTerakhir)): ?>
                    <?php foreach ($aktivitasTerakhir as $aktivitas): ?>
                        <li class="list-group-item">
                            <?= $aktivitas->aktivitas ?> 
                            <small class="text-muted float-end">(<?= $aktivitas->waktu ?>)</small>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">Belum ada aktivitas terakhir</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?= $this->endSection(); ?>
