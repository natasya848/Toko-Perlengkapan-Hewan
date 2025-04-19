<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Booking Grooming</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .info { margin-bottom: 10px; }
        .total { text-align: right; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

<div class="header">
    <h2>Nota Booking Grooming</h2>
    <hr>
</div>

<div class="info">
    <p><strong>Nama Pelanggan:</strong> <?= $booking->nama_pelanggan ?></p>
    <p><strong>Nama Hewan:</strong> <?= $booking->nama_hewan ?> (<?= $booking->jenis ?>)</p>
    <p><strong>Tanggal Booking:</strong> <?= date('d-m-Y', strtotime($booking->tanggal)) ?></p>
    <p><strong>Jam:</strong> <?= date('H:i', strtotime($booking->jam_mulai)) ?> - <?= date('H:i', strtotime($booking->jam_selesai)) ?> WIB</p>
    <p><strong>Status:</strong> <?= $booking->status ?></p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Layanan</th>
            <th>Petugas</th>
            <th>Durasi</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($layanan as $lay): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $lay->nama_layanan ?></td>
            <td><?= $lay->nama_petugas ?? '-' ?></td>
            <td><?= $lay->durasi ?> menit</td>
            <td>Rp <?= number_format($lay->harga, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>

<p style="margin-top:30px;">Terima kasih telah mempercayakan grooming di tempat kami!</p>

</body>
</html>
