<style>
    .welcome-bubble {
        display: inline-block;
        background-color: #fdddfd;
        color: #d14aa3;
        font-family: 'Nunito', cursive;
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 30px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        position: relative;
        animation: popIn 0.6s ease;
    }

    .welcome-bubble::before {
        content: "😺 ";
    }

    @keyframes popIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<header class="mb-3">
    <div class="navbar-nav ms-auto">
        <div class="nav-item">
            <?php if (!empty($showWelcome) && $showWelcome): ?>
                <span class="welcome-bubble">
                    Welcome, <?= session()->get('email') ?>!
                </span>
            <?php endif; ?>
        </div>
    </div>
</header>
