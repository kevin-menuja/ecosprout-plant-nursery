<?php
require_once __DIR__ . '/../config/db.php';
require_login('customer');
$pageTitle = 'My Queries';

$stmt = $pdo->prepare('SELECT * FROM queries WHERE customer_id = ? ORDER BY created_at DESC');
$stmt->execute([current_user()['id']]);
$queries = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h1 class="section-title mb-0">My Plant Care Queries</h1>
            <a class="btn btn-success" href="<?= e(app_url('contact.php')) ?>">Submit Query</a>
        </div>
        <?php foreach ($queries as $query): ?>
            <div class="auth-panel mb-3">
                <div class="d-flex justify-content-between gap-3">
                    <h5 class="fw-bold"><?= e($query['subject']) ?></h5>
                    <span class="badge badge-category align-self-start"><?= e(ucfirst($query['status'])) ?></span>
                </div>
                <p><?= e($query['message']) ?></p>
                <?php if ($query['response']): ?>
                    <div class="alert alert-success mb-0"><strong>Staff response:</strong> <?= e($query['response']) ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
