<?php

namespace WatchShop;

use Exception;
use PDO;
use PDOException;

class Comment
{
  private $db;

  public function __construct()
  {
    $this->db = (new Database())->getConnection();
  }

  public function getCommentsForWatch(int $watch_id)
  {
    $stmt = $this->db->prepare('SELECT comments.*, users.username FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE watch_id = ? ORDER BY created_at DESC');
    $stmt->execute([$watch_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createComment(int $watch_id, int $user_id, string $comment)
  {
    $stmt = $this->db->prepare('INSERT INTO comments (watch_id, user_id, comment) VALUES (?, ?, ?)');

    try {
      $stmt->execute([$watch_id, $user_id, $comment]);
    } catch (PDOException $e) {
      throw new Exception('Error creating comment: ' . $e->getMessage());
    }
  }

  public function getCommentById(int $comment_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM comments WHERE comment_id = ?');
    $stmt->execute([$comment_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updateComment(int $comment_id, int $user_id, string $comment)
  {
    $stmt = $this->db->prepare('SELECT watch_id FROM comments WHERE comment_id = ?');
    $stmt->execute([$comment_id]);
    $watch_id = $stmt->fetchColumn();

    $stmt = $this->db->prepare('UPDATE comments SET comment = ? WHERE comment_id = ? AND user_id = ?');
    $stmt->execute([$comment, $comment_id, $user_id]);

    return $watch_id;
  }

public function deleteComment(int $comment_id)
{
  $stmt = $this->db->prepare('SELECT watch_id FROM comments WHERE comment_id = ?');
  $stmt->execute([$comment_id]);
  $watch_id = $stmt->fetchColumn();

  if ($watch_id) {
    $stmt = $this->db->prepare('DELETE FROM comments WHERE comment_id = ?');
    $stmt->execute([$comment_id]);
    return $watch_id;
  }

  return null; // Comment not found
}
}
