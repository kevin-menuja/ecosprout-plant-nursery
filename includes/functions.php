<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function app_url(string $path = ''): string
{
    $root = realpath(__DIR__ . '/..');
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $base = '/EcoSprout_plant_nursery_c';

    if ($root && $documentRoot && strpos($root, $documentRoot) === 0) {
        $base = '/' . trim(str_replace(DIRECTORY_SEPARATOR, '/', substr($root, strlen($documentRoot))), '/');
    }

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function require_login(?string $role = null): void
{
    if (!is_logged_in()) {
        header('Location: ' . app_url('auth/login.php'));
        exit;
    }

    if ($role !== null && ($_SESSION['user']['role'] ?? '') !== $role) {
        header('Location: ' . app_url('index.php'));
        exit;
    }
}

function redirect_by_role(string $role): void
{
    $targets = [
        'customer' => app_url('customer/dashboard.php'),
        'staff' => app_url('staff/dashboard.php'),
        'admin' => app_url('admin/dashboard.php'),
    ];

    header('Location: ' . ($targets[$role] ?? app_url('index.php')));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function show_flash_messages(): void
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    foreach ($messages as $message) {
        $type = $message['type'] === 'success' ? 'success' : 'danger';
        echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
        echo e($message['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function sanitize_text(string $value): string
{
    return trim(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
}

function image_url(?string $url): string
{
    return $url ?: 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=900&q=80';
}

function cart_count(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}
