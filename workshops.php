<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Workshops';
$workshops = $pdo->query('SELECT * FROM workshops ORDER BY date, time')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Workshops & Events</h1>
        <div class="row g-4">
            <?php foreach ($workshops as $workshop): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="workshop-card p-3">
                        <h5 class="fw-bold"><?= e($workshop['title']) ?></h5>
                        <p><?= e($workshop['description']) ?></p>
                        <p class="mb-1"><i class="fa-solid fa-calendar-days me-2 text-success"></i><?= e($workshop['date']) ?> at <?= e(substr($workshop['time'], 0, 5)) ?></p>
                        <p><i class="fa-solid fa-location-dot me-2 text-success"></i><?= e($workshop['location']) ?></p>
                        <a href="<?= e(app_url('customer/workshops.php')) ?>" class="btn btn-success">Register</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
