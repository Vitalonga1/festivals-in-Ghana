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
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Get user ID from session - adjust based on how you store user data
    $user_id = $_SESSION['user']['id'] ?? null;

    if (!$user_id) {
        $message = "User session error. Please log in again.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match!";
    } else {
        try {
            // Get current password hash
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $message = "User not found!";
            } elseif (password_verify($current_password, $row['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $update_stmt->bindParam(':password', $hashed_password);
                $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                if ($update_stmt->execute()) {
                    echo "<script>
                            alert('Password successfully updated!');
                            window.location.href = 'index.php';
                          </script>";
                    exit();
                } else {
                    $message = "Error updating password.";
                }
            } else {
                $message = "Incorrect current password!";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-group {
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 8px 35px 8px 8px;
            box-sizing: border-box;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 32px;
            cursor: pointer;
            color: #666;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Change Password</h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
        </div>

        <div class="input-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
        </div>

        <div class="input-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
        </div>

        <button type="submit">Update Password</button>
    </form>

    <a href="profile.php" class="back-link">Back to Profile</a>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>

</html>