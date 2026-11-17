<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Home';

$featuredStmt = $pdo->query('SELECT * FROM plants ORDER BY created_at DESC LIMIT 4');
$featuredPlants = $featuredStmt->fetchAll();
$services = $pdo->query('SELECT * FROM services LIMIT 3')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container">
        <h1>EcoSprout Plant Nursery</h1>
        <p class="mt-3">Healthy plants, thoughtful landscaping, and seasonal gardening workshops from Kegalle to your doorstep.</p>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-success btn-lg" href="<?= e(app_url('catalogue.php')) ?>"><i class="fa-solid fa-leaf me-2"></i>Shop Plants</a>
            <a class="btn btn-light btn-lg" href="<?= e(app_url('workshops.php')) ?>">Join a Workshop</a>
        </div>
    </div>
</section>

<section class="section-band">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-title">Featured Plants</h2>
                <p class="mb-0">Popular nursery picks with online images and detailed care guidance.</p>
            </div>
            <a href="<?= e(app_url('catalogue.php')) ?>" class="btn btn-outline-success">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredPlants as $plant): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="plant-card">
                        <img src="<?= e(image_url($plant['image_path'])) ?>" class="card-img-top" alt="<?= e($plant['name']) ?>">
                        <div class="p-3">
                            <span class="badge badge-category mb-2"><?= e(ucfirst($plant['category'])) ?></span>
                            <h5 class="fw-bold"><?= e($plant['name']) ?></h5>
                            <p class="text-success fw-bold mb-3">LKR <?= number_format((float) $plant['price'], 2) ?></p>
                            <a class="btn btn-success w-100" href="<?= e(app_url('catalogue.php')) ?>">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-band bg-white">
    <div class="container">
        <h2 class="section-title mb-4">Gardening Services</h2>
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <img src="<?= e(image_url($service['image_path'])) ?>" class="card-img-top" alt="<?= e($service['title']) ?>">
                        <div class="p-3">
                            <h5 class="fw-bold"><?= e($service['title']) ?></h5>
                            <p><?= e($service['description']) ?></p>
                            <p class="fw-bold text-success mb-0"><?= e($service['price_range']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-band">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="dashboard-tile">
                    <i class="fa-solid fa-truck-fast mb-3"></i>
                    <h5 class="fw-bold">Islandwide Delivery</h5>
                    <p class="mb-0">Packed carefully for safe travel and easy planting.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-tile">
                    <i class="fa-solid fa-hand-holding-heart mb-3"></i>
                    <h5 class="fw-bold">Care Support</h5>
                    <p class="mb-0">Submit questions and get responses from nursery staff.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-tile">
                    <i class="fa-solid fa-chalkboard-user mb-3"></i>
                    <h5 class="fw-bold">Seasonal Workshops</h5>
                    <p class="mb-0">Learn composting, balcony gardening, and plant care skills.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
