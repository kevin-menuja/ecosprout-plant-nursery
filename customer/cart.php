<?php
require_once __DIR__ . '/../config/db.php';
require_login('customer');
$pageTitle = 'Shopping Cart';
$_SESSION['cart'] = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $plantId = (int) ($_POST['plant_id'] ?? 0);

    if ($action === 'add' && $plantId > 0) {
        $_SESSION['cart'][$plantId] = ($_SESSION['cart'][$plantId] ?? 0) + 1;
        flash('success', 'Plant added to cart.');
        header('Location: cart.php');
        exit;
    }

    if ($action === 'update' && $plantId > 0) {
        $qty = max(0, (int) ($_POST['quantity'] ?? 0));
        if ($qty === 0) {
            unset($_SESSION['cart'][$plantId]);
        } else {
            $_SESSION['cart'][$plantId] = $qty;
        }
        flash('success', 'Cart updated.');
        header('Location: cart.php');
        exit;
    }

    if ($action === 'checkout' && $_SESSION['cart']) {
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM plants WHERE id IN ({$placeholders})");
        $stmt->execute($ids);
        $plants = $stmt->fetchAll();
        $total = 0;

        foreach ($plants as $plant) {
            $total += (float) $plant['price'] * (int) $_SESSION['cart'][$plant['id']];
        }

        $pdo->beginTransaction();
        $orderStmt = $pdo->prepare('INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, "processing")');
        $orderStmt->execute([current_user()['id'], $total]);
        $orderId = (int) $pdo->lastInsertId();
        $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, plant_id, quantity, unit_price) VALUES (?, ?, ?, ?)');

        foreach ($plants as $plant) {
            $itemStmt->execute([$orderId, $plant['id'], $_SESSION['cart'][$plant['id']], $plant['price']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];
        flash('success', 'Payment simulated and order placed successfully.');
        header('Location: orders.php');
        exit;
    }
}

$cartPlants = [];
$total = 0;
if ($_SESSION['cart']) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM plants WHERE id IN ({$placeholders})");
    $stmt->execute($ids);
    $cartPlants = $stmt->fetchAll();
    foreach ($cartPlants as $plant) {
        $total += (float) $plant['price'] * (int) $_SESSION['cart'][$plant['id']];
    }
}

include __DIR__ . '/../includes/header.php';
?>
<section class="section-band">
    <div class="container">
        <h1 class="section-title mb-4">Shopping Cart</h1>
        <?php if (!$cartPlants): ?>
            <p>Your cart is empty.</p>
            <a class="btn btn-success" href="<?= e(app_url('catalogue.php')) ?>">Browse Plants</a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle bg-white">
                    <thead><tr><th>Plant</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($cartPlants as $plant): ?>
                        <tr>
                            <td><img src="<?= e(image_url($plant['image_path'])) ?>" alt=""> <?= e($plant['name']) ?></td>
                            <td>LKR <?= number_format((float) $plant['price'], 2) ?></td>
                            <td>
                                <form method="post" class="d-flex gap-2">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="plant_id" value="<?= (int) $plant['id'] ?>">
                                    <input class="form-control form-control-sm" type="number" name="quantity" value="<?= (int) $_SESSION['cart'][$plant['id']] ?>" min="0" style="max-width:90px">
                                    <button class="btn btn-outline-success btn-sm" type="submit">Update</button>
                                </form>
                            </td>
                            <td>LKR <?= number_format((float) $plant['price'] * (int) $_SESSION['cart'][$plant['id']], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <form method="post" class="auth-panel mt-4">
                <h5 class="fw-bold">Simulated Secure Payment</h5>
                <input type="hidden" name="action" value="checkout">
                <div class="row g-3">
                    <div class="col-md-4"><input class="form-control" placeholder="Card number" required></div>
                    <div class="col-md-2"><input class="form-control" placeholder="MM/YY" required></div>
                    <div class="col-md-2"><input class="form-control" placeholder="CVV" required></div>
                    <div class="col-md-4 text-md-end"><strong>Total: LKR <?= number_format($total, 2) ?></strong></div>
                    <div class="col-12"><button class="btn btn-success" type="submit">Place Order</button></div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
