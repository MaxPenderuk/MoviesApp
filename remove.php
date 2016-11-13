<?php

$fileName = $_GET['name'];
$filePath = 'uploads/' . $fileName;

// remove file if exists
if (file_exists($filePath)) {
  unlink($filePath);
  header('Location: upload.php');
}
