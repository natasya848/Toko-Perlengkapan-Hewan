<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - <?= $settings['site_name'] ?? 'E-Tiket Pesawat' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        body {
            background: linear-gradient(to right, #e4e8cf, #e8cfe4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #auth {
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .auth-logo img {
            max-width: 120px;
            display: block;
            margin: 0 auto 20px;
        }

        .form-control {
            border-radius: 30px;
        }

        .btn-primary {
            border-radius: 30px;
            background: #c178b1;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #a55b9a;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .text-gray-600 a {
            color: #b360a6;
            font-weight: bold;
            text-decoration: underline;
        }

    </style>
    <script>
        function nextStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
        }
    </script>
</head>

<body>
    <div id="auth">
        <h5 class="auth-logo">
            <img src="<?= base_url('assets/images/meowgic.png') ?>" alt="PetShop Logo">
        </h5>

        <form action="<?= base_url('home/aksi_register') ?>" method="POST">
            <?= csrf_field(); ?>
            
            <div id="step-1" class="step active">
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Username" required>
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-block btn-lg mt-4" onclick="nextStep(2)">Next</button>
            </div>
            
            <div id="step-2" class="step">
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="text" name="nama" class="form-control form-control-lg" placeholder="Nama" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-3">
                    <input type="text" name="no_hp" class="form-control form-control-lg" placeholder="Nomor HP" required>
                    <div class="form-control-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">Sign Up</button>
            </div>
        </form>

        <div class="text-center mt-4 text-lg fs-6">
            <p class='text-gray-600'>Already have an account? <a href="<?= base_url('home/login') ?>">Log in</a>.</p>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        var passwordInput = document.querySelector('input[name="password"]');
        var nextButton = document.querySelector('button[onclick="nextStep(2)"]');
        var formGroup = passwordInput.closest(".form-group"); // Cari parent form-group
        
        var passwordWarning = document.createElement("div");
        passwordWarning.style.color = "#856404";
        passwordWarning.style.background = "#fff3cd";
        passwordWarning.style.border = "1px solid #ffeeba";
        passwordWarning.style.padding = "8px";
        passwordWarning.style.borderRadius = "5px";
        passwordWarning.style.display = "none";
        passwordWarning.style.marginTop = "5px";
        passwordWarning.style.fontSize = "14px";
        passwordWarning.innerHTML = "<i class='bi bi-exclamation-triangle-fill' style='color: #856404;'></i> Password harus mengandung minimal satu angka.";

        formGroup.parentNode.insertBefore(passwordWarning, formGroup.nextSibling);

        passwordInput.addEventListener("input", function () {
            var passwordPattern = /\d/; 
            if (!passwordPattern.test(passwordInput.value)) {
                passwordWarning.style.display = "block";
                nextButton.disabled = true;
            } else {
                passwordWarning.style.display = "none";
                nextButton.disabled = false;
            }
        });

        nextButton.addEventListener("click", function (event) {
            if (!/\d/.test(passwordInput.value)) {
                passwordWarning.style.display = "block";
                nextButton.disabled = true;
                event.preventDefault();
            } else {
                nextStep(2);
            }
        });
    });

    function nextStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
    }
    </script>
</body>
</html>
