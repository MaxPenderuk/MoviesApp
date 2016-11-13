<?php
  require 'database.php';

  $movieID = 0;

  if (!empty($_POST['id'])) {
    $movieID = $_POST['id'];
  }


  if (!empty($_POST)) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // first we wanna delete actors
    $sql = "DELETE FROM actor WHERE movie_id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$movieID]);

    $sql = "DELETE FROM movie WHERE id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$movieID]);
    Database::disconnect();
    echo 1;
  } else {
    echo 0;
  }
