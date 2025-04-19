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
            <div class="col-md-6">
                <h3>Input Pesanan</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            
            <div class="card-body">
                <form action="<?= base_url('home/input_pesanan') ?>" method="post">
                    <input type="hidden" name="total_harga" id="input-total-harga">

                    <div class="mb-3">
                        <label for="id_pelanggan" class="form-label">Pelanggan</label>
                        <select name="id_pelanggan" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($pelanggan as $value): ?>
                                <option value="<?= $value->id_pelanggan ?>"><?= $value->nama?> - <?= $value->id_pelanggan?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filter-kategori" class="form-label">Filter Kategori Produk</label>
                        <select id="filter-kategori" class="form-control">
                            <option value="">-- Semua Kategori --</option>
                            <?php foreach ($kategori_enum as $kat): ?>
                                <option value="<?= $kat ?>"><?= $kat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <table class="table" id="produk-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th><button type="button" class="btn btn-sm btn-success" id="add-row"><i class="fas fa-cart-plus"></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="produk[]" class="form-control produk-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        <?php foreach ($produk as $pr): ?>
                                            <option value="<?= $pr['id_produk'] ?>"
                                                data-harga="<?= $pr['harga'] ?>"
                                                data-kategori="<?= $pr['kategori'] ?>">
                                            <?= $pr['kode_produk'] ?> - <?= $pr['nama_produk'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                                <td><input type="text" class="form-control harga-input" readonly></td>
                                <td><input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" value="1" required></td>
                                <td>
                                    <input type="text" class="form-control subtotal-input" readonly>
                                    <input type="hidden" name="subtotal[]" class="subtotal-hidden">
                                </td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-end me-3">
                        <strong>Total: <span id="total-harga">Rp0</span></strong>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pesanan</button>
                </form>

                <script>
                    function formatRupiah(angka) {
                        return 'Rp' + parseInt(angka).toLocaleString('id-ID');
                    }

                    function hitungSubtotalDanTotal() {
                        let total = 0;
                        document.querySelectorAll('#produk-table tbody tr').forEach(row => {
                            const selected = row.querySelector('.produk-select');
                            const harga = parseInt(selected?.selectedOptions[0]?.getAttribute('data-harga') || 0);
                            const jumlah = parseInt(row.querySelector('.jumlah-input')?.value || 0);
                            const subtotal = harga * jumlah;

                            row.querySelector('.harga-input').value = harga ? formatRupiah(harga) : '';
                            row.querySelector('.subtotal-input').value = subtotal ? formatRupiah(subtotal) : '';

                            const hiddenSubtotal = row.querySelector('.subtotal-hidden');
                            if (hiddenSubtotal) hiddenSubtotal.value = subtotal;

                            total += subtotal;
                        });

                        document.getElementById('total-harga').innerText = formatRupiah(total);
                        document.getElementById('input-total-harga').value = total;
                    }

                    document.addEventListener('change', function(e) {
                        if (e.target.classList.contains('produk-select') || e.target.classList.contains('jumlah-input')) {
                            hitungSubtotalDanTotal();
                        }
                    });

                    document.getElementById('add-row').addEventListener('click', function() {
                        const table = document.querySelector('#produk-table tbody');
                        const newRow = table.rows[0].cloneNode(true);

                        newRow.querySelectorAll('input').forEach(el => el.value = '');
                        newRow.querySelector('.produk-select').selectedIndex = 0;

                        table.appendChild(newRow);

                        document.getElementById('filter-kategori').dispatchEvent(new Event('change'));

                        hitungSubtotalDanTotal();
                    });

                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-row')) {
                            const row = e.target.closest('tr');
                            const totalRows = document.querySelectorAll('#produk-table tbody tr').length;
                            if (totalRows > 1) row.remove();
                            hitungSubtotalDanTotal();
                        }
                    });

                    document.querySelector('form').addEventListener('submit', function(e) {
                        hitungSubtotalDanTotal(); 
                    });

                    document.getElementById('filter-kategori').addEventListener('change', function () {
                        const selectedKategori = this.value;

                        document.querySelectorAll('.produk-select').forEach(select => {
                            const currentValue = select.value;

                            Array.from(select.options).forEach(option => {
                                const kategori = option.getAttribute('data-kategori');
                                const isSelected = option.value === currentValue;

                                if (!kategori || option.value === '') {
                                    option.hidden = false;
                                    return;
                                }

                                if (!selectedKategori || kategori === selectedKategori || isSelected) {
                                    option.hidden = false; 
                                } else {
                                    option.hidden = true; 
                                }
                            });
                        });
                    });

                    </script>

<?= $this->endSection() ?>