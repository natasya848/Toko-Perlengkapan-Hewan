<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemesanan Produk</title>
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
        <h3 class="text-center mb-4">Laporan Pemesanan Produk</h3>
        <p class="text-center">Dari: <?= $tanggal_mulai ?> Sampai: <?= $tanggal_selesai ?></p>

        <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Tanggal Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pemesanan)): ?>
                        <?php $no = 1; foreach ($pemesanan as $pesanan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $pesanan['nama'] ?></td>
                                <td><?= $pesanan['nama_produk'] ?></td>
                                <td><?= $pesanan['jumlah'] ?></td>
                                <td><?= number_format($pesanan['subtotal'], 0, ',', '.') ?></td>
                                <td><?= date('d-m-Y', strtotime($pesanan['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pemesanan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
