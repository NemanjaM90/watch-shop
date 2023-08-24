<?php

namespace WatchShop;

use Exception;
use PDO;
use PDOException;

class Watch
{
  private $db;

  public function __construct()
  {
    $this->db = (new Database())->getConnection();
  }

  public function getAllWatches()
  {
    $stmt = $this->db->prepare('SELECT * FROM watches');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getWatchById(int $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM watches WHERE watch_id = ?');
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createWatch(string $brand, string $model, float $price, string $description, $image)
  {
    $image_path = null;

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
      $image_path = '/storage/' . basename($image['name']);
      move_uploaded_file($image['tmp_name'], __DIR__ . '/../public' . $image_path);
    }

    $stmt = $this->db->prepare('INSERT INTO watches (brand, model, price, description, image_path) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$brand, $model, $price, $description, $image_path]);
  }

  public function updateWatch(int $id, string $brand, string $model, float $price, string $description, $image = null)
  {
    $watchData = $this->getWatchById($id);

    $image_path = $watchData['image_path'];

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
      $image_path = '/storage/' . basename($image['name']);
      move_uploaded_file($image['tmp_name'], __DIR__ . '/../public' . $image_path);
    }

    $stmt = $this->db->prepare('UPDATE watches SET brand = ?, model = ?, price = ?, description = ?, image_path = ? WHERE watch_id = ?');
    $stmt->execute([$brand, $model, $price, $description, $image_path, $id]);
  }

  public function deleteWatch(int $id)
  {
    $stmt = $this->db->prepare('DELETE FROM watches WHERE watch_id = ?');

    try {
      $stmt->execute([$id]);
    } catch (PDOException $e) {
      throw new Exception('Error deleting watch: ' . $e->getMessage());
    }
  }
}
