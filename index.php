<?php

require 'vendor/autoload.php';
require 'database.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

$pdo = Database::connect();

//if sort button was clicked - sort movie list by name, otherwise - not
$sql = (empty($_GET['sort'])) ? "SELECT * FROM movie ORDER BY id DESC" :
       "SELECT * FROM movie ORDER BY sort, name";

//search by movie title or actoe's name
if (!empty($_POST['search_by']) && !empty($_POST['search_param'])) {
  $searchParam = $_POST['search_param'];
  $searchBy = $_POST['search_by'];

  if ($searchBy == "title" && $searchParam != "") {
     $sql = "SELECT * FROM movie WHERE name LIKE '%{$searchParam}%'";
  }

  if ($searchBy == "name" && $searchParam != "") {
     $sql = "SELECT movie.id, name, release_date, format_id
      FROM movie JOIN actor ON movie.id = actor.movie_id WHERE first_name LIKE '{$searchParam}'";
  }
}

$dataArr = [];
foreach ($pdo->query($sql) as $key => $row) {
         //fetch format for each movie from `format` table in DB
         $formatQuery = $pdo->query("SELECT `name` FROM `format` where `id` = {$row['format_id']} LIMIT 1");
         $format = $formatQuery->fetch(PDO::FETCH_ASSOC);
         $dataArr[$key]['id'] = $row['id'];
         $dataArr[$key]['name'] = $row['name'];
         $dataArr[$key]['release_date'] = $row['release_date'];
         $dataArr[$key]['format'] = $format['name'];
}
Database::disconnect();

// send data to `index.html` view
echo $twig->render('index.html', [
  'data' => $dataArr
]);
