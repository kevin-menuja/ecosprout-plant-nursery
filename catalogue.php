<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Plant Catalogue';
$category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');
$sql = 'SELECT * FROM plants WHERE 1=1';
$params = [];

if (in_array($category, ['indoor', 'outdoor', 'ornamental', 'edible'], true)) {
    $sql .= ' AND category = ?';
    $params[] = $category;
}

if ($search !== '') {
    $sql .= ' AND name LIKE ?';
    $params[] = '%' . $search . '%';
}

$sql .= ' ORDER BY name';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$plants = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="section-title">Plant Catalogue</h1>
                <p class="mb-0">Browse indoor, outdoor, ornamental, and edible plants.</p>
            </div>
            <form class="d-flex gap-2" method="get">
                <input class="form-control" name="search" data-plant-search placeholder="Search plants" value="<?= e($search) ?>">
                <select class="form-select" name="category" data-category-filter>
                    <option value="">All categories</option>
                    <?php foreach (['indoor', 'outdoor', 'ornamental', 'edible'] as $item): ?>
                        <option value="<?= $item ?>" <?= $category === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="row g-4">
            <?php foreach ($plants as $plant): ?>
                <div class="col-sm-6 col-lg-4" data-plant-card data-name="<?= e($plant['name']) ?>" data-category="<?= e($plant['category']) ?>">
                    <div class="plant-card">
                        <img src="<?= e(image_url($plant['image_path'])) ?>" class="card-img-top" alt="<?= e($plant['name']) ?>">
                        <div class="p-3">
                            <span class="badge badge-category mb-2"><?= e(ucfirst($plant['category'])) ?></span>
                            <h5 class="fw-bold"><?= e($plant['name']) ?></h5>
                            <p class="small fst-italic mb-1"><?= e($plant['botanical_name']) ?></p>
                            <p><?= e($plant['description']) ?></p>
                            <p class="small"><strong>Care:</strong> <?= e($plant['care_instructions']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">LKR <?= number_format((float) $plant['price'], 2) ?></span>
                                <form method="post" action="<?= e(app_url('customer/cart.php')) ?>">
                                    <input type="hidden" name="plant_id" value="<?= (int) $plant['id'] ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button class="btn btn-success btn-sm" type="submit">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
