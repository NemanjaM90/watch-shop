<?php include "navbar.php" ?>

<link rel="stylesheet" href="/assets/css/styles.css">

<?php if (isset($_SESSION['error'])) : ?>
  <div class="error-message">
    <p><?= htmlspecialchars($_SESSION['error']) ?></p>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="/login" method="post">
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <input type="submit" value="Login">
</form>
