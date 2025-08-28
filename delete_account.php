<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect to login page
    exit();
}

// Database connection
$host = 'localhost'; // or your host
$dbname = 'group11';
$username = 'root'; // your database username
$password = ''; // your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get user ID from session - assuming it's stored in $_SESSION['user']['id']
// Adjust this based on how you actually store user data in session
$user_id = $_SESSION['user']['id'] ?? null;

if (!$user_id) {
    die("User ID not found in session");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    try {
        // Delete user from the database
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        // Destroy session and logout
        session_destroy();
        header("Location: logout.php");
        exit();
    } catch(PDOException $e) {
        $error = "Error deleting account: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .btn {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-cancel {
            background-color: #007bff;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<h2>Are you sure you want to delete your account?</h2>

<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

<form action="" method="POST">
    <button type="submit" name="confirm_delete" class="btn btn-delete">
        <i class="fas fa-trash-alt"></i> Yes, Delete My Account
    </button>
</form>

<a href="profile.php" class="btn btn-cancel"><i class="fas fa-arrow-left"></i> No, Go Back</a>

</body>
</html>