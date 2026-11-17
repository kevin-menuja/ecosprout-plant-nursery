<?php
require_once __DIR__ . '/../config/db.php';
require_login('customer');
$pageTitle = 'Order History';
$stmt = $pdo->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC');
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Order History</h1>
        <div class="table-responsive">
            <table class="table bg-white">
                <thead><tr><th>#</th><th>Date</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr><td><?= (int) $order['id'] ?></td><td><?= e($order['created_at']) ?></td><td><?= e(ucfirst($order['status'])) ?></td><td>LKR <?= number_format((float) $order['total_amount'], 2) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
