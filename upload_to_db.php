<?php

require 'database.php';

// sort out line endings
ini_set('auto_detect_line_endings', true);

$fileName = $_GET['name'];
$filePath = 'uploads/' . $fileName;

if (file_exists($filePath)) {
  // read the file into an array
  $file = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $movieInfo = [];

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $formats = [
    1 => 'VHS',
    2 => 'DVD',
    3 => 'Blu-Ray'
  ];

  $sql = "INSERT INTO movie (name, release_date, format_id) values(?, ?, ?)";
  $query = $pdo->prepare($sql);

  // for rows counting
  $cnt = 0;
  for ($i = 0; $i < count($file); $i++) {
    $movieInfo[] = explode(': ', $file[$i]);
    $cnt++;
    if ($cnt == 4) {
      // format name to index in format DB table
      $movieInfo[2][1] = array_search($movieInfo[2][1], $formats);
      $query->execute([$movieInfo[0][1], $movieInfo[1][1], $movieInfo[2][1]]);
      $lastMovieID = $pdo->lastInsertId();

      $actorsArr = explode(', ', $movieInfo[3][1]);
      $sql = [];
      $question_marks = [];
      $insert_values = [];
      foreach ($actorsArr as $actor) {
        $newActorsArr = explode(' ', $actor);
        $temp = [
          'first_name' => $newActorsArr[0],
          'last_name' => $newActorsArr[1],
          'movie_id' => $lastMovieID
        ];
        $question_marks[] = '(?, ?, ?)';
        $insert_values = array_merge($insert_values, array_values($temp));
        $temp = null;
      }

      $sqlActor = "INSERT INTO actor (first_name, last_name, movie_id) VALUES " . implode(', ', $question_marks);
      $queryA = $pdo->prepare($sqlActor);

      try {
          $queryA->execute($insert_values);
      } catch (PDOException $e){
          echo $e->getMessage();
      }

      $cnt = 0;
      unset($movieInfo);
    }
  }
}

header('Location: index.php');
