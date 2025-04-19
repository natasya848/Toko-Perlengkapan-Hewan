<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetShop</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/meowgic.png') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="<?= isset($page_class) ? $page_class : '' ?>">
  <div id="app">
    <?= $this->include('navbar'); ?> <!-- Hanya satu pemanggilan navbar -->
    
    <div class="main-content">
        <section class="section">
            <?= $this->renderSection('content'); ?>
        </section>
    </div>

    <?= $this->include('footer'); ?>
  </div>
</body>
</html>
