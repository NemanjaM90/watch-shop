<?php include "navbar.php" ?>

<?php if (isset($_SESSION['error'])) : ?>
  <div class="error-message">
    <p><?= htmlspecialchars($_SESSION['error']) ?></p>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<form action="/create_watch" method="post" enctype="multipart/form-data">
  <input type="text" name="brand" placeholder="Brand">
  <input type="text" name="model" placeholder="Model">
  <input type="number" step="0.01" name="price" placeholder="Price">
  <textarea name="description" placeholder="Description"></textarea>
  <input type="file" name="image">
  <input type="submit" value="Create Watch">
</form>
