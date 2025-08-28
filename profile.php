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
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get user data from database
    $userId = $_SESSION['user']['id']; // Assuming user ID is stored in session
    $stmt = $pdo->prepare("SELECT fname, lname, email, dob, country FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .profile-container {
            max-width: 600px;
            height: 140vh;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        }

        h1 {
            color: #333;
            text-align: center;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 250px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .btn-change {
            background-color: #4560b6;
            color: white;
        }

        .btn-home {
            background-color: #4caf50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        /* Upload styles */
        .upload {
            position: relative;
            width: 40px;
            height: 10px;
        }

        .round {
            position: absolute;
            top: 265px;
            left: 285px;
            background: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #4CAF50;
            cursor: pointer;
        }

        .fa-camera {
            color: #4caf50;
            font-size: 20px;
        }

        .profile-img {
            width: 200px;
            height: 30vh;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            vertical-align: middle;
            margin-top: 90px;
            border: 2px solid #fff;
            margin-left: 135px;
        }
    </style>
</head>

<body>
    <div class="profile-container">

        <?php
        // DB connection
        $conn = new mysqli("localhost", "root", "", "group11");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $image = 'default.jpg'; // fallback image

        if (isset($_SESSION['user']['id'])) {
            $stmt = $conn->prepare("SELECT file__name 
                            FROM upload_images 
                            WHERE user_id = ? 
                            ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("i", $_SESSION['user']['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                if (!empty($row['file__name'])) {
                    $image = $row['file__name'];
                }
            }
            $stmt->close();
        }
        $conn->close();
        ?>


        <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="upload">
                <!-- Profile Image with Fallback -->
                <img id="profileImage"
                    src="uploads/<?php echo htmlspecialchars($image); ?>"
                    onerror="this.src='uploads/default.jpg'"
                    alt="Profile Image"
                    class="profile-img"
                    onclick="toggleDropdown()">


                <!-- Camera Icon for Upload -->
                <div class="round" onclick="document.getElementById('imageUpload').click()">
                    <i class="fa fa-camera"></i>
                    <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;"
                        onchange="document.getElementById('uploadForm').submit()">
                </div>
            </div>
            <?php if (isset($_GET['status'])): ?>
                <div class="upload-status" style="margin-top: 10px; padding: 5px; 
            background-color: <?php echo $_GET['status'] === 'success' ? '#4CAF50' : '#f44336'; ?>; 
            color: white; border-radius: 4px;">
                    <?php
                    echo htmlspecialchars($_GET['message'] ??
                        ($_GET['status'] === 'success' ? 'Upload successful!' : 'Upload failed'));
                    ?>
                </div>
            <?php endif; ?>

        </form>


        <h1>User Profile</h1>

        <table>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($user['fname'] . ' ' . htmlspecialchars($user['lname'])); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Date Of Birth</th>
                <td><?php echo htmlspecialchars($user['dob']); ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo htmlspecialchars($user['country']); ?></td>
            </tr>


        </table>


        <?php


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

        <!-- <a href="profile.php" class="back-link">Back to Profile</a> -->

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


        <div class="action-buttons">
            <!-- <a href="change_password.php" class="btn btn-change">
                <i class="fas fa-key"></i> Change Password
            </a> -->
            <a href="Home.php" class="btn btn-change">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="delete_account.php" class="btn btn-delete">
                <i class="fas fa-trash-alt"></i> Delete Account
            </a>
        </div>
    </div>
</body>

</html>