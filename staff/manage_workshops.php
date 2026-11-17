<?php
require_once __DIR__ . '/../config/db.php';
require_login('staff');
$pageTitle = 'Manage Workshops';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id > 0) {
        $stmt = $pdo->prepare('DELETE FROM workshops WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Workshop deleted.');
        header('Location: manage_workshops.php');
        exit;
    }

    $data = [
        sanitize_text($_POST['title'] ?? ''),
        sanitize_text($_POST['description'] ?? ''),
        $_POST['date'] ?? '',
        $_POST['time'] ?? '',
        sanitize_text($_POST['location'] ?? ''),
        (int) ($_POST['max_participants'] ?? 0),
    ];

    if ($action === 'save' && $data[0] && $data[2] && $data[3]) {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE workshops SET title=?, description=?, date=?, time=?, location=?, max_participants=? WHERE id=?');
            $stmt->execute([...$data, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO workshops (title, description, date, time, location, max_participants, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([...$data, current_user()['id']]);
        }
        flash('success', 'Workshop saved.');
        header('Location: manage_workshops.php');
        exit;
    }
}

$editWorkshop = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM workshops WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editWorkshop = $stmt->fetch();
}
$workshops = $pdo->query('SELECT * FROM workshops ORDER BY date, time')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Manage Workshops</h1>
        <form method="post" class="auth-panel mb-4">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) ($editWorkshop['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($editWorkshop['title'] ?? '') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Date</label><input class="form-control" type="date" name="date" value="<?= e($editWorkshop['date'] ?? '') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Time</label><input class="form-control" type="time" name="time" value="<?= e($editWorkshop['time'] ?? '') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Location</label><input class="form-control" name="location" value="<?= e($editWorkshop['location'] ?? '') ?>"></div>
                <div class="col-md-3"><label class="form-label">Max Participants</label><input class="form-control" type="number" name="max_participants" value="<?= e((string) ($editWorkshop['max_participants'] ?? 20)) ?>"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= e($editWorkshop['description'] ?? '') ?></textarea></div>
                <div class="col-12"><button class="btn btn-success" type="submit">Save Workshop</button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table bg-white">
                <thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($workshops as $workshop): ?>
                    <tr>
                        <td><?= e($workshop['title']) ?></td>
                        <td><?= e($workshop['date']) ?> <?= e(substr($workshop['time'], 0, 5)) ?></td>
                        <td><?= e($workshop['location']) ?></td>
                        <td><a class="btn btn-sm btn-outline-success" href="?edit=<?= (int) $workshop['id'] ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
