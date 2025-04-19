<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF Pemesanan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            text-align: center;
            padding: 8px;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table td {
            font-size: 12px;
        }
        .text-center {
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Laporan PDF Pemesanan Produk</h3>
        <p>Dari: <?= $tanggal_mulai ?> Sampai: <?= $tanggal_selesai ?></p>

        <div class="table-responsive">
            <table class="table">
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
                                <td>Rp <?= number_format($pesanan['subtotal'], 0, ',', '.') ?></td>
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
    </div>

</body>

</html>
