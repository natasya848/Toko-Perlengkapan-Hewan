<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10mm;
        }
        h2 {
            text-align: center;
            margin-bottom: 5mm;
        }
        h4 {
            text-align: center;
            margin-bottom: 10mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10mm;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tfoot {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Meowgic</h2>
    <h4>Periode: <?= date('d/m/Y', strtotime($tanggal_mulai)) ?> - <?= date('d/m/Y', strtotime($tanggal_selesai)) ?></h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Item</th> <!-- Menambahkan Nama Item -->
                <th>Metode</th>
                <th>Pendapatan</th>
                <th>Pengeluaran</th>
                <th>Total Keuangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($laporanKeuangan) && count($laporanKeuangan) > 0): ?>
                <?php $no = 1; foreach ($laporanKeuangan as $l): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($l['created_at'])) ?></td>
                        <td><?= isset($l['nama_item']) ?></td>
                        <td><?= $l['metode_pembayaran'] ?></td>
                        <td>Rp <?= number_format($l['total_harga'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($l['pengeluaran'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($l['total_keuangan'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data laporan keuangan tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">TOTAL</th>
                <th>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></th>
                <th>Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></th>
                <th>Rp <?= number_format($totalKeuangan, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
