<?php include "navbar.php" ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<div class="single-watch-container">
  <h1><?= htmlspecialchars($watchData['brand']) ?> <?= htmlspecialchars($watchData['model']) ?></h1>
  <img src="<?= htmlspecialchars($watchData['image_path']) ?>" alt="<?= htmlspecialchars($watchData['brand']) ?> <?= htmlspecialchars($watchData['model']) ?>">
  <p>Price: <?= htmlspecialchars($watchData['price']) ?></p>
  <p><?= nl2br(htmlspecialchars($watchData['description'])) ?></p>
  <?php if ($user->isAdmin()) : ?>
    <p><a href="/delete_watch?id=<?= urlencode($watchData['watch_id']) ?>">Delete Watch</a></p>
    <p><a href="/edit_watch?id=<?= urlencode($watchData['watch_id']) ?>">Edit Watch</a></p>
  <?php endif; ?>

  <div class="comment-section">
    <?php
    foreach ($comments as $comment) : ?>
      <div class="comment">
        <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
        <p>By <?= htmlspecialchars($comment['username']) ?> on <?= htmlspecialchars($comment['created_at']) ?></p>

        <?php if ($user->isLoggedIn() && $_SESSION['user_id'] === $comment['user_id']) : ?>
          <a href="/edit_comment?comment_id=<?= urlencode($comment['comment_id']) ?>">Edit</a>
        <?php endif; ?>

        <?php if ($user->isLoggedIn() && ($_SESSION['user_id'] === $comment['user_id'] || $user->isAdmin())) : ?>
          <a href="/delete_comment?comment_id=<?= urlencode($comment['comment_id']) ?>">Delete</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <?php
    if ($user->isLoggedIn()) : ?>
      <form action="/add_comment" method="post" class="comment-form">
        <input type="hidden" name="watch_id" value="<?= htmlspecialchars($watchData['watch_id']) ?>">
        <textarea name="comment" placeholder="Your comment"></textarea>
        <input type="submit" value="Post Comment">
      </form>
    <?php endif; ?>
  </div>
</div>
