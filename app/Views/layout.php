<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $setting['nama'] ?? 'PetShop' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/' . ($setting['foto'] ?? 'default-logo.png')) ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/mazer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div id="app">
        <?= $this->include('menu'); ?>

        <div id="main">
            <?= $this->include('header'); ?>

            <div style="text-align: right; padding: 10px;">
                <div id="google_translate_element"></div>
            </div>

            <div class="main-content">
                <section class="section">
                    <?= $this->renderSection('content'); ?>
                </section>
            </div>

            <?= $this->include('footer'); ?>
        </div>
    </div>
</body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <?= $this->renderSection('scripts'); ?>

    <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'id'}, 'google_translate_element');
        checkGoogleTranslateReady();
    }
    
    function checkGoogleTranslateReady() {
        let interval = setInterval(function () {
            var googleTranslateFrame = document.querySelector(".goog-te-menu-frame");
            if (googleTranslateFrame !== null) {
                clearInterval(interval);
                console.log("✅ Google Translate siap digunakan!");
            }
        }, 500);
    }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let dropdownItems = document.querySelectorAll(".has-sub > a");
        let mainMenuLinks = document.querySelectorAll(".sidebar-menu .sidebar-item > a:not(.has-sub > a)");

        function saveDropdownState() {
            let openMenus = [];
            document.querySelectorAll(".has-sub.active").forEach(function (item) {
                openMenus.push(item.dataset.id);
            });
            localStorage.setItem("openDropdowns", JSON.stringify(openMenus));
        }

        function loadDropdownState() {
            let openMenus = JSON.parse(localStorage.getItem("openDropdowns")) || [];
            openMenus.forEach(function (id) {
                let menu = document.querySelector('.has-sub[data-id="' + id + '"]');
                if (menu) {
                    menu.classList.add("active");
                    menu.querySelector(".submenu").style.display = "block";
                }
            });
        }

        dropdownItems.forEach(function (item) {
            item.parentElement.setAttribute("data-id", item.innerText.trim());

            item.addEventListener("click", function (e) {
                e.preventDefault();
                let parent = item.parentElement;
                let submenu = parent.querySelector(".submenu");

                if (parent.classList.contains("active")) {
                    parent.classList.remove("active");
                    submenu.style.display = "none";
                } else {
                    parent.classList.add("active");
                    submenu.style.display = "block";
                }

                saveDropdownState();
            });
        });

        mainMenuLinks.forEach(function (link) {
            link.addEventListener("click", function () {
                localStorage.removeItem("openDropdowns");
            });
        });

        loadDropdownState();
    });
    </script>
</html>
