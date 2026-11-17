<?php
require_once __DIR__ . '/../config/db.php';
require_login('customer');
$pageTitle = 'Customer Dashboard';
$customerId = current_user()['id'];

$ordersStmt = $pdo->prepare('SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_amount), 0) AS total_spent FROM orders WHERE customer_id = ?');
$ordersStmt->execute([$customerId]);
$orderStats = $ordersStmt->fetch();

$queriesStmt = $pdo->prepare('SELECT COUNT(*) AS open_queries FROM queries WHERE customer_id = ? AND status = "open"');
$queriesStmt->execute([$customerId]);
$openQueries = (int) $queriesStmt->fetchColumn();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title">Welcome, <?= e(current_user()['name']) ?></h1>
        <div class="row g-4 mt-1">
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-bag-shopping mb-3"></i><h5 class="fw-bold">Orders</h5><p class="mb-0"><?= (int) $orderStats['total_orders'] ?> placed</p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-coins mb-3"></i><h5 class="fw-bold">Total Spend</h5><p class="mb-0">LKR <?= number_format((float) $orderStats['total_spent'], 2) ?></p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-comments mb-3"></i><h5 class="fw-bold">Open Queries</h5><p class="mb-0"><?= $openQueries ?> waiting</p></div></div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-success" href="<?= e(app_url('customer/catalogue.php')) ?>">Browse Plants</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('customer/cart.php')) ?>">View Cart</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('customer/workshops.php')) ?>">Workshops</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('customer/orders.php')) ?>">Order History</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('customer/queries.php')) ?>">My Queries</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
