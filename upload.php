<?php

require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

$response = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_FILES['file']['name'];
  $tmpName = $_FILES['file']['tmp_name'];
  $error = $_FILES['file']['error'];
  $size = $_FILES['file']['size'];
  $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

  switch ($error) {
    case UPLOAD_ERR_OK:
      $is_valid = true;

      // validate extension
      if ($extension != 'txt') {
        $is_valid = false;
        $response = 'Invalid file extension.';
      }

      // validate file size
      if ($size/1024/1024 > 2) {
        $is_valid = false;
        $response = 'File size is exceeding maximum allowed size.';
      }

      // upload file
      if ($is_valid) {
        $targetPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $name;
        move_uploaded_file($tmpName, $targetPath);
        header('Location: upload.php');
        exit;
      }
      break;

    case UPLOAD_ERR_INI_SIZE:
      $response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
      break;

    case UPLOAD_ERR_FORM_SIZE:
      $response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
      break;

    case UPLOAD_ERR_PARTIAL:
      $response = 'The uploaded file was only partially uploaded.';
      break;

    case UPLOAD_ERR_NO_FILE:
      $response = 'No file was uploaded.';
      break;

    case UPLOAD_ERR_NO_TMP_DIR:
      $response = 'Missing a temporary folder.';
      break;

    case UPLOAD_ERR_CANT_WRITE:
      $response = 'Failed to write file to disk.';
      break;

    case UPLOAD_ERR_EXTENSION:
      $response = 'File upload stopped by extension.';
      break;

    default:
      $response = 'Unknown error.';
      break;
  }
}

// scan uploads folder
$folder = "uploads";
$scanRes = scandir('uploads');
$resultArr = [];

foreach ($scanRes as $res) {
  if ($res === '.' || $res === '..') continue;

  if (is_file($folder . '/' . $res)) {
    $resultArr[] = $res;
  }
}

echo $twig->render('upload.html', [
  'response' => $response,
  'folder' => $folder,
  'scanRes' => $resultArr
]);
