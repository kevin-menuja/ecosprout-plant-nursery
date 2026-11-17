<?php
require_once __DIR__ . '/../config/db.php';
require_login('admin');
$pageTitle = 'Admin Dashboard';
$users = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$orders = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$revenue = (float) $pdo->query('SELECT COALESCE(SUM(total_amount), 0) FROM orders')->fetchColumn();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title">Administrator Dashboard</h1>
        <div class="row g-4 mt-1">
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-users mb-3"></i><h5 class="fw-bold">Users</h5><p class="mb-0"><?= $users ?> accounts</p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-receipt mb-3"></i><h5 class="fw-bold">Orders</h5><p class="mb-0"><?= $orders ?> total</p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-chart-line mb-3"></i><h5 class="fw-bold">Revenue</h5><p class="mb-0">LKR <?= number_format($revenue, 2) ?></p></div></div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-success" href="<?= e(app_url('admin/manage_users.php')) ?>">Manage Users</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('admin/reports.php')) ?>">Sales Reports</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
