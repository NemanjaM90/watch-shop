<?php include "navbar.php" ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<form action="/edit_watch?id=<?= urlencode($watchData['watch_id']) ?>" method="post" enctype="multipart/form-data">
  <input type="text" name="brand" value="<?= htmlspecialchars($watchData['brand']) ?>">
  <input type="text" name="model" value="<?= htmlspecialchars($watchData['model']) ?>">
  <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($watchData['price']) ?>">
  <textarea name="description"><?= htmlspecialchars($watchData['description']) ?></textarea>
  <input type="file" name="image">
  <input type="submit" value="Update Watch">
</form>
