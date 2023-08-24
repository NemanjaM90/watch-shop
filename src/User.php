<?php

namespace WatchShop;

use Exception;
use PDO;
use PDOException;

class User
{
  private $db;

  public function __construct()
  {
    $this->db = (new Database())->getConnection();
  }

  public function register(string $username, string $email, string $password)
  {
    // Check if a user with the same username or email already exists
    $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $_SESSION['error'] = 'Username or email already exists!'; // Set the error message here
      return false; // Return false to indicate failure
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$username, $email, $hashedPassword]);

    return true; // Return true to indicate success
  }

  public function login(string $username, string $password)
  {
    // Retrieve the user from the database
    $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
      // Password is correct. Store the user's ID in the session.
      $_SESSION['user_id'] = $user['user_id'];
      return true;
    } else {
      // Invalid username or password
      $_SESSION['error'] = 'Invalid username or password.'; // Set the error message here
      return false;
    }
  }

  public function isLoggedIn()
  {
    // The user is logged in if we have a user_id in the session
    return isset($_SESSION['user_id']);
  }

  public function logout()
  {
    // Remove the user_id from the session
    unset($_SESSION['user_id']);
  }


  public function isAdmin()
  {
    if ($this->isLoggedIn()) {
      $stmt = $this->db->prepare('SELECT email FROM users WHERE user_id = ?');
      $stmt->execute([$_SESSION['user_id']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Let's say the admin is the user with the email 'admin@example.com'
      return $user['email'] === 'admin@admin.com';
    } else {
      return false;
    }
  }
}
