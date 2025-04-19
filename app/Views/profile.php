<?= $this->extend('layout1'); ?>
<?= $this->section('content'); ?>

<style>
.profile-container {
    background-color: #fffaf7;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 0 auto;
}

.profile-img {
    border-radius: 50%;
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid #f5d1b7;
}

/* Judul Profil */
h3 {
    font-size: 24px;
    color: #734c3d;
    font-weight: bold;
}

/* Username */
p.text-muted {
    font-size: 18px;
    color: #9c6b5b;
}

/* Tabel Profil */
.table {
    background-color: #fff9f5;
    border: 1px solid #f5dad3;
    border-radius: 10px;
    margin-top: 20px;
    padding: 16px;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
}

/* Tombol Edit dan Logout */
.btn-edit {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
}

.btn-edit:hover {
    opacity: 0.9;
}

.btn-warning {
    background-color: #f7b7a3;
}

.btn-primary {
    background-color: #87b7d5;
}

.btn-warning:hover {
    background-color: #e48f7f;
}

.btn-primary:hover {
    background-color: #6e9eb7;
}

/* Modal Edit Profil */
.modal-content {
    background-color: #fffaf7;
    border-radius: 12px;
    padding: 20px;
}

.modal-header {
    border-bottom: 1px solid #f5d1b7;
}

.modal-title {
    font-size: 1.5rem;
    color: #734c3d;
}

.modal-backdrop {
    display: none !important;
}

.form-label {
    font-weight: 600;
    color: #734c3d;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #f5d1b7;
}

.form-control:focus {
    border-color: #f5d1b7;
    box-shadow: 0 0 5px rgba(250, 187, 150, 0.5);
}

/* Responsif */
@media (max-width: 576px) {
    .profile-container {
        padding: 20px;
    }

    .profile-img {
        width: 100px;
        height: 100px;
    }

    .table th, .table td {
        padding: 8px;
    }

    h3 {
        font-size: 20px;
    }
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
