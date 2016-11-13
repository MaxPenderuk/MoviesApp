<?php

class Database {
  private static $dbName = 'movies';
  private static $dbHost = 'localhost';
  private static $dbUsername = 'root';
  private static $dbUserPassword = 'collateraldamage1995s';
  private static $conf = null;

  public function __construct() {
    die('Initialization is not allowed!');
  }

  // connect to database
  public static function connect() {
    if (self::$conf == null) {
      try {
        self::$conf = new PDO("mysql:host=".self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword);
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }
    return self::$conf;
  }

  // disconnect from database
  public static function disconnect() {
    self::$conf = null;
  }
}
