<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    protected $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function get_all_users() {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create_user($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO users (username, password)
            VALUES (:username, :password)
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hash);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function checkUsername($username) {
        $stmt = $this->db->prepare("
            SELECT id
              FROM users
             WHERE username = :username
             LIMIT 1
        ");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row === false || $row === null);
    }

    public function processLogin($username, $password) {
        $stmt = $this->db->prepare("
            SELECT *
              FROM users
             WHERE username = :username
             LIMIT 1
        ");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function notEmptyAccount($username, $password) {
        return !empty($username) && !empty($password);
    }
}