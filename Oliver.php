<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oliver</title>
    <link rel="stylesheet" href="Oliver.css">
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
            <li><a href="Lord.php">Opoku</a></li>
            <span class="home_1">
                <li>Oliver</li>
            </span>
            <li><a href="Nick.php"> Nick</a></li>
            <li><a href="Contact.php">Contact</a></li>
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

    <section class="home-content">
        <div class="content">
            <h1>Homowo festival</h1>
            <p>Homowo is a festival celebrated by the Ga people of Ghana in the Greater Accra Region. <br> <span class="hidden-text">
                    The festival starts at the end of April into May with the planting of crops (mainly millet) before the rainy season starts. <br>
                    The Ga people celebrate Homowo in the remembrance of famine that once happened in their history in precolonial Ghana. <br>
                    The Ga Homowo or Harvest Custom is an annual tradition among the Accra people, <br>with its origin tied to the Native Calendar and the Damte Dsanwe people of the Asere Quarter. <br>
                    Asere is a sub-division of the Ga Division in the Accra District of the Gold Coast Colony. </span>
                <button class="read-more-btn">Read More</button>
            </p>

            <h1>The Rites performed</h1>
            <p>Rites Shaayo Laitso Kee
                This rite involves housewives presenting logs to mothers-in-law. <br>This act marks the cordial relationship between a daughter-in-law and mother-in-law. <span class="hidden-text">The logs are used to make bonfires for the souls of dead relatives that are said to have arrived during Soobii. <br>This rite is also exchanged between sons-in-law and fathers-in-law. <br> The logs are used to make bonfires for the souls of dead relatives that are said to have arrived during Soobii.
                    Akpade Rite<br>
                    This rite involves plastering two side doors with red clay (Akpade) on the Friday of the Twins Yam Festival. <br>This act is carried out by the elderly women of families, <br>however the elderly men of the families fire musket bullets to expel evil spirits on the same day.
                    Libation<br>
                    The head of the family traditionally sprinkles kpoikpoi (a process called "Nishwamo") and pours drinks to the ground to honor ancestors <br>following the preparation of Homowo food on Saturday.
                    Prayer During Libation
                    Noowala Noowala (Long life Long Life)<br>
                    Afi naa akpe wo (May the new year bring us together)
                    Gbii kpaanyo anina wo (May we live to see the eighth day) <br>
                    Woye Gbo ni woye Gboenaa (May we eat the fruits of Gbo and that of Gboenaa) <br>
                    Wofee moomo (May we live long)
                    Alonte din ko aka-fo woten (May no black cat (ill omen) come between us) <br>
                    Wosee afi bene wotrashi neke nonu noon (May sit like this the next year) <br>
                    Tswa Tswa tswa Omanye aba (Hai! Hail! Hail! May peace be) </span>
                <button class="read-more-btn"><b>...Read more</b></button>
            </p>
        </div>
    </section>

    <h1 class="gal">Gallery</h1>
    <div class="video">
        <video autoplay controls muted width="500" height="400"
            src="Oliv/The Homowo Festival - ( About , Origin And The Activities).mp4" type="video/mp4"></video>

        <video autoplay controls muted width="500" height="400" src="Oliv/WhatsApp Video 2025-03-04 at 10.04.24_761cc8e9.mp4" type="video/mp4" class="video_2"></video>
    </div>

    <div class="fest_2">
        <div class="schnapp">
            <img src="Oliv/WhatsApp Image 2025-03-04 at 10.04.44_9c8a6320.jpg" alt="" width="400px" height="350px">
            <l></l>
        </div>

        <div class="schnapps">
            <img src="Oliv/WhatsApp Image 2025-03-04 at 10.04.43_9733132a.jpg" alt="" width="400px" height="350px">
            <l></l>
        </div>

        <div class="schnapps">
            <img src="Oliv/WhatsApp Image 2025-03-04 at 10.04.43_1e4816b7.jpg" alt="" width="400px" height="350px">
            <l></l>
        </div>
    </div>

    <footer>
        Copyright &copy; 2025 | Powered by Group Eleven(11)
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".read-more-btn");

            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const hiddenText = this.previousElementSibling; // Get the hidden text before the button
                    if (hiddenText.style.display === "none" || hiddenText.style.display === "") {
                        hiddenText.style.display = "inline";
                        this.textContent = "Read Less"; // Change button text
                    } else {
                        hiddenText.style.display = "none";
                        this.textContent = "Read More";
                    }
                });
            });
        });
    </script>

</body>

</html>