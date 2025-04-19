<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

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

	<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="profile-container text-center bg-light p-4 rounded shadow-sm">
                <!-- Foto Profil -->
                <div class="d-flex justify-content-center mb-3">
                    <img src="<?= base_url('uploads/' . ($user['foto'] ?? 'default.png')) ?>" class="profile-img rounded-circle border" alt="Foto Profil" style="width: 120px; height: 120px; object-fit: cover;">
                </div>

                <h3 class="mt-2 text-dark"><?= $user['nama'] ?></h3>
                <p class="text-muted mb-4"><?= $user['username'] ?></p>

                <table class="table table-sm table-borderless">
                    <tr>
                        <th class="text-start">Level Akses</th>
                        <td class="text-start">
                            <?php
                            if ($user['level'] == 1) echo "Admin";
                            elseif ($user['level'] == 2) echo "Pelanggan";
                            elseif ($user['level'] == 3) echo "Petugas";
                            elseif ($user['level'] == 4) echo "Super Admin";
                            ?>
                        </td>
                    </tr>

                    <?php if ($user['level'] == 2 && $extraData): ?>
                        <tr>
                            <th class="text-start">Nama</th>
                            <td class="text-start"><?= $extraData['nama'] ?></td>
                        </tr>
                        <tr>
                            <th class="text-start">No. HP</th>
                            <td class="text-start"><?= $extraData['no_hp'] ?></td>
                        </tr>
                        <tr>
                            <th class="text-start">Alamat</th>
                            <td class="text-start"><?= $extraData['alamat'] ?></td>
                        </tr>
                    <?php elseif ($user['level'] == 3 && $extraData): ?>
                        <tr>
                            <th class="text-start">Nama</th>
                            <td class="text-start"><?= $extraData['nama_petugas'] ?></td>
                        </tr>
                        <tr>
                            <th class="text-start">Jenis Kelamin</th>
                            <td class="text-start"><?= $extraData['jenis_kelamin'] ?></td>
                        </tr>
                        <tr>
                            <th class="text-start">No. HP</th>
                            <td class="text-start"><?= $extraData['no_hp'] ?></td>
                        </tr>
                        <tr>
                            <th class="text-start">Alamat</th>
                            <td class="text-start"><?= $extraData['alamat'] ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th class="text-start">Nama</th>
                            <td class="text-start"><?= $user['nama'] ?></td>
                        </tr>
                    <?php endif; ?>
                </table>

                <div class="d-flex justify-content-center">
                    <button class="btn btn-warning btn-edit me-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil"></i> Edit Profil
                    </button>
                    <a href="<?= base_url('home/logout') ?>" class="btn btn-primary btn-edit">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('home/updateProfile') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $user['nama'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>

                    <?php if ($user['level'] == 2 || $user['level'] == 3): ?>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $extraData['alamat'] ?? '' ?></textarea>
                        </div>
                    <?php endif; ?>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>
