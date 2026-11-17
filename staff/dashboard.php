<?php
require_once __DIR__ . '/../config/db.php';
require_login('staff');
$pageTitle = 'Staff Dashboard';
$plantCount = (int) $pdo->query('SELECT COUNT(*) FROM plants')->fetchColumn();
$workshopCount = (int) $pdo->query('SELECT COUNT(*) FROM workshops')->fetchColumn();
$openQueries = (int) $pdo->query('SELECT COUNT(*) FROM queries WHERE status = "open"')->fetchColumn();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title">Nursery Staff Dashboard</h1>
        <div class="row g-4 mt-1">
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-leaf mb-3"></i><h5 class="fw-bold">Plants</h5><p class="mb-0"><?= $plantCount ?> varieties</p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-calendar-days mb-3"></i><h5 class="fw-bold">Workshops</h5><p class="mb-0"><?= $workshopCount ?> scheduled</p></div></div>
            <div class="col-md-4"><div class="dashboard-tile"><i class="fa-solid fa-inbox mb-3"></i><h5 class="fw-bold">Open Queries</h5><p class="mb-0"><?= $openQueries ?> pending</p></div></div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-success" href="<?= e(app_url('staff/manage_plants.php')) ?>">Manage Plants</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('staff/manage_workshops.php')) ?>">Manage Workshops</a>
            <a class="btn btn-outline-success" href="<?= e(app_url('staff/queries.php')) ?>">Customer Queries</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
