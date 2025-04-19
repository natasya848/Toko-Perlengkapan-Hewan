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
            <div class="col-12 col-md-6">
                <h3>Input Layanan Grooming</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('home/tbooking') ?>" method="post">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Pelanggan</label>
                                <select name="id_pelanggan" class="form-control" required>
                                    <?php foreach($pelanggan as $p): ?>
                                        <option value="<?= $p->id_pelanggan ?>"><?= $p->nama ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Hewan</label>
                                <select name="id_hewan" class="form-control" required>
                                    <?php if (!empty($hewan)): ?>
                                        <?php foreach($hewan as $h): ?>
                                            <option value="<?= $h->id_hewan ?>"><?= $h->nama_hewan ?> (<?= $h->jenis ?>)</option>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <option value="">Tidak ada hewan terdaftar</option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tanggal Booking</label>
                                <input type="date" name="tanggal" id="tanggalBooking" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Jam Tersedia</label>
                                <select name="id_jadwal" id="jadwalSelect" class="form-control" required>
                                    <option value="">-- Pilih Jam --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3">
    <table class="table" id="layanan-table">
        <thead>
            <tr>
                <th style="width: 60%">Layanan</th>
                <th style="width: 30%">Harga</th>
                <th style="width: 10%">
                    <button type="button" class="btn btn-sm btn-success" id="add-layanan">
                        <i class="fa fa-plus"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="layanan[]" class="form-control layanan-select w-100" required>
                        <option value="">-- Pilih Layanan --</option>
                        <?php foreach ($layanan as $l): ?>
                            <option value="<?= $l->id_layanan ?>" data-harga="<?= $l->harga ?>">
                                <?= $l->nama_layanan ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="text" class="form-control harga-layanan" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-layanan"><i class="fa fa-trash"></i></button></td>
            </tr>
        </tbody>
    </table>

    <div class="text-end">
        <strong>Total: <span id="total-layanan">Rp0</span></strong>
        <input type="hidden" name="total_harga" id="input-total-layanan">
    </div>
</div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Input</button>
                    <a href="<?= base_url('home/rekap_grooming') ?>" class="btn btn-danger mt-3">Batal</a>
                </form>
            </div>
        </div>
    </section>
</div>


<script>
function formatRupiah(angka) {
    return 'Rp' + parseInt(angka || 0).toLocaleString('id-ID');
}

function hitungTotalLayanan() {
    let total = 0;
    document.querySelectorAll('#layanan-table tbody tr').forEach(row => {
        const selected = row.querySelector('.layanan-select');
        const harga = parseInt(selected?.selectedOptions[0]?.getAttribute('data-harga') || 0);
        row.querySelector('.harga-layanan').value = harga ? formatRupiah(harga) : '';
        total += harga;
    });

    document.getElementById('total-layanan').innerText = formatRupiah(total);
    document.getElementById('input-total-layanan').value = total;
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('layanan-select')) {
        hitungTotalLayanan();
    }
});

document.getElementById('add-layanan').addEventListener('click', function() {
    const table = document.querySelector('#layanan-table tbody');
    const newRow = table.rows[0].cloneNode(true);

    newRow.querySelector('.layanan-select').selectedIndex = 0;
    newRow.querySelector('.harga-layanan').value = '';

    table.appendChild(newRow);
    hitungTotalLayanan();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-layanan')) {
        const row = e.target.closest('tr');
        const totalRows = document.querySelectorAll('#layanan-table tbody tr').length;
        if (totalRows > 1) row.remove();
        hitungTotalLayanan();
    }
});

document.querySelector("form").addEventListener("submit", function() {
    hitungTotalLayanan(); // update total sebelum kirim
});
</script>

<script>
document.getElementById("tanggalBooking").addEventListener("change", function () {
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


<script>
document.getElementsByName("id_pelanggan")[0].addEventListener("change", function () {
    let id = this.value;
    fetch("<?= base_url('home/get_hewan_by_pelanggan/') ?>" + id)
        .then(res => res.json())
        .then(data => {
            let hewanSelect = document.getElementsByName("id_hewan")[0];
            hewanSelect.innerHTML = "";
            data.forEach(h => {
                hewanSelect.innerHTML += `<option value="${h.id_hewan}">${h.nama_hewan} (${h.jenis})</option>`;
            });
        });
});
</script>

<?= $this->endSection() ?>