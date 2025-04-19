<?= $this->extend('layout1'); ?>
<?= $this->section('content'); ?>

<style>
    .keranjang-box {
        background-color: #fffaf7;
        padding: 24px;
        border-radius: 16px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-pastel {
        background-color: #bc8476;
        color: white;
        border-radius: 8px;
        padding: 8px 16px;
        border: none;
    }

    .btn-pastel:hover {
        background-color: #a96a63;
        color: white;
    }

    .form-label {
        color: #9c6b5b;
        font-weight: bold;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #f5dad3;
        padding: 12px;
    }

    .form-control:focus {
        border-color: #bc8476;
        box-shadow: 0 0 5px rgba(188, 132, 118, 0.5);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #734c3d;
        margin-bottom: 20px;
    }

    .select-style {
        background-color: #fff9f5;
        border: 1px solid #f5dad3;
        border-radius: 10px;
        padding: 12px;
    }

    .btn-submit {
        background-color: #a96a63;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
    }

    .btn-submit:hover {
        background-color: #bc8476;
    }

    @media (max-width: 576px) {
        .keranjang-box {
            padding: 16px;
        }
    }
</style>

<div class="container py-4">
  <div class="keranjang-box">
    <h3 class="section-title">Checkout Layanan Grooming</h3>
    <p><strong>Nama Pelanggan:</strong> <?= isset($pelanggan['nama']) ? $pelanggan['nama'] : 'Tidak ditemukan' ?></p>

    <?php if (empty($hewan)) : ?>
      <p>Anda belum mendaftarkan hewan. Silakan isi data berikut:</p>
      <form action="<?= base_url('home/simpanHewan') ?>" method="post">
        <input type="hidden" name="id_pelanggan" value="<?= $id_pelanggan ?>">
        <div class="mb-3">
          <label class="form-label">Nama Hewan</label>
          <input type="text" name="nama_hewan" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Jenis</label>
          <select name="jenis" class="form-control select-style" required>
            <option value="Kucing">Kucing</option>
            <option value="Anjing">Anjing</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Ras</label>
          <input type="text" name="ras" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Usia</label>
          <input type="text" name="usia" class="form-control" required>
        </div>
        <button type="submit" class="btn-pastel">Simpan Hewan</button>
      </form>

<?php else : ?>
    <?php if (!empty($keranjang)): ?>
        <form action="<?= base_url('home/prosesCheckoutLayanan') ?>" method="post">
            <input type="hidden" name="id_pelanggan" value="<?= esc($id_pelanggan) ?>">
            <input type="hidden" name="total_harga" value="<?= $total_harga ?>">

            <div class="mb-3">
                <label class="form-label">Layanan Dipilih</label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keranjang as $item): ?>
                            <tr>
                                <td><?= esc($item['nama_layanan']) ?></td>
                                <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="text-end">Total</th>
                            <th>Rp<?= number_format($total_harga, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Hewan</label>
                <select name="id_hewan" class="form-control select-style" required>
                    <?php foreach ($hewan as $h) : ?>
                        <option value="<?= $h['id_hewan'] ?>"><?= $h['nama_hewan'] ?> (<?= $h['jenis'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Grooming</label>
                <input type="date" name="tanggal" class="form-control" id="tanggalGrooming" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jadwal Grooming</label>
                <select name="id_jadwal" class="form-control select-style" id="jadwalSelect" required>
                    <option value="">-- Pilih Jam --</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Proses Checkout</button>
        </form>
    <?php endif; ?>
<?php endif; ?>
</div>

<script>
  document.getElementById("tanggalGrooming").addEventListener("change", function () {
    const tanggal = new Date(this.value);
    const hariIndo = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const hari = hariIndo[tanggal.getDay()];
    const jadwalSelect = document.getElementById("jadwalSelect");

    jadwalSelect.innerHTML = "<option value=''>Loading...</option>";

    fetch("<?= base_url('home/get_jadwal_by_hari') ?>/" + hari)
      .then(res => res.json())
      .then(data => {
        jadwalSelect.innerHTML = "<option value=''>-- Pilih Jam --</option>";
        
        data.forEach(j => {
          const jamMulai = j.jam_mulai.substring(0, 5);
          const jamSelesai = j.jam_selesai.substring(0, 5);
          jadwalSelect.innerHTML += `<option value="${j.id_jadwal}">${jamMulai} - ${jamSelesai}</option>`;
        });
      });
  });
</script>

<?= $this->endSection(); ?>
