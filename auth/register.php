<?php
require_once __DIR__ . '/../config/db.php';
$pageTitle = 'Register';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_text($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = sanitize_text($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $phone && strlen($password) >= 6) {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, "customer", ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT), $phone]);
            flash('success', 'Registration successful. Please login.');
            header('Location: login.php');
            exit;
        } catch (PDOException $exception) {
            flash('error', 'That email address is already registered.');
        }
    } else {
        flash('error', 'Please enter valid details. Password must be at least 6 characters.');
    }
}

include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form method="post" class="auth-panel needs-validation" novalidate>
                    <h1 class="section-title mb-3">Customer Registration</h1>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input class="form-control" name="phone" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" minlength="6" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success w-100" type="submit">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
