<?php
require 'vendor/autoload.php';
require 'database.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

$movieID = 0;
if (!empty($_GET['id'])) {
  $movieID = $_GET['id'];
}

if ($movieID === null) {
  header('Location: index.php');
} else {
  $pdo = Database::connect();

  // get movie
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM movie WHERE id = ?";
  $query = $pdo->prepare($sql);
  $query->execute([$movieID]);
  $movieData = $query->fetch(PDO::FETCH_ASSOC);

  // get movie format
  $sql = "SELECT name FROM format WHERE id = ?";
  $query = $pdo->prepare($sql);
  $query->execute([$movieData['format_id']]);
  $movieFormat = $query->fetch(PDO::FETCH_ASSOC);

  // get actors
  $sql = "SELECT * FROM actor WHERE movie_id = ?";
  $query = $pdo->prepare($sql);
  $query->execute([$movieID]);
  $movieActors = $query->fetchAll();

  Database::disconnect();
}

echo $twig->render('read.html', [
  'movieID' => $movieID,
  'movieData' => $movieData,
  'movieFormat' => $movieFormat,
  'movieActors' => $movieActors
]);
