<?php
session_start();
require_once 'connect.php';

// Assume user is logged in and we have $user_id, $email from session
$user_id = $_SESSION['user_id'] ?? null;
$email   = $_SESSION['email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback']);

    if (empty($feedback)) {
        header("Location: Contact.php?status=error&msg=" . urlencode("Feedback message is required."));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO feedback_table (user_id, email, feedback, sent_at) 
                               VALUES (:user_id, :email, :feedback, :sent_at)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':email'   => $email,
            ':feedback' => $feedback,
            ':sent_at' => date('Y-m-d H:i:s')
        ]);

        header("Location: Home.php?status=success&msg=" . urlencode("Feedback submitted successfully!"));
        exit;
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        header("Location: Home.php?status=error&msg=" . urlencode("Error submitting feedback. Try again."));
        exit;
    }
}
