<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Layanan Grooming</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table th,
        .table td {
            text-align: center;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
    </style>
</head>
    
<body>
    <div class="container mt-5">
         <h3 class="text-center mb-4">Laporan Layanan Grooming</h3>
        <p class="text-center">Dari: <?= $tanggal_mulai ?> Sampai: <?= $tanggal_selesai ?></p>

        <div class="table-responsive">
            <table class="table table-bordered mt-4">    
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Jadwal</th>
                        <th>Pelanggan</th>
                        <th>Hewan</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($groomingResult as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['layanan'] ?></td>
                        <td><?= $item['jam_mulai'] ?> - <?= $item['jam_selesai'] ?></td>
                        <td><?= $item['nama_pelanggan'] ?></td>
                        <td><?= $item['nama_hewan'] ?></td>
                        <td><?= $item['status'] ?></td>
                        <td>Rp<?= number_format($item['total_harga'], 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
 <div class="text-center">
            <a href="#" class="btn btn-secondary" onclick="window.print(); return false;">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>