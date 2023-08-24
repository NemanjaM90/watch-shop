<?php
use WatchShop\User;

$user = new User();
?>

<nav>
  <a href="/" class="home-link">Home</a>
  <?php if ($user->isLoggedIn() && $user->isAdmin()) : ?>
    <a href="/create_watch" class="admin-link">Create Watch</a>
  <?php endif; ?>
  <?php if ($user->isLoggedIn()) : ?>
    <a href="/logout">Logout</a>
  <?php else : ?>
    <a href="/login">Login</a>
    <a href="/register">Register</a>
  <?php endif; ?>
</nav>
