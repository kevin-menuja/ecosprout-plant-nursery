<?php
require_once __DIR__ . '/config/db.php';
$pageTitle = 'Blog';
$posts = $pdo->query('SELECT * FROM blog_posts ORDER BY created_at DESC')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Blog & Resources</h1>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="blog-card">
                        <img src="<?= e(image_url($post['image_path'])) ?>" class="card-img-top" alt="<?= e($post['title']) ?>">
                        <div class="p-3">
                            <span class="badge badge-category mb-2"><?= e($post['category']) ?></span>
                            <h5 class="fw-bold"><?= e($post['title']) ?></h5>
                            <p><?= e(substr($post['content'], 0, 170)) ?>...</p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
