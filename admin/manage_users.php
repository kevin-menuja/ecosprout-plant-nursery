<?php
require_once __DIR__ . '/../config/db.php';
require_login('admin');
$pageTitle = 'Manage Users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id > 0 && $id !== current_user()['id']) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'User deleted.');
        header('Location: manage_users.php');
        exit;
    }

    $name = sanitize_text($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = sanitize_text($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? 'customer';
    $password = $_POST['password'] ?? '';

    if ($action === 'save' && $name && $email && in_array($role, ['customer', 'staff', 'admin'], true)) {
        if ($id > 0) {
            if ($password) {
                $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, phone=?, role=?, password_hash=? WHERE id=?');
                $stmt->execute([$name, $email, $phone, $role, password_hash($password, PASSWORD_BCRYPT), $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, phone=?, role=? WHERE id=?');
                $stmt->execute([$name, $email, $phone, $role, $id]);
            }
            flash('success', 'User updated.');
        } else {
            if (strlen($password) < 6) {
                flash('error', 'New users need a password of at least 6 characters.');
                header('Location: manage_users.php');
                exit;
            }
            $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, role, password_hash) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, $phone, $role, password_hash($password, PASSWORD_BCRYPT)]);
            flash('success', 'User created.');
        }
        header('Location: manage_users.php');
        exit;
    }
}

$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editUser = $stmt->fetch();
}
$users = $pdo->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY role, name')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Manage Users</h1>
        <form method="post" class="auth-panel mb-4">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) ($editUser['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($editUser['name'] ?? '') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($editUser['email'] ?? '') ?>" required></div>
                <div class="col-md-2"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= e($editUser['phone'] ?? '') ?>"></div>
                <div class="col-md-2"><label class="form-label">Role</label><select class="form-select" name="role"><?php foreach (['customer','staff','admin'] as $role): ?><option value="<?= $role ?>" <?= ($editUser['role'] ?? '') === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-2"><label class="form-label">Password</label><input class="form-control" type="password" name="password" placeholder="<?= $editUser ? 'Leave blank' : 'Required' ?>"></div>
                <div class="col-12"><button class="btn btn-success" type="submit">Save User</button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table bg-white align-middle">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e(ucfirst($user['role'])) ?></td>
                        <td><?= e($user['phone']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-success" href="?edit=<?= (int) $user['id'] ?>">Edit</a>
                            <?php if ((int) $user['id'] !== current_user()['id']): ?>
                                <form method="post" class="d-inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $user['id'] ?>"><button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this user?')">Delete</button></form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
