<?php $user = current_user(); ?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="<?= e(app_url('index.php')) ?>">
            <i class="fa-solid fa-seedling me-2"></i>EcoSprout
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('index.php')) ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('about.php')) ?>">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('catalogue.php')) ?>">Plants</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('services.php')) ?>">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('workshops.php')) ?>">Workshops</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('blog.php')) ?>">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('contact.php')) ?>">Contact</a></li>
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(app_url($user['role'] . '/dashboard.php')) ?>"><i class="fa-solid fa-user me-1"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link position-relative" href="<?= e(app_url('customer/cart.php')) ?>"><i class="fa-solid fa-cart-shopping"></i><span class="cart-pill"><?= cart_count() ?></span></a></li>
                    <li class="nav-item"><a class="btn btn-outline-success btn-sm ms-lg-2" href="<?= e(app_url('auth/logout.php')) ?>">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(app_url('auth/login.php')) ?>"><i class="fa-solid fa-right-to-bracket me-1"></i>Login</a></li>
                    <li class="nav-item"><a class="btn btn-success btn-sm ms-lg-2" href="<?= e(app_url('auth/register.php')) ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
