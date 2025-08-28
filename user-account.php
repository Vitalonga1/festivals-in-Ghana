<?php
session_start();
require_once 'connect.php';

// Initialize errors array and success message
$_SESSION['errors'] = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    // Sanitize and validate inputs
    $Fname = trim(filter_input(INPUT_POST, 'Fname', FILTER_SANITIZE_STRING));
    $Lname = trim(filter_input(INPUT_POST, 'Lname', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];

    // Validate inputs with more comprehensive checks
    if (empty($Fname) || !preg_match('/^[a-zA-Z\s\-]{2,50}$/', $Fname)) {
        $_SESSION['errors']['Fname'] = 'Valid first name (2-50 chars) is required';
    }
    if (empty($Lname) || !preg_match('/^[a-zA-Z\s\-]{2,50}$/', $Lname)) {
        $_SESSION['errors']['Lname'] = 'Valid last name (2-50 chars) is required';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = 'Invalid email format';
    }
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $_SESSION['errors']['password'] = 'Password must be 8+ chars with at least 1 number and 1 uppercase letter';
    }
    if ($password !== $confirmPassword) {
        $_SESSION['errors']['confirm_password'] = 'Passwords do not match';
    }
    if (empty($dob) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
        $_SESSION['errors']['dob'] = 'Valid date of birth (YYYY-MM-DD) is required';
    }
    if (empty($gender)) {
        $_SESSION['errors']['gender'] = 'Valid gender selection is required';
    }
    if (empty($country)) {
        $_SESSION['errors']['country'] = 'Country is required';
    }

    // Check if email exists (only if email is valid)
    if (!isset($_SESSION['errors']['email'])) {
        try {
            $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $_SESSION['errors']['email'] = 'Email is already registered';
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['errors']['database'] = 'Registration temporarily unavailable';
        }
    }

    // If no errors, proceed with registration
    if (empty($_SESSION['errors'])) {
        try {
            // Generate password hash with cost factor
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            // Insert user with prepared statement
            $stmt = $pdo->prepare("INSERT INTO users 
                                  (Fname, Lname, email, password, dob, gender, country, created_at) 
                                  VALUES 
                                  (:Fname, :Lname, :email, :password, :dob, :gender, :country, NOW())");

            $stmt->execute([
                'Fname' => $Fname,
                'Lname' => $Lname,
                'email' => $email,
                'password' => $hashedPassword,
                'dob' => $dob,
                'gender' => $gender,
                'country' => $country
                // created_at handled by NOW()
            ]);

            // Get the new user ID
            $userId = $pdo->lastInsertId();

            // Set session variables
            $_SESSION['registration_success'] = true;
            $_SESSION['new_user_email'] = $email; // For welcome message

            // Send welcome email (implement separately)
            // sendWelcomeEmail($email, $Fname);

            // Redirect to success page
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['errors']['database'] = 'Registration failed. Please try again.';
            header('Location: register.php');
            exit();
        }
    } else {
        // Preserve form input for user convenience
        $_SESSION['form_input'] = [
            'Fname' => $Fname,
            'Lname' => $Lname,
            'email' => $email,
            'dob' => $dob,
            'gender' => $gender,
            'country' => $country
        ];
        header('Location: register.php');
        exit();
    }
} else {
    // Not a POST request or signup not set
    header('Location: register.php');
    exit();
}
