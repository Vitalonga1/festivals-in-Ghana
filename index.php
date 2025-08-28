<?php
session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Signin</title>
    <link rel="stylesheet" href="index.css">
    <!-- <link rel="stylesheet" href="signin.css"> -->
    <!-- Latest Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="form-container">
        <div class="form" id="signin">
            <?php
            if (isset($errors['login'])) {
                echo '<div class="error-main">
                        <p>' . $errors['login'] . '</p>
                      </div>';
                unset($errors['login']);
            }
            ?>
            <form action="user-account-login.php" method="POST">
                <img src="Pictures/festival-logo-A28D3A42CD-seeklogo.com.png" alt="Signup Icon" class="signup-icon">
                <h1>SIGN IN</h1>

                <div class="input-group">
                    <div class="input_box">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                    <?php
                    if (isset($errors['email'])) {
                        echo '<div class="error">
                                <p>' . $errors['email'] . '</p>
                              </div>';
                    }
                    ?>
                </div>

                <div class="input-group">
                    <div class="input_box">
                        <input type="password" id="password" name="password" minlength="8" placeholder="Password" required>
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                    <?php
                    if (isset($errors['password'])) {
                        echo '<div class="error">
                                <p>' . $errors['password'] . '</p>
                              </div>';
                    }
                    ?>
                </div>

                <button type="submit" id="logBtn" class="btn" disabled name="signin">
                    <span class="btn-text">Sign In</span>
                    <i id="gearIcon" class="fa fa-gear" style="display: none;"></i>
                    <i id="spinnerIcon" class="fa fa-spinner" style="display: none;"></i>
                </button>

                <div class="form-footer">
                    <a href="#" class="forgot-password">Forgotten password?</a>
                    <div class="or-divider">
                        <span class="or-line"></span>
                        <span class="or-text">OR</span>
                        <span class="or-line"></span>
                    </div>
                    <div class="links">
                        <p>Don't have an account yet?</p>
                        <a href="register.php" class="register-link">Register!</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const logBtn = document.getElementById("logBtn");

        function validateInputs() {
            if (emailInput.value.trim() && passwordInput.value.trim()) {
                logBtn.classList.add("active");
                logBtn.removeAttribute("disabled");
            } else {
                logBtn.classList.remove("active");
                logBtn.setAttribute("disabled", true);
            }
        }

        emailInput.addEventListener("input", validateInputs);
        passwordInput.addEventListener("input", validateInputs);

        document.getElementById('logBtn').addEventListener('click', function() {
            const gearIcon = document.getElementById('gearIcon');
            const spinnerIcon = document.getElementById('spinnerIcon');
            const btnText = document.querySelector('.btn-text');
            
            btnText.style.visibility = 'hidden';
            gearIcon.style.display = 'none';
            spinnerIcon.style.display = 'inline-block';
        });
    </script>
</body>

</html>
<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>