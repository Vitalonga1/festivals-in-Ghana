<?php
session_start(); // Always start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(:email)");
    $stmt->execute(['email' => $email]);
    $users = $stmt->fetch();

    if ($users && password_verify($password, $users['password'])) {
        $_SESSION['user'] = [
            'id' => $users['id'],
            'email' => $users['email'],
            'Fname' => $users['Fname'],
            'Lname' => $users['Lname'],
            'created_at' => $users['created_at']
        ];

        header('Location: Home.php'); // Ensure this path is correct
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }
}
