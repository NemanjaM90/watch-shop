<?php include "navbar.php" ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<div class="home-hero"></div> <!-- Hero Image -->

<div class="container">
  <?php foreach ($watches as $watch) : ?>
    <div class="watch-card">
      <h2><?= htmlspecialchars($watch['brand']) ?> <?= htmlspecialchars($watch['model']) ?></h2>
      <img src="<?= htmlspecialchars($watch['image_path']) ?>" alt="<?= htmlspecialchars($watch['brand']) ?> <?= htmlspecialchars($watch['model']) ?>">
      <p>Price: <?= htmlspecialchars($watch['price']) ?></p>
      <p><a href="/watch?id=<?= urlencode($watch['watch_id']) ?>">View Details</a></p>
    </div>
  <?php endforeach; ?>
</div>
