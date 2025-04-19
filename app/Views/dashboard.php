<?= $this->extend('layout1'); ?>
<?php $this->setVar('page_class', 'dashboard-page'); ?>
<?= $this->section('content'); ?>
<style>
	.hero-full {
	  position: relative;
	  height: 100vh;
	  width: 100%;
	  overflow: hidden;
	  z-index: 1;
	}

	.hero-img-full {
	  position: absolute;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  object-fit: cover;
	  filter: brightness(0.6) blur(2px);
	  z-index: 1;
	}

	.hero-content {
	  position: relative;
	  z-index: 2;
	  height: 100%;
	  padding-top: 0; 
	  padding-bottom: 80px;
	  color: white;
	}

	html, body {
	  margin: 0;
	  padding: 0;
	  height: 100%;
	}

	.text-shadow {
	  text-shadow: 0 2px 8px rgba(0,0,0,0.6);
	}

	.section-divider {
	  width: 100%;
	  text-align: center;
	  position: relative;
	  margin: 5rem 0 2rem;
	}

	.section-divider::before {
	  content: "";
	  position: absolute;
	  left: 50%;
	  top: 50%;
	  height: 3px;
	  width: 0;
	  background-color: #bc84c6;
	  transform: translateX(-50%);
	  animation: expandLine 1s ease-in-out forwards;
	}

	@keyframes expandLine {
	  to {
	    width: 200px;
	  }
	}
</style>

<body class="dashboard-page">
	<div class="hero-full position-relative">
  		<img src="<?= base_url('assets/images/hewan.jpeg') ?>" alt="Hero Hewan" class="hero-img-full">
		<div class="hero-content d-flex flex-column justify-content-center align-items-center text-center px-3">
			<h1 class="display-4 fw-bold text-white text-shadow">Kami Solusi Anda</h1>
   			<p class="lead text-light text-shadow">Dalam Perawatan & Kebutuhan Hewan Kesayangan Anda</p>
		</div>
	</div>

	<div class="container mt-4">
		 <div class="section-divider"></div>
			<h4 class="mb-3 fw-bold">🛒 Produk Pilihan</h4>
			<div id="produkCarousel" class="carousel slide" data-bs-ride="carousel">
			  <div class="carousel-inner">
			    <?php if (!empty($produk)) : ?>
			      <?php
			      $chunks = array_chunk($produk, 4); 
			      foreach ($chunks as $i => $group) : ?>
			        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
			          <div class="row justify-content-center">
			            <?php foreach ($group as $p) : ?>
			              <div class="col-md-3 col-sm-6 mb-4">
			                <div class="card h-90 shadow-sm">
			                  <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>" 
			                       class="card-img-top" 
			                       alt="<?= $p['nama_produk'] ?>" 
			                       style="height: 250px; object-fit: cover;">
			                  <div class="card-body">
			                    <h6 class="card-title"><?= $p['nama_produk'] ?></h6>
			                    <p class="fw-semibold text-success">Rp<?= number_format($p['harga'], 0, ',', '.') ?></p>
			                  </div>
			                </div>
			              </div>
			            <?php endforeach; ?>
			          </div>
			        </div>
			      <?php endforeach; ?>
			    <?php else : ?>
			      <div class="text-muted">Belum ada produk yang tersedia.</div>
			    <?php endif; ?>
			  </div>

			  <button class="carousel-control-prev" type="button" data-bs-target="#produkCarousel" data-bs-slide="prev">
			    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
			    <span class="visually-hidden">Sebelumnya</span>
			  </button>
			  <button class="carousel-control-next" type="button" data-bs-target="#produkCarousel" data-bs-slide="next">
			    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
			    <span class="visually-hidden">Berikutnya</span>
			  </button>
			</div>

		<div class="section-divider"></div>
		 	<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
			  <h4 class="fw-bold mb-0 section-title">💈 Layanan Grooming</h4>
			  <a href="<?= base_url('home/tampilgrooming') ?>" class="text-decoration-none text-pastel small">Lihat lebih banyak <i class="fa fa-chevron-right"></i></a>
			</div>

			<div class="row">
			  <?php if (!empty($layanan) && is_array($layanan)) : ?>
			    <?php foreach (array_slice($layanan, 0, 3) as $l) : ?>
			      <div class="col-md-4 mb-4">
			        <div class="card bg-pastel-card shadow-sm h-100">
			          <div class="card-body">
			            <h6 class="card-title"><?= $l->nama_layanan ?></h6>
			            <p class="section-subtitle">Durasi: <?= $l->durasi ?> menit</p>
			            <p class="text-pastel"><?= $l->deskripsi ?></p>
			          </div>
			        </div>
			      </div>
			    <?php endforeach; ?>
			  <?php else : ?>
			    <p class="text-muted">Belum ada layanan grooming aktif.</p>
			  <?php endif; ?>
			</div>
		</div>
		
			<script>
			  window.addEventListener('scroll', function () {
			    const navbar = document.querySelector('.navbar-custom');
			    if (window.scrollY > 50) {
			      navbar.classList.add('navbar-scrolled');
			    } else {
			      navbar.classList.remove('navbar-scrolled');
			    }
			  });
			</script>
		</body>

<?= $this->endSection() ?>