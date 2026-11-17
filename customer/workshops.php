<?php
require_once __DIR__ . '/../config/db.php';
require_login('customer');
$pageTitle = 'Workshop Registration';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workshopId = (int) ($_POST['workshop_id'] ?? 0);
    $check = $pdo->prepare('SELECT id FROM workshop_registrations WHERE workshop_id = ? AND customer_id = ?');
    $check->execute([$workshopId, current_user()['id']]);

    if ($check->fetch()) {
        flash('error', 'You are already registered for this workshop.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO workshop_registrations (workshop_id, customer_id) VALUES (?, ?)');
        $stmt->execute([$workshopId, current_user()['id']]);
        flash('success', 'Workshop registration successful.');
    }

    header('Location: workshops.php');
    exit;
}

$workshops = $pdo->query('SELECT * FROM workshops ORDER BY date, time')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Register for Workshops</h1>
        <div class="row g-4">
            <?php foreach ($workshops as $workshop): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="workshop-card p-3">
                        <h5 class="fw-bold"><?= e($workshop['title']) ?></h5>
                        <p><?= e($workshop['description']) ?></p>
                        <p><?= e($workshop['date']) ?>, <?= e(substr($workshop['time'], 0, 5)) ?> | <?= e($workshop['location']) ?></p>
                        <form method="post">
                            <input type="hidden" name="workshop_id" value="<?= (int) $workshop['id'] ?>">
                            <button class="btn btn-success" type="submit">Register</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
