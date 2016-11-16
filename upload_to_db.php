<?php

require 'database.php';

// sort out line endings
ini_set('auto_detect_line_endings', true);

$fileName = $_GET['name'];
$filePath = 'uploads/' . $fileName;

function checkMovieTitle($movieName) {
  if (preg_match('/^[0-9\s\-\:,.:?$]+$/', $movieName)) {
    $sort = 0;
  } else if (preg_match('/^[a-zA-Z0-9\s\-\:,.:?$]+$/', $movieName)) {
    $sort = 1;
  } else if (preg_match('/^[\p{Cyrillic}0-9\s\-\:,.:?$]+$/u', $movieName)) {
    $sort = 2;
  } else {
    $sort = 3;
  }
  return $sort;
}

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

  $sql = "INSERT INTO movie (name, release_date, format_id, sort) values(?, ?, ?, ?)";
  $query = $pdo->prepare($sql);

  // for rows counting
  $cnt = 0;
  for ($i = 0; $i < count($file); $i++) {
    $movieInfo[] = explode(': ', $file[$i]);
    $cnt++;
    if ($cnt == 4) {
      $sort = 0;
      $movieTitleResp = checkMovieTitle($movieInfo[0][1]);
      if($movieTitleResp !== 3) {
        $sort = $movieTitleResp;
      } else {
        throw new Exception('Only English, Ukrainian and Russian names are allowed without special symbols!');
      }
      // format name to index in format DB table
      $movieInfo[2][1] = array_search($movieInfo[2][1], $formats);
      $query->execute([$movieInfo[0][1], $movieInfo[1][1], $movieInfo[2][1], $sort]);
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
