<?php

// db.php
// Database interaction functions

require_once 'config.php';

// Establish database connection
function getDbConnection() {
    try {
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        $pdo = new PDO($dsn, $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Insert a new account
function insertAccount($user_id, $website_name, $url, $password, $comment) {
    $pdo = getDbConnection();
    $query = "INSERT INTO accounts (user_id, website_name, url) VALUES (:user_id, :website_name, :url)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'website_name' => $website_name, 'url' => $url]);
    $account_id = $pdo->lastInsertId();

    $query = "INSERT INTO passwords (account_id, password, comment) VALUES (:account_id, AES_ENCRYPT(:password, 'secret_key'), :comment)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['account_id' => $account_id, 'password' => $password, 'comment' => $comment]);

    return $account_id;
}

// Search accounts
function searchAccounts($pattern) {
    $pdo = getDbConnection();
    $query = "SELECT accounts.id, website_name, url, passwords.comment, passwords.created_at 
              FROM accounts 
              JOIN passwords ON accounts.id = passwords.account_id 
              WHERE website_name LIKE :pattern OR url LIKE :pattern";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['pattern' => "%$pattern%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update an account
function updateAccount($column, $newValue, $patternColumn, $patternValue) {
    $pdo = getDbConnection();
    $query = "UPDATE accounts 
              SET $column = :newValue 
              WHERE $patternColumn LIKE :patternValue";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['newValue' => $newValue, 'patternValue' => "%$patternValue%"]);
}

// Delete an account
function deleteAccount($patternColumn, $patternValue) {
    $pdo = getDbConnection();
    $query = "DELETE FROM accounts WHERE $patternColumn LIKE :patternValue";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['patternValue' => "%$patternValue%"]);
}

?>
