<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #e4e8cf, #e8cfe4);
            min-height: 90vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        .navbar-custom {
          padding-top: 0.4rem;
          padding-bottom: 0.4rem;
          transition: background-color 0.3s ease, box-shadow 0.3s ease;
          z-index: 10;
        }

        .dashboard-page .navbar-custom {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          background-color: transparent;
        }

        .dashboard-page .navbar-custom.navbar-scrolled {
          background-color: #bba8c8;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        body:not(.dashboard-page) .navbar-custom {
          background-color: transparent;
          position: relative;
        }

        .hero-img-full {
          z-index: 1;
        }

        .hero-content {
          z-index: 2;
          padding-top: 80px;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
            color: #4c305e; 
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            padding: 10px 15px;
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease-in-out;
            border-radius: 20px;
        }

        .navbar-nav .nav-link:hover {
            color: #fff8e1;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            width: 0;
            height: 3px;
            background: #ffffff;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover::after {
            width: 60%;
        }

        .navbar-nav .nav-link.active {
            background-color: #bc8476; 
            color: #ffffff !important;
            border-radius: 30px;
            padding: 10px 20px;
        }

        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link:focus-visible {
            outline: none;
            box-shadow: none;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
        }

        .nav-icon {
            margin-right: 5px;
        }

        .carousel-item img {
            object-fit: cover;
            height: 300px;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
        }

        .bg-pastel-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            color: #5c5470;
        }

        .btn-outline-success {
            border-color: #a4c3b2;
            color: #6a9476;
        }
        .btn-outline-success:hover {
            background-color: #a4c3b2;
            color: white;
        }

        .text-pastel {
            color: #a27ea8;
        }
        .card-title {
            font-weight: 600;
        }

        .keranjang-popup {
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background: rgba(0, 0, 0, 0.5);
          display: none;
          justify-content: center;
          align-items: center;
          z-index: 1050;
          padding: 20px;
        }

        .keranjang-popup.d-flex {
          display: flex !important;
        }

        .keranjang-box {
          background-color: #fffaf7;
          padding: 20px;
          border-radius: 12px;
          max-width: 400px;
          width: 100%;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-pastel {
          background-color: #bc8476;
          color: white;
        }

        .btn-pastel:hover {
          background-color: #a96a63;
        }

        .pastel-scroll {
          max-height: 200px;
          overflow-y: auto;
        }
  </style>

</head>
<body>
  <?php if (session()->has('id_user')) :
    $keranjangModel = new \App\Models\M_keranjang();
    $keranjang = $keranjangModel->getKeranjangByUser(session('id_user'));
  ?>
  <?php endif; ?>

 <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('home/meowgic') ?>">
            <img src="<?= base_url('assets/images/meowgic.png') ?>" alt="Meowgic" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('home/tampilproduk') ?>">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('home/tampilgrooming') ?>">Layanan Grooming</a>
                </li>
                <li class="nav-item me-3">
                    <button class="btn btn-light position-relative" id="btnKeranjang">
                        <i class="fa fa-cart-plus"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="keranjang-count">
                            <?= isset($keranjang) ? count($keranjang['produk']) + count($keranjang['layanan']) : 0 ?>
                        </span>
                    </button>
                </li>

                <?php if (session()->get('level') == 2): ?>
                <li class="nav-item me-3">
                    <a class="nav-link" href="<?= base_url('home/riwayat') ?>" title="Riwayat">
                        <i class="fa fa-clock-rotate-left fa-lg"></i>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (session()->get('level') == 2): ?>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="<?= base_url('home/riwayat_pengiriman') ?>" title="Riwayat Pengiriman">
                            <i class="fa fa-truck fa-lg"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('home/profile') ?>" title="Profil">
                        <i class="fa fa-user-circle fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


  <div id="popupKeranjang" class="keranjang-popup d-none justify-content-center align-items-center">
    <div class="keranjang-box">
      <div class="keranjang-header d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">🛒 Keranjang Anda</h5>
        <button class="btn btn-sm btn-outline-secondary" id="btnTutupKeranjang">×</button>
      </div>
      <hr>
      <div class="mb-3">
        <h6 class="text-muted">Produk</h6>
        <ul id="listProduk" class="list-group pastel-scroll small"></ul>
      </div>
      <hr class="my-2">
      <div>
        <h6 class="text-muted">Layanan Grooming</h6>
        <ul id="listLayanan" class="list-group pastel-scroll small"></ul>
      </div>
      <hr>
      <div class="text-end">
        <a href="<?= base_url('home/tampilkeranjang') ?>" class="btn btn-pastel">Lihat Semua</a>
      </div>
    </div>
  </div>

  <!-- Script Popup -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const btnKeranjang = document.getElementById('btnKeranjang');
      const popupKeranjang = document.getElementById('popupKeranjang');
      const btnTutupKeranjang = document.getElementById('btnTutupKeranjang');
      const listProduk = document.getElementById('listProduk');
      const listLayanan = document.getElementById('listLayanan');

      const keranjangData = <?= json_encode($keranjang ?? ['produk' => [], 'layanan' => []]) ?>;

      btnKeranjang?.addEventListener('click', function () {
        popupKeranjang.classList.toggle('d-none');
        popupKeranjang.classList.toggle('d-flex');
      });

      btnTutupKeranjang?.addEventListener('click', function () {
        popupKeranjang.classList.remove('d-flex');
        popupKeranjang.classList.add('d-none');
      });

      // Kosongkan dulu list-nya
      listProduk.innerHTML = '';
      listLayanan.innerHTML = '';

      keranjangData.produk?.forEach(p => {
        const item = document.createElement('li');
        item.className = "list-group-item d-flex justify-content-between";
        item.innerHTML = `<span>${p.nama}</span><span>x${p.jumlah}</span>`;
        listProduk.appendChild(item);
      });

      keranjangData.layanan?.forEach(l => {
        const item = document.createElement('li');
        item.className = "list-group-item d-flex justify-content-between";
        item.innerHTML = `<span>${l.nama}</span><span>Rp${parseInt(l.harga).toLocaleString()}</span>`;
        listLayanan.appendChild(item);
      });

      document.getElementById('keranjang-count').innerText = (keranjangData.produk?.length || 0) + (keranjangData.layanan?.length || 0);
    });
  </script>

  <!-- Script Navbar Active & Scroll -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
      navLinks.forEach(link => {
        link.addEventListener("click", function () {
          navLinks.forEach(nav => nav.classList.remove("active"));
          this.classList.add("active");
        });
      });

      window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar-custom');
        if (window.scrollY > 50) {
          navbar.classList.add('navbar-scrolled');
        } else {
          navbar.classList.remove('navbar-scrolled');
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

   
