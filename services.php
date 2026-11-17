<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Services';
$services = $pdo->query('SELECT * FROM services ORDER BY title')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Landscaping Services</h1>
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <img src="<?= e(image_url($service['image_path'])) ?>" class="card-img-top" alt="<?= e($service['title']) ?>">
                        <div class="p-3">
                            <h5 class="fw-bold"><?= e($service['title']) ?></h5>
                            <p><?= e($service['description']) ?></p>
                            <p class="fw-bold text-success"><?= e($service['price_range']) ?></p>
                            <a href="<?= e(app_url('contact.php')) ?>" class="btn btn-success">Book Service</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
