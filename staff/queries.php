<?php
require_once __DIR__ . '/../config/db.php';
require_login('staff');
$pageTitle = 'Customer Queries';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $response = sanitize_text($_POST['response'] ?? '');

    if ($id > 0 && $response) {
        $stmt = $pdo->prepare('UPDATE queries SET response = ?, status = "responded" WHERE id = ?');
        $stmt->execute([$response, $id]);
        flash('success', 'Response saved.');
        header('Location: queries.php');
        exit;
    }
}

$queries = $pdo->query('SELECT * FROM queries ORDER BY created_at DESC')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Customer Queries</h1>
        <?php foreach ($queries as $query): ?>
            <div class="auth-panel mb-3">
                <h5 class="fw-bold"><?= e($query['subject']) ?> <span class="badge badge-category"><?= e($query['status']) ?></span></h5>
                <p class="mb-1"><strong><?= e($query['name']) ?></strong> | <?= e($query['email']) ?></p>
                <p><?= e($query['message']) ?></p>
                <form method="post">
                    <input type="hidden" name="id" value="<?= (int) $query['id'] ?>">
                    <label class="form-label">Response</label>
                    <textarea class="form-control mb-2" name="response" rows="3" required><?= e($query['response'] ?? '') ?></textarea>
                    <button class="btn btn-success" type="submit">Send Response</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
