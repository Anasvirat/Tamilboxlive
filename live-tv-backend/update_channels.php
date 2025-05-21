<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$file = 'channels.json';
$channels = json_decode(file_get_contents($file), true) ?? [];
$action = $_POST['action'] ?? '';
$id = intval($_POST['id'] ?? 0);

if ($action === 'add') {
    // Remove existing channel with same ID (for edit)
    $channels = array_filter($channels, fn($c) => $c['id'] !== $id);

    $channels[] = [
        'id' => $id,
        'name' => $_POST['name'] ?? '',
        'url' => $_POST['url'] ?? '',
        'drm' => ($_POST['drm'] ?? 'false') === 'true',
        'key_id' => $_POST['key_id'] ?? '',
        'key' => $_POST['key'] ?? '',
        'logo' => $_POST['logo'] ?? ''
    ];
} elseif ($action === 'delete') {
    $channels = array_filter($channels, fn($c) => $c['id'] !== $id);
}

file_put_contents($file, json_encode(array_values($channels), JSON_PRETTY_PRINT));
header('Location: admin.php');
exit;
