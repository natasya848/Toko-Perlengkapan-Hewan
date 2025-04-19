<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<style>
    body {
        background: linear-gradient(to right, #e4e8cf, #e8cfe4);  
        color: #333;
        font-family: 'Nunito', sans-serif;
    }

    .profile-container { 
        max-width: 500px; 
        margin: 60px auto; 
        background: #fff; 
        padding: 30px; 
        border-radius: 12px; 
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); 
    }
    .profile-img { 
        width: 100px; 
        height: 100px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 3px solid #007bff; 
        margin-bottom: 15px;
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

<div class="container">
    <div class="profile-container text-center">

        <form action="<?= base_url('home/update_setting') ?>" method="POST" enctype="multipart/form-data">
        	<div class="mt-2">
                <img src="<?= base_url('uploads/' . $setting['foto']) ?>" class="profile-img">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label text-dark">Nama Website:</label>
                <input type="text" class="form-control" name="nama" value="<?= esc($setting['nama']) ?>" required>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label text-dark">Logo Website:</label>
                <input type="file" class="form-control" name="foto">
            </div>

            <button type="submit" class="btn btn-save w-100">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
