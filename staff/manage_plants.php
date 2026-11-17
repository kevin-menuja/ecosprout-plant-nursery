<?php
require_once __DIR__ . '/../config/db.php';
require_login('staff');
$pageTitle = 'Manage Plants';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id > 0) {
        $stmt = $pdo->prepare('DELETE FROM plants WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Plant deleted.');
        header('Location: manage_plants.php');
        exit;
    }

    $data = [
        sanitize_text($_POST['name'] ?? ''),
        $_POST['category'] ?? 'indoor',
        sanitize_text($_POST['description'] ?? ''),
        sanitize_text($_POST['botanical_name'] ?? ''),
        sanitize_text($_POST['care_instructions'] ?? ''),
        (float) ($_POST['price'] ?? 0),
        (int) ($_POST['stock_qty'] ?? 0),
        filter_var($_POST['image_path'] ?? '', FILTER_VALIDATE_URL) ? trim($_POST['image_path']) : '',
    ];

    if ($action === 'save' && $data[0] && in_array($data[1], ['indoor', 'outdoor', 'ornamental', 'edible'], true) && $data[5] > 0) {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE plants SET name=?, category=?, description=?, botanical_name=?, care_instructions=?, price=?, stock_qty=?, image_path=? WHERE id=?');
            $stmt->execute([...$data, $id]);
            flash('success', 'Plant updated.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO plants (name, category, description, botanical_name, care_instructions, price, stock_qty, image_path, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([...$data, current_user()['id']]);
            flash('success', 'Plant added.');
        }
        header('Location: manage_plants.php');
        exit;
    }

    flash('error', 'Please provide valid plant details. Image must be an online URL when supplied.');
}

$editPlant = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM plants WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editPlant = $stmt->fetch();
}

$plants = $pdo->query('SELECT * FROM plants ORDER BY name')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Manage Plant Inventory</h1>
        <form method="post" class="auth-panel needs-validation mb-4" novalidate>
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) ($editPlant['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($editPlant['name'] ?? '') ?>" required></div>
                <div class="col-md-4"><label class="form-label">Category</label><select class="form-select" name="category"><?php foreach (['indoor','outdoor','ornamental','edible'] as $cat): ?><option value="<?= $cat ?>" <?= ($editPlant['category'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-2"><label class="form-label">Price</label><input class="form-control" type="number" step="0.01" name="price" value="<?= e((string) ($editPlant['price'] ?? '')) ?>" required></div>
                <div class="col-md-2"><label class="form-label">Stock</label><input class="form-control" type="number" name="stock_qty" value="<?= e((string) ($editPlant['stock_qty'] ?? '')) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Botanical Name</label><input class="form-control" name="botanical_name" value="<?= e($editPlant['botanical_name'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label">Online Image URL</label><input class="form-control" type="url" name="image_path" value="<?= e($editPlant['image_path'] ?? '') ?>" placeholder="https://images.unsplash.com/..."></div>
                <div class="col-md-6"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= e($editPlant['description'] ?? '') ?></textarea></div>
                <div class="col-md-6"><label class="form-label">Care Instructions</label><textarea class="form-control" name="care_instructions" rows="3"><?= e($editPlant['care_instructions'] ?? '') ?></textarea></div>
                <div class="col-12"><button class="btn btn-success" type="submit"><?= $editPlant ? 'Update Plant' : 'Add Plant' ?></button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle bg-white">
                <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($plants as $plant): ?>
                    <tr>
                        <td><img src="<?= e(image_url($plant['image_path'])) ?>" alt=""></td>
                        <td><?= e($plant['name']) ?></td>
                        <td><?= e(ucfirst($plant['category'])) ?></td>
                        <td>LKR <?= number_format((float) $plant['price'], 2) ?></td>
                        <td><?= (int) $plant['stock_qty'] ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-success" href="?edit=<?= (int) $plant['id'] ?>">Edit</a>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $plant['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this plant?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
