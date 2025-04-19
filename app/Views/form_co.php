<?= $this->extend('layout1'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

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

    .list-group-item {
        background-color: #fff9f5;
        border: 1px solid #f5dad3;
        margin-bottom: 10px;
        border-radius: 10px;
        padding: 16px;
    }

    .keranjang-title {
        font-weight: bold;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #734c3d;
    }

    .pastel-scroll {
        max-height: 300px;
        overflow-y: auto;
    }

    .pastel-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .pastel-scroll::-webkit-scrollbar-thumb {
        background-color: #e7bfb3;
        border-radius: 10px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 1.5rem;
        color: #9c6b5b;
    }

    @media (max-width: 576px) {
        .keranjang-box {
            padding: 16px;
        }
    }
</style>

<section class="section">
    <div class="keranjang-box">
        <h2 class="keranjang-title">Checkout Pesanan</h2>

        <p><strong>Pelanggan:</strong> <?= isset($pelanggan['nama']) ? $pelanggan['nama'] : 'Tidak ditemukan' ?></p>

        <form action="<?= base_url('home/prosesCheckout') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="total_harga" id="input-total-harga">
            <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id_pelanggan'] ?>">
            <input type="hidden" name="biaya_pengiriman" id="biaya_pengiriman" value="10000">

           <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                <div id="map" style="height: 300px; margin-bottom: 10px; border-radius: 10px;"></div>
                <textarea name="alamat" id="alamat" class="form-control" rows="3" required disabled><?= $pelanggan['alamat'] ?? '' ?></textarea>
                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
            </div>


            <div class="section-title">Detail Produk</div>
            <div class="pastel-scroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($produk)): ?>
                            <?php foreach ($produk as $key => $item): ?>
                                <tr>
                                    <td>
                                        <?= $item->nama ?>
                                        <input type="hidden" name="produk[]" value="<?= $item->id_produk ?>">
                                    </td>
                                    <td>
                                        <?= 'Rp' . number_format($item->harga, 0, ',', '.') ?>
                                        <input type="hidden" name="harga[]" value="<?= $item->harga ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateJumlah('minus', <?= $key ?>)">-</button>
                                            <input type="number" name="jumlah[]" value="<?= $item->jumlah ?>" class="form-control form-control-sm w-25 mx-2 jumlah-input" min="1" id="jumlah_<?= $key ?>" onchange="updateSubtotal(<?= $key ?>)">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateJumlah('plus', <?= $key ?>)">+</button>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="subtotal-display" id="subtotal_<?= $key ?>"><?= 'Rp' . number_format($item->harga * $item->jumlah, 0, ',', '.') ?></span>
                                        <input type="hidden" name="subtotal[]" value="<?= $item->harga * $item->jumlah ?>" id="subtotal_hidden_<?= $key ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <label for="metode" class="form-label">Metode Pembayaran</label>
                <select name="metode" id="metode" class="form-control" required onchange="toggleBuktiPembayaran()">
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Cash" selected>Cash</option>
                </select>
            </div>

            <div id="buktiPembayaran" class="mb-3" style="display: none;">
                <label>Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" class="form-control">
                <small class="text-muted">Wajib upload jika bukan Cash</small>
            </div>

            <div class="mb-3">
                <label>Jenis Pengiriman</label>
                <select name="jenis_pengiriman" class="form-control" id="jenis-pengiriman" required onchange="updatePengiriman()">
                    <option value="Pick-Up" selected>Pick-Up</option>
                    <option value="Pengiriman">Pengiriman</option>
                </select>
            </div>

            <div class="text-end me-3 mb-3" id="biaya-pengiriman-div" style="display:none;">
                Biaya Pengiriman: <span id="biaya-pengiriman">Rp0</span>
            </div>

            <div class="text-end me-3 mb-3">
                <strong>Total: <span id="total-harga">Rp0</span></strong>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-pastel">Buat Pesanan</button>
            </div>


    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


    <script>
        let map, marker;

        document.addEventListener("DOMContentLoaded", function () {
            const batamCenter = [1.130475, 104.030453]; 
            const bounds = L.latLngBounds(
                [0.960000, 103.850000],
                [1.240000, 104.200000]  
            );

            map = L.map('map').setView(batamCenter, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            map.setMaxBounds(bounds);
            map.on('drag', function () {
                map.panInsideBounds(bounds, { animate: false });
            });

                L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: 'Cari lokasi...',
                })
                .on('markgeocode', function(e) {
                    const latlng = e.geocode.center;

                    if (!bounds.contains(latlng)) {
                        alert("Lokasi hasil pencarian di luar area Batam.");
                        return;
                    }

                    map.setView(latlng, 16);

                    if (marker) {
                        marker.setLatLng(latlng);
                    } else {
                        marker = L.marker(latlng).addTo(map);
                    }

                    document.getElementById('latitude').value = latlng.lat;
                    document.getElementById('longitude').value = latlng.lng;

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.display_name) {
                                document.getElementById('alamat').value = data.display_name;
                            } else {
                                alert("Alamat tidak ditemukan.");
                            }
                        })
                        .catch(() => alert("Gagal mengambil alamat."));
                })
                .addTo(map);


            map.on('click', function (e) {
                const latlng = e.latlng;

                if (!bounds.contains(latlng)) {
                    alert("Pilih lokasi di dalam area Batam.");
                    return;
                }

                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;

                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('alamat').value = data.display_name;
                        } else {
                            alert("Alamat tidak ditemukan.");
                        }
                    })
                    .catch(() => alert("Gagal mengambil alamat."));
            });
        });
    </script>

    <script>
        function toggleBuktiPembayaran() {
            var metode = document.getElementById('metode').value;
            var buktiPembayaran = document.getElementById('buktiPembayaran');
            if (metode === 'Cash') {
                buktiPembayaran.style.display = 'none';
            } else {
                buktiPembayaran.style.display = 'block';
            }
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function updateSubtotal(input) {
            const qty = parseInt(input.value);
            const harga = parseInt(input.closest('tr').querySelector('.harga').dataset.harga);
            const subtotal = qty * harga;
            input.closest('tr').querySelector('.subtotal').textContent = formatRupiah(subtotal);
            input.closest('tr').querySelector('input[name="subtotal[]"]').value = subtotal;

            hitungSubtotalDanTotal();
        }

        function hitungSubtotalDanTotal() {
            const subtotalInputs = document.querySelectorAll('input[name="subtotal[]"]');
            let total = 0;
            subtotalInputs.forEach(function(input) {
                total += parseInt(input.value);
            });

            let biayaPengiriman = parseInt(document.getElementById('biaya-pengiriman').dataset.biaya || 0);

            let grandTotal = total + biayaPengiriman;

            let totalInput = document.querySelector('input[name="total_harga"]');
            let totalDisplay = document.getElementById('total-harga');

            totalInput.value = grandTotal;
            totalDisplay.textContent = formatRupiah(grandTotal);
        }


        function updatePengiriman() {
            const jenis = document.getElementById('jenis-pengiriman').value;
            const biayaDiv = document.getElementById('biaya-pengiriman-div');
            const biayaText = document.getElementById('biaya-pengiriman');
            const biayaInput = document.getElementById('biaya_pengiriman');
            const alamatInput = document.getElementById('alamat');
            const metode = document.getElementById('metode');

            let biaya = 0;

            if (jenis === 'Pengiriman') {
                biaya = 10000;
                biayaDiv.style.display = 'block';
                alamatInput.disabled = false;
            } else {
                biaya = 0;
                biayaDiv.style.display = 'none';
                alamatInput.disabled = true;
                metode.value = 'Cash'; // default cash saat pick-up
                toggleBuktiPembayaran(); // sembunyikan upload
            }

            biayaText.textContent = formatRupiah(biaya);
            biayaText.dataset.biaya = biaya;
            biayaInput.value = biaya;

            hitungSubtotalDanTotal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            hitungSubtotalDanTotal();
            toggleBuktiPembayaran();
            updatePengiriman();
        });
    </script>

    <script>
        function updatePengiriman() {
            const jenis = document.getElementById('jenis-pengiriman').value;
            const biayaDiv = document.getElementById('biaya-pengiriman-div');
            const biayaText = document.getElementById('biaya-pengiriman');
            const alamatField = document.getElementById('alamat');
            const mapDiv = document.getElementById('map');

            let biaya = 0;

            if (jenis === 'Pengiriman') {
                biaya = 10000; // Biaya contoh
                biayaDiv.style.display = 'block';
                alamatField.readOnly = false;
                mapDiv.style.display = 'block';
            } else {
                biayaDiv.style.display = 'none';
                alamatField.readOnly = true;
                mapDiv.style.display = 'none';
            }

            biayaText.textContent = formatRupiah(biaya);
            biayaText.dataset.biaya = biaya;
            hitungSubtotalDanTotal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            updatePengiriman(); 
        });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        updatePengiriman();
        toggleBuktiPembayaran();
    });
</script>



<?= $this->endSection(); ?>
