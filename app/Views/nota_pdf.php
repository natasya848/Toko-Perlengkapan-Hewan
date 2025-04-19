<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pesanan Produk</title>
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
    <h2>Nota Pesanan Produk</h2>
    <hr>
</div>

<div class="info">
    <p><strong>Nama Pelanggan:</strong> <?= $pesanan->nama_pelanggan ?></p>
    <p><strong>Status:</strong> <?= $pesanan->status ?></p>
    <p><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($pesanan->created_at)) ?></p>
</div>

    <table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Produk</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total = 0;
        $no = 1;
        foreach ($detail as $d):
            $total += $d->subtotal;
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $d->kode_produk ?></td>
            <td><?= $d->nama_produk ?></td>
            <td>Rp <?= number_format($d->harga, 0, ',', '.') ?></td>
            <td><?= $d->jumlah ?></td>
            <td>Rp <?= number_format($d->subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">Total</th>
            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>

<p style="margin-top:30px;">Terima kasih telah mempercayakan grooming di tempat kami!</p>
    
</body>
</html>
