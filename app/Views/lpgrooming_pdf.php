<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF Layanan Grooming</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
            background-color: #f9f9f9;
            color: #333;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
            padding: 6px 0;
            color: #6b4c9a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th {
            background-color: #dcd6f7;
            color: #333;
            padding: 10px;
            border: 1px solid #aaa;
        }
        td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f3f0ff;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        tfoot th {
            background-color: #c3bef0;
            font-weight: bold;
        }
    </style>
</head>
    
<body>
    <div class="container mt-5">
         <h3 class="text-center mb-4">Laporan PDF Layanan Grooming</h3>
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
    </div>

</body>

</html>