<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Contact';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_text($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $subject = sanitize_text($_POST['subject'] ?? '');
    $message = sanitize_text($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {
        $stmt = $pdo->prepare('INSERT INTO queries (customer_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([current_user()['id'] ?? null, $name, $email, $subject, $message]);
        flash('success', 'Your query has been submitted.');
        header('Location: contact.php');
        exit;
    }

    flash('error', 'Please complete every field with a valid email address.');
}

include __DIR__ . '/includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <h1 class="section-title">Contact EcoSprout</h1>
                <p>Send a plant care question, service inquiry, or workshop request to the nursery team.</p>
                <p><i class="fa-solid fa-location-dot me-2 text-success"></i>Kegalle, Sri Lanka</p>
                <p><i class="fa-solid fa-phone me-2 text-success"></i>+94 77 123 4567</p>
            </div>
            <div class="col-lg-7">
                <form method="post" class="auth-panel needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input class="form-control" name="subject" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="5" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success" type="submit">Submit Query</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
