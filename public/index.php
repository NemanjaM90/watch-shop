<?php

require '../vendor/autoload.php';

use WatchShop\User;
use WatchShop\Watch;
use WatchShop\Comment;

session_start();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define the route for the homepage
if ($request == '/') {
  $watch = new Watch();
  $watches = $watch->getAllWatches();

  // Include the view for the homepage
  include 'views/home.php';
} elseif ($request == '/watch') {
  // Define the route for displaying a single watch
  // The id of the watch would usually be in the URL, something like /watch?id=1
  $id = $_GET['id'] ?? null;

  if ($id) {
    $watch = new Watch();
    $watchData = $watch->getWatchById($id);

    $comment = new Comment();
    $comments = $comment->getCommentsForWatch($id);

    // Include the view for displaying a single watch
    include 'views/watch.php';
  } else {
    echo '404 Not Found';
  }
} elseif ($request == '/create_watch') {
  $user = new User();

  if ($user->isAdmin()) {
    // The current user is an admin, allow them to create a watch
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $brand = $_POST['brand'];
      $model = $_POST['model'];
      $price = $_POST['price'];
      $description = $_POST['description'];

      // Check if any required fields are empty
      if (empty($brand) || empty($model) || empty($price)) {
        $_SESSION['error'] = 'All fields are required!';
        header('Location: /create_watch'); // Redirect back to the form
        exit;
      }

      $watch = new Watch();
      $watch->createWatch($brand, $model, (float)$price, $description, $_FILES['image'] ?? null);
      header('Location: /');
      exit;
    }

    include 'views/create_watch.php';
  } else {
    // The current user is not an admin, redirect them to the home page
    header('Location: /');
    exit;
  }
} elseif ($request == '/edit_watch') {
  $user = new User();

  if (!$user->isAdmin()) {
    header('Location: /');
    exit;
  }

  $id = $_GET['id'] ?? null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $watch = new Watch();
    $watch->updateWatch($id, $_POST['brand'], $_POST['model'], $_POST['price'], $_POST['description'], $_FILES['image'] ?? null);
    header('Location: /watch?id=' . $id);
    exit;
  }

  if ($id) {
    $watch = new Watch();
    $watchData = $watch->getWatchById($id);

    include 'views/edit_watch.php';
  } else {
    echo '404 Not Found';
  }
} elseif ($request == '/delete_watch') {
  $user = new User();

  if (!$user->isAdmin()) {
    header('Location: /');
    exit;
  }

  $id = $_GET['id'] ?? null;

  if ($id) {
    $watch = new Watch();
    $watch->deleteWatch($id);
    header('Location: /');
    exit;
  } else {
    echo '404 Not Found';
  }
} elseif ($request == '/login') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    if ($user->login($_POST['username'], $_POST['password'])) {
      header('Location: /');
      exit;
    } else {
      $error = 'Invalid username or password.';
    }
  }

  include 'views/login.php';
} elseif ($request == '/logout') {
  $user = new User();
  $user->logout();
  header('Location: /');
} elseif ($request == '/register') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    if ($user->register($_POST['username'], $_POST['email'], $_POST['password'])) {
      $_SESSION['success'] = 'Registration successful! You can now log in.';
      header('Location: /login');
    } else {
      header('Location: /register');
    }
    exit;
  }

  include 'views/register.php';
} elseif ($request == '/logout') {
  $user = new User();
  $user->logout();

  // Redirect to the homepage
  header('Location: /');
} elseif ($request == '/add_comment') {
  // This would be a POST route, so we would get the data from $_POST
  $watch_id = $_POST['watch_id'] ?? null;
  $comment_text = $_POST['comment'] ?? null;

  if ($watch_id && $comment_text) {
    $comment = new Comment();
    $comment->createComment($watch_id, $_SESSION['user_id'], $comment_text);
  }

  // Redirect back to the watch page
  header('Location: /watch?id=' . $watch_id);
} elseif ($request == '/edit_comment') {
  // This would be a GET route, so we would get the data from $_GET
  $comment_id = $_GET['comment_id'] ?? null;

  if ($comment_id) {
    $comment = new Comment();
    $commentData = $comment->getCommentById($comment_id);

    // Include the view for editing a single comment
    include 'views/edit_comment.php';
  } else {
    echo '404 Not Found';
  }
} elseif ($request == '/update_comment') {
  // This would be a POST route, so we would get the data from $_POST
  $comment_id = $_POST['comment_id'] ?? null;
  $comment_text = $_POST['comment'] ?? null;

  if ($comment_id && $comment_text) {
    $comment = new Comment();
    $watch_id = $comment->updateComment($comment_id, $_SESSION['user_id'], $comment_text);
  }

  // Redirect back to the watch page
  header('Location: /watch?id=' . $watch_id);
} elseif ($request == '/delete_comment') {
  $comment_id = $_GET['comment_id'] ?? null;
  $user = new User();

  if ($comment_id) {
    $comment = new Comment();
    $commentData = $comment->getCommentById($comment_id);

    // Check if the user is the owner or an admin
    if ($commentData && ($user->isAdmin() || $_SESSION['user_id'] === $commentData['user_id'])) {
      $watch_id = $comment->deleteComment($comment_id);
      if ($watch_id) {
        // Redirect back to the watch page
        header('Location: /watch?id=' . $watch_id);
        exit;
      }
    }
  }

  // If we reach here, something went wrong, so redirect to the homepage
  header('Location: /');
  exit;
}
