<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
        body {
            background: linear-gradient(to right, #e4e8cf, #e8cfe4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #auth {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .auth-logo img {
            max-width: 120px;
            max-height: 100px;
            display: block;
            margin: 0 auto 20px;
            object-fit: contain; 
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

        .text-gray-600 a {
            color: #b360a6;
            font-weight: bold;
            text-decoration: underline;
        }

        #math-captcha {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
}


    </style>
</head>

<body>
    <div id="auth">
        <h5 class="auth-logo">
            <img src="<?= base_url('assets/images/meowgic.png') ?>" alt="PetShop Logo">
        </h5>

        <form id="login-form" action="<?= base_url('home/aksi_login') ?>" method="post">
            <input type="hidden" name="correct_answer" id="correct-answer">
            <input type="hidden" name="math_answer" id="user-answer">

            <div class="form-group position-relative has-icon-left mb-3">
                <input type="text" name="email" class="form-control form-control-lg" placeholder="Username" required>
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-3">
                <input type="password" name="pswd" class="form-control form-control-lg" placeholder="Password" required>
                <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <div class="text-end mt-2">
                <p class="text-gray-600">
                    <a href="<?= base_url('home/resetp') ?>" class="text-decoration-none">Forgot Password?</a>
                </p>
            </div>

            <div class="g-recaptcha" data-sitekey="6LcNvAsrAAAAAOiX2u-w_9h-pza_Sd2Rnao-9jbB"></div>

            <div id="math-captcha" class="mt-3" style="display: none;">
                <label id="math-question" class="form-label fw-bold"></label>
                <input type="number" id="math-answer" class="form-control" placeholder="Jawaban Anda">
            </div>

            <button type="button" onclick="validateCaptcha()" class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Log in</button>
        </form>

        <div class="text-center mt-4 text-lg fs-6">
            <p class="text-gray-600">Don't have an account? <a href="<?= base_url('home/register') ?>">Register</a></p>
        </div>
    </div>

    <script>
        function validateCaptcha() {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert("Please complete the CAPTCHA before submitting.");
            } else {
                document.getElementById('login-form').submit();
            }
        }
    </script>
    <script>
    let correctAnswer;

    function generateMathCaptcha() {
        const a = Math.floor(Math.random() * 10);
        const b = Math.floor(Math.random() * 10);
        const ops = ['+', '-'];
        const op = ops[Math.floor(Math.random() * ops.length)];
        const question = `Berapa hasil dari ${a} ${op} ${b}?`;
        document.getElementById('math-question').innerText = question;
        correctAnswer = op === '+' ? a + b : a - b;
        document.getElementById('correct-answer').value = correctAnswer;
    }

    function validateCaptcha() {
        if (!navigator.onLine) {
            const userAnswer = parseInt(document.getElementById('math-answer').value);
            document.getElementById('user-answer').value = userAnswer;

            if (isNaN(userAnswer) || userAnswer !== correctAnswer) {
                alert("Jawaban soal matematika salah. Silakan coba lagi.");
                generateMathCaptcha();
            } else {
                document.getElementById('login-form').submit();
            }
        } else {
            const response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert("Please complete the CAPTCHA before submitting.");
            } else {
                document.getElementById('login-form').submit();
            }
        }
    }

    window.addEventListener('load', function () {
        if (!navigator.onLine) {
            document.getElementById('math-captcha').style.display = 'block';
            generateMathCaptcha();
        }
    });
</script>

</body>
</html>