<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

// Make sure user is logged in
if (!isset($_SESSION['user']['id']) || !isset($_SESSION['user']['email'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "You must be logged in to submit feedback."
    ]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$email   = $_SESSION['user']['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback'] ?? '');

    if (empty($feedback)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Feedback message is required."
        ]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, email, feedback) 
                               VALUES (:user_id, :email, :feedback)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':email'   => $email,
            ':feedback' => $feedback
        ]);

        echo json_encode([
            "status" => "success",
            "msg" => "Feedback submitted successfully!"
        ]);
        exit;
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        echo json_encode([
            "status" => "error",
            "msg" => "Error submitting feedback. Try again."
        ]);
        exit;
    }
}
