<?php

session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<?php

// Include the database connection file
require_once 'connect.php';

// Initialize variables
$success = false;
$message = '';
$errors = [];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and trim input data
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_STRING);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty(trim($feedback))) {
        $errors['feedback'] = "Feedback message is required.";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        try {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO feedback_table (email, feedback, sent_at) VALUES (:email, :feedback, :sent_at)");
            
            // Execute the statement with the provided data
            $stmt->execute([
                ':email' => $email,
                ':feedback' => $feedback,
                ':sent_at' => date('Y-m-d H:i:s')
            ]);
            
            $success = true;
            $message = "Thank you for your feedback!";
            
        } catch (PDOException $e) {
            $message = "Error submitting feedback: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="Feedback.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<nav class="navbar">
        <h2 class="logo">
            <img src="Pictures/festival-logo-A28D3A42CD-seeklogo.com.png" alt="logo" class="logo-icon">
            <span class="h2-nav">in Ghana</span>
        </h2>
        <ul class="nav-links">
            <li><a href="Home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="Lord.php">Lord</a></li>
            <li><a href="Oliver.php">Oliver</a></li>
            <li><a href="Nick.php"> Nick</a></li>
            <span class="home_1">
                <li>Contact</li>
            </span>
        </ul>

        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "upload_image");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch the latest uploaded image
        $result = $conn->query("SELECT filename FROM images ORDER BY id DESC LIMIT 1");
        $image = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['filename'] : 'default.jpg';
        $conn->close();
        ?>

        <div class="profile-dropdown">
            <div class="upload" onclick="toggleDropdown()">
                <img id="profileImage" src="uploads/<?php echo $image; ?>" width="40" height="40" alt="Profile">
                <i class="fas fa-caret-down dropdown-arrow"></i>
            </div>
            <div id="profileDropdown" class="dropdown-content">
                <a href="profile.php"><i class="fas fa-user"></i> Edit Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #131313;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .logo-icon {
            height: 40px;
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #4caf50;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .upload {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 5px;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        .upload:hover {
            background-color: #f5f5f5;
        }

        #profileImage {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4caf50;
        }

        .dropdown-arrow {
            margin-left: 8px;
            color: #666;
            transition: transform 0.3s;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px 0;
            z-index: 1001;
            margin-top: 10px;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f5f5f5;
            color: #4caf50;
        }

        .dropdown-content i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .show {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rotate {
            transform: rotate(180deg);
        }
    </style>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            const arrow = document.querySelector('.dropdown-arrow');

            dropdown.classList.toggle("show");
            arrow.classList.toggle("rotate");
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(event) {
            const dropdown = document.getElementById("profileDropdown");
            const profileElement = document.querySelector('.profile-dropdown');

            if (!profileElement.contains(event.target)) {
                dropdown.classList.remove("show");
                document.querySelector('.dropdown-arrow').classList.remove("rotate");
            }
        });
    </script>
    <div class="main_container">
        <div class="feedback-container">
            <img src="Pictures/feedback.png" alt="Feedback Icon" class="feedback-icon">
            <h2 class="h2">We're Listening!</h2>
            <p>Tell us what you love about our page or things that we can improve</p>
            <p class="support-text">Please go to <a href="#">Customer Service</a> if you need support</p>

            <form method="POST" action="" id="feedbackForm">
                <!-- <div class="form-group">
                    <input type="text" name="name" id="name" placeholder="Username" required>
                </div> -->

                <div class="form-group">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <?php
                    if (isset($errors['email'])) {
                        echo '<div class="error">
                            <p>' . $errors['email'] . '</p>
                        </div>';
                        unset($errors['email']);
                    }
                    ?>
                </div>

                <div>
                    <textarea id="feedback" name="feedback" class="feedback-textarea" placeholder="Please share your feedback here" maxlength="500" required></textarea>
                    <?php
                    if (isset($errors['feedback'])) {
                        echo '<div class="error">
                            <p>' . $errors['feedback'] . '</p>
                        </div>';
                        unset($errors['feedback']);
                    }
                    ?>
                </div>

                <div class="char-count"><span id="charCount">0</span>/500</div>

                <button type="submit" name="signup" id="sendBtn" class="btn" value="" disabled>
                    <!-- <i id="gearIcon" class="fa fa-gear" style="display: none;"></i> -->
                    <i id="spinnerIcon" class="fa fa-spinner fa-spin" style="display: none;"></i>
                    Send
                </button>
            </form>
        </div>
    </div>

    <!-- Pop-up Notification -->
    <!-- <div id="popup" class="popup">
        <p>Feedback sent successfully!</p>
    </div> -->
    <script>
        // Display popup message after page loads
        window.onload = function() {
            <?php if (!empty($message)): ?>
                alert("<?php echo $message; ?>");
                <?php if ($success): ?>
                    window.location.href = "Contact.php";
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                let errorMsg = "Please fix the following errors:\n";
                <?php foreach ($errors as $error): ?>
                    errorMsg += "• <?php echo $error; ?>\n";
                <?php endforeach; ?>
                alert(errorMsg);
            <?php endif; ?>
        };
    </script>
    <script src="Feedback.js"></script>
    <hr>
    <div class="footnote">
        <h3 class="sn">On social networks</h3>
        <ul>
            <li><a href="#"><img src="Nick-pic/github.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/linkedIn.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/whatsapp.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/discord.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/facebook.png" alt="" class="hidden" width="80px" height="70px"></a></li>
        </ul>
        <ul>
            <li><a href="#"><img src="Nick-pic/GithubQR.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/LinkedInQR.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/WhatsApp Image 2025-02-28 at 04.36.41_dce9dfbc.jpg" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/DiscordQR.png" alt="" class="hidden" width="80px" height="70px"></a></li>
            <li><a href="#"><img src="Nick-pic/FacebookQR.png" alt="" class="hidden" width="80px" height="70px"></a></li>
        </ul>
        <hr>

        <footer>
            Copyright &copy; 2025 | Powered by Group Eleven(11)
        </footer>
</body>

</html>
<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>