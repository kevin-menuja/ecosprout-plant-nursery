<?php
require_once __DIR__ . '/../config/db.php';
require_login('admin');
$pageTitle = 'Reports';
$summary = $pdo->query('SELECT COUNT(*) AS orders, COALESCE(SUM(total_amount), 0) AS revenue FROM orders')->fetch();
$popular = $pdo->query('SELECT p.name, SUM(oi.quantity) AS sold FROM order_items oi JOIN plants p ON p.id = oi.plant_id GROUP BY p.id, p.name ORDER BY sold DESC LIMIT 5')->fetchAll();
$orders = $pdo->query('SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON u.id = o.customer_id ORDER BY o.created_at DESC')->fetchAll();
$queries = $pdo->query('SELECT * FROM queries ORDER BY created_at DESC')->fetchAll();
$bookings = $pdo->query('SELECT wr.*, w.title, u.name AS customer_name FROM workshop_registrations wr JOIN workshops w ON w.id = wr.workshop_id JOIN users u ON u.id = wr.customer_id ORDER BY wr.registered_at DESC')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Sales Reports & Oversight</h1>
        <div class="row g-4 mb-4">
            <div class="col-md-6"><div class="dashboard-tile"><h5 class="fw-bold">Total Orders</h5><p class="display-6 mb-0"><?= (int) $summary['orders'] ?></p></div></div>
            <div class="col-md-6"><div class="dashboard-tile"><h5 class="fw-bold">Total Revenue</h5><p class="display-6 mb-0">LKR <?= number_format((float) $summary['revenue'], 2) ?></p></div></div>
        </div>
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="auth-panel">
                    <h5 class="fw-bold">Popular Plants</h5>
                    <?php foreach ($popular as $item): ?>
                        <div class="d-flex justify-content-between border-bottom py-2"><span><?= e($item['name']) ?></span><strong><?= (int) $item['sold'] ?> sold</strong></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="auth-panel">
                    <h5 class="fw-bold">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table mb-0"><thead><tr><th>Customer</th><th>Status</th><th>Total</th></tr></thead><tbody>
                        <?php foreach ($orders as $order): ?><tr><td><?= e($order['customer_name']) ?></td><td><?= e($order['status']) ?></td><td>LKR <?= number_format((float) $order['total_amount'], 2) ?></td></tr><?php endforeach; ?>
                        </tbody></table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="auth-panel">
                    <h5 class="fw-bold">Workshop Bookings</h5>
                    <?php foreach ($bookings as $booking): ?>
                        <p class="mb-2"><?= e($booking['customer_name']) ?> registered for <strong><?= e($booking['title']) ?></strong></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="auth-panel">
                    <h5 class="fw-bold">Submitted Queries</h5>
                    <?php foreach ($queries as $query): ?>
                        <p class="mb-2"><strong><?= e($query['subject']) ?>:</strong> <?= e($query['status']) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
