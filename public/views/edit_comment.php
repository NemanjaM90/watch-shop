<?php include "navbar.php" ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<form action="/update_comment" method="post">
  <input type="hidden" name="comment_id" value="<?= htmlspecialchars($commentData['comment_id']) ?>">
  <textarea name="comment"><?= htmlspecialchars($commentData['comment']) ?></textarea>
  <input type="submit" value="Update Comment">
</form>
