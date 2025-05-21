<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$channels = json_decode(file_get_contents('channels.json'), true) ?? [];
?>

<a href="login.php?logout=1">Logout</a>

<h2>Add / Edit Channel</h2>
<form action="update_channels.php" method="POST">
  <input type="hidden" name="action" value="add">
  <label>ID: <input type="number" name="id" required></label><br>
  <label>Name: <input type="text" name="name" required></label><br>
  <label>Stream URL (.m3u8 / .ts): <input type="text" name="url" required></label><br>
  <label>Is DRM:
    <select name="drm">
      <option value="false">No</option>
      <option value="true">Yes</option>
    </select>
  </label><br>
  <label>Key ID (if DRM): <input type="text" name="key_id"></label><br>
  <label>Key (if DRM): <input type="text" name="key"></label><br>
  <label>Logo URL (PNG/JPG): <input type="text" name="logo"></label><br>
  <button type="submit">Save Channel</button>
</form>

<h2>Channel List</h2>
<ul>
<?php foreach ($channels as $channel): ?>
  <li>
    <strong><?= htmlspecialchars($channel['name']) ?></strong><br>
    URL: <?= htmlspecialchars($channel['url']) ?><br>
    DRM: <?= $channel['drm'] ? "Yes" : "No" ?><br>
    <?php if (!empty($channel['logo'])): ?>
      <img src="<?= htmlspecialchars($channel['logo']) ?>" alt="Logo" width="100"><br>
    <?php endif; ?>
    <?php if ($channel['drm']): ?>
      Key ID: <?= htmlspecialchars($channel['key_id']) ?><br>
      Key: <?= htmlspecialchars($channel['key']) ?><br>
    <?php endif; ?>
    <form action="update_channels.php" method="POST" style="display:inline;">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?= $channel['id'] ?>">
      <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
    </form>
  </li>
<?php endforeach; ?>
</ul>
