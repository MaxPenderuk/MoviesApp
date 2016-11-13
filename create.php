<?php
  require 'vendor/autoload.php';
  require 'database.php';

  $loader = new Twig_Loader_Filesystem('views');
  $twig = new Twig_Environment($loader);


  $formatsList = [];
  $errorsList = [];
  $pdo = Database::connect();

  //get all formats for select tag on `create.php`
  $sql = 'SELECT * from format';
  foreach ($pdo->query($sql) as $key => $row) {
    $formatsList[$key]['id'] = $row['id'];
    $formatsList[$key]['name'] = $row['name'];
  }
  Database::disconnect();


  if (!empty($_POST)) {
    // validation errors
    $nameError = null;
    $dateError = null;
    $actorFirstNameError = null;
    $actorLastNameError = null;

    // our values from form
    $name = $_POST['name'];
    $releaseDate = $_POST['release_date'];
    $format = $_POST['format'];
    $actorsArr = $_POST['actors'];
    if (count($actorsArr) !== 0) {
      if ($actorsArr[0]['first_name'] === "") $actorFirstNameError = 'Empty field';
      if ($actorsArr[0]['last_name'] === "") $actorLastNameError = 'Empty field';
    }

    // check if the form fields are filled
    $is_valid = true;
    if (empty($name)) {
      $nameError = 'Please enter a movie title';
      $is_valid = false;
    }
    if (empty($releaseDate)) {
      $dateError = 'Enter the movie release date';
      $is_valid = false;
    } else {
      if (intval($releaseDate) === 0 || strlen($releaseDate) !== 4) {
          $dateError = 'Enter a four digit year';
          $is_valid = false;
      }
    }
    if (!empty($actorFirstNameError) || !empty($actorLastNameError)) {
      $is_valid = false;
    }

    $errorsList = [
      'nameError' => $nameError,
      'releaseDate' => $releaseDate,
      'dateError' => $dateError,
      'actorFirstNameError' => $actorFirstNameError,
      'actorLastNameError' => $actorLastNameError
    ];

    // if it was no errors flush data into DB
    if ($is_valid) {
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO movie (name, release_date, format_id) values(?, ?, ?)";
      $query = $pdo->prepare($sql);
      $query->execute([$name, $releaseDate, $format]);
      $lastMovieID = $pdo->lastInsertId();

      foreach ($actorsArr as $actor) {
          $sql = "INSERT INTO actor (first_name, last_name, movie_id) values(?, ?, ?)";
          $query = $pdo->prepare($sql);
          $query->execute([$actor['first_name'], $actor['last_name'], $lastMovieID]);
      }
      Database::disconnect();
      header('Location: index.php');
    }
}

echo $twig->render('create.html', [
  'errorsList' => $errorsList,
  'formatsList' => $formatsList
]);
