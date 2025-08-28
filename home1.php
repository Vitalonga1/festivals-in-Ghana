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
    <title>Festivals in Ghana</title>
    <link rel="stylesheet" href="home1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <nav class="navbar" id="navbar">
        <h2 class="logo">
            <img src="Pictures/festival-logo-A28D3A42CD-seeklogo.com.png" alt="logo" class="logo-icon" width="50">
            <span class="glitch" data-text="in Ghana">in Ghana</span>
        </h2>
        <ul class="nav-links">
            <li><a href="#welcome" class="nav-item active">Home</a></li>
            <li><a href="#fest_1" class="nav-item">Gallary</a></li>
            <!-- <li><a href="#fest_2" class="nav-item">Oliver</a></li>
            <li><a href="#fest_3" class="nav-item">Nick</a></li> -->
            <li><a href="#contact" class="nav-item">Contact</a></li>
        </ul>

        <?php

        // Database connection
        $conn = new mysqli("localhost", "root", "", "group11");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Make sure user is logged in
        if (!isset($_SESSION['user']['id'])) {
            die("User not logged in.");
        }

        $userId = intval($_SESSION['user']['id']); // Sanitize user ID

        // Fetch profile picture from users table
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

    <!-- ====== Welcome Section ====== -->
    <section id="welcome" class="welcome">
        <div class="content">
            <?php
            // DB connection
            $conn = new mysqli("localhost", "root", "", "group11");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            if (!isset($_SESSION['user']['id'])) die("User not logged in");

            $userId = intval($_SESSION['user']['id']);

            // Fetch firstname
            $stmt = $conn->prepare("SELECT Fname FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($Fname);
            $stmt->fetch();
            $stmt->close();
            $conn->close();

            if (empty($Fname)) $Fname = "Guest";
            ?>

            <!-- Search Bar -->
            <div class="search">
                <div style="max-width: 600px; margin: 0px auto; position: relative;">
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="festivalSearch" placeholder="Search festival..."
                            style="flex: 1; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;">
                        <button id="searchButton" style="padding: 0 20px; background: #4caf50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Search
                        </button>
                    </div>
                    <div id="searchResults" style="margin-top: 10px; border: 1px solid #eee; border-radius: 4px; max-height: 300px; overflow-y: auto; display: none;"></div>
                    <div id="searchResultsInfo" style="margin-top: 5px; font-size: 14px; color: #666;"></div>
                </div>
            </div>

            <!-- Typewriter Greeting -->
            <h3 id="welcomeText"></h3>

            <p>Explore colorful and cultural festivals celebrated across Ghana. Immerse yourself in the rich traditions and vibrant celebrations that define Ghanaian culture.</p>

            <div class="slider-container">
                <div class="slider">
                    <img src="Pictures/marquee/three-women-colorful-traditional-dress-dancing-hogbetsotso-festival-ghana-women-colorful-traditional-dress-261372873.jpg" alt="">
                    <img src="Pictures/marquee/marquee-2.jpg" alt="">
                    <img src="Pictures/marquee/marquee 3.jpg" alt="">
                    <img src="Pictures/marquee/marquee 4.jpg" alt="">
                    <img src="Pictures/marquee/marquee 5.jpg" alt="">
                    <img src="Pictures/marquee/marquee-6.jpg" alt="">
                </div>
                <div class="dots">
                    <span class="dot active" onclick="changeSlide(0)"></span>
                    <span class="dot" onclick="changeSlide(1)"></span>
                    <span class="dot" onclick="changeSlide(2)"></span>
                    <span class="dot" onclick="changeSlide(3)"></span>
                    <span class="dot" onclick="changeSlide(4)"></span>
                    <span class="dot" onclick="changeSlide(5)"></span>

                </div>
                <style>
                    .slider-container {
                        width: 100%;
                        height: 60vh;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                        overflow: hidden;
                        transform: translateY(10px);

                        /* position: relative; */
                        /* display: flex; */

                    }

                    .slider img {
                        width: 50%;
                        /* Ensures each image takes up its full container */
                        height: 80vh;
                        object-fit: cover;
                        flex: 0 0 100%;
                        /* Ensures each image does not shrink or grow */
                    }

                    .slider {
                        display: flex;
                        width: 100%;
                        /* Ensure it’s exactly 100% * number of images */
                        transition: transform 0.6s ease-in-out;
                    }

                    .dots {
                        position: absolute;
                        bottom: 20px;
                        left: 60%;
                        transform: translateX(-50%);
                        display: flex;
                        gap: 10px;
                    }

                    .dot {
                        width: 12px;
                        height: 12px;
                        background: #ccc;
                        border-radius: 50%;
                        cursor: pointer;
                        transition: background 0.3s;
                    }

                    .dot.active {
                        background: #4caf50;
                    }
                </style>
            </div>
            <script>
                const slider = document.querySelector(".slider");
                const slides = document.querySelectorAll(".slider img");
                const dots = document.querySelectorAll(".dot");
                let currentIndex = 0;
                const totalSlides = slides.length;

                function moveSlide(index) {
                    if (index >= totalSlides) {
                        index = 0; // Reset to first image
                    } else if (index < 0) {
                        index = totalSlides - 1; // Go to last image
                    }

                    // Ensure consistent movement
                    slider.style.transition = "transform 0.6s ease-in-out";
                    slider.style.transform = `translateX(-${index * 100}%)`;

                    currentIndex = index;
                    updateDots();
                }

                function updateDots() {
                    dots.forEach((dot, i) => {
                        dot.classList.toggle("active", i === currentIndex);
                    });
                }

                // Auto slide every 3 seconds
                setInterval(() => {
                    moveSlide(currentIndex + 1);
                }, 3000);

                // Click event for dots
                dots.forEach((dot, i) => {
                    dot.addEventListener("click", () => moveSlide(i));
                });
            </script>
        </div>

        <style>
            .zoom-highlight {
                animation: zoomInOut 10s ease-in-out;
                transform-origin: center;
                border: 2px solid #4caf50 !important;
                border-radius: 8px !important;
                box-shadow: 0 0 15px rgba(76, 175, 80, 0.3) !important;
            }

            @keyframes zoomInOut {

                0%,
                20%,
                40%,
                60%,
                80%,
                100% {
                    transform: scale(1);
                }

                10%,
                30%,
                50%,
                70%,
                90% {
                    transform: scale(1.05);
                }
            }
        </style>

        <script>
            // ===== Typewriter Effect =====
            const userName = "<?php echo $Fname; ?>";
            const text = `Welcome ${userName} to festivals in Ghana.`;
            const speed = 100;
            let i = 0;

            function typeWriter() {
                const element = document.getElementById("welcomeText");
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                } else {
                    setTimeout(() => {
                        element.innerHTML = "";
                        i = 0;
                        typeWriter();
                    }, 2000);
                }
            }
            window.onload = typeWriter;

            // ===== Festival Search =====
            const festivalData = [{
                    name: "Aboakyer Festival",
                    containerClass: "a"
                },
                {
                    name: "Ngmayem Festival",
                    containerClass: "b"
                },
                {
                    name: "Damba Festival",
                    containerClass: "c"
                },
                {
                    name: "Homowo Festival",
                    containerClass: "f"
                },
                {
                    name: "Kundum Festival",
                    containerClass: "g"
                },
                {
                    name: "Fetu Afahye Festival",
                    containerClass: "h"
                },
                {
                    name: "Asafotu Fiam Festival",
                    containerClass: "d"
                },
                {
                    name: "Odambea Festival",
                    containerClass: "e"
                },
                {
                    name: "Hogbetsotso Festival",
                    containerClass: "j"
                }
            ];

            const searchInput = document.getElementById('festivalSearch');
            const resultsContainer = document.getElementById('searchResults');
            const searchButton = document.getElementById('searchButton');
            const searchResultsInfo = document.getElementById('searchResultsInfo');
            let currentZoomElement = null;
            let zoomTimeout = null;

            function displayResults(results, searchTerm = '') {
                if (results.length === 0) {
                    resultsContainer.innerHTML = '<div style="padding:10px;color:#666;">No festivals found</div>';
                    resultsContainer.style.display = 'block';
                    searchResultsInfo.textContent = '';
                    return;
                }
                resultsContainer.innerHTML = results.map(f => `
                <div class="result-item" 
                     data-festival="${f.name}" data-container="${f.containerClass}" 
                     style="padding:10px;border-bottom:1px solid #eee; cursor:pointer;">
                    ${highlightMatch(f.name, searchTerm)}
                </div>
            `).join('');
                resultsContainer.style.display = 'block';
                searchResultsInfo.innerHTML = `Found ${results.length} matching festival(s). <button id="resetSearch" style="background:none; border:none; color:#4caf50; cursor:pointer; text-decoration:underline;">Reset search</button>`;
                document.getElementById('resetSearch').addEventListener('click', () => {
                    searchInput.value = '';
                    resultsContainer.style.display = 'none';
                    searchResultsInfo.textContent = '';
                    resetHighlights();
                });
            }

            function highlightMatch(text, searchTerm) {
                if (!searchTerm) return text;
                const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
                return text.replace(regex, '<span style="background-color:yellow;">$1</span>');
            }

            function escapeRegExp(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            function selectFestival(name, containerClass) {
                resetHighlights();
                const el = document.querySelector(`.${containerClass}`);
                if (el) {
                    el.classList.add('zoom-highlight');
                    currentZoomElement = el;
                    zoomTimeout = setTimeout(() => {
                        el.classList.remove('zoom-highlight');
                    }, 10000);
                    setTimeout(() => {
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 100);
                }
                resultsContainer.style.display = 'none';
            }

            function resetHighlights() {
                if (zoomTimeout) clearTimeout(zoomTimeout);
                festivalData.forEach(f => {
                    const el = document.querySelector(`.${f.containerClass}`);
                    if (el) el.classList.remove('zoom-highlight');
                });
            }

            function performSearch() {
                const term = searchInput.value.toLowerCase();
                if (term.length === 0) {
                    resultsContainer.style.display = 'none';
                    searchResultsInfo.textContent = '';
                    resetHighlights();
                    return;
                }
                const matched = festivalData.filter(f => f.name.toLowerCase().includes(term));
                displayResults(matched, term);
            }

            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });
            searchButton.addEventListener('click', () => {
                clearTimeout(searchTimeout);
                performSearch();
            });
            searchInput.addEventListener('keypress', e => {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    performSearch();
                }
            });

            resultsContainer.addEventListener('click', e => {
                const item = e.target.closest('.result-item');
                if (item) selectFestival(item.dataset.festival, item.dataset.container);
            });

            document.addEventListener('click', e => {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target) && e.target !== searchButton) {
                    resultsContainer.style.display = 'none';
                }
            });
        </script>
    </section>



    <!-- ====== Main Festivals Section ====== -->
    <section id="fest_1" class="main">
        <h3>Explore</h3>

        <div class="fest-container">

            <div class="fest-row">
                <div class="fest-card a">
                    <img src="Pictures/Aboakyir-festival.jpeg" alt="">
                    <p>
                        <strong>Aboakyer (Deer Hunt) Festival:</strong>
                        <span class="hidden-text">This popular festival is celebrated on the first Saturday of May by the chiefs and people of Winneba...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card b">
                    <img src="Pictures/Ngmayem-festival.jpg" alt="">
                    <p>
                        <strong>Ngmayem Festival:</strong>
                        <span class="hidden-text">Annual traditional harvest and thanksgiving festival of the Krobo people in September...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card c">
                    <img src="Pictures/damba-festival.jpg" alt="">
                    <p>
                        <strong>Damba Festival:</strong>
                        <span class="hidden-text">Celebrated to mark the birth and naming of the Prophet Muhammad; glorification of the chieftaincy...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>
            </div>

            <div class="fest-row">
                <div class="fest-card f">
                    <img src="Pictures/homowo.jpg" alt="">
                    <p>
                        <strong>Homowo Festival:</strong>
                        <span class="hidden-text">Harvest festival celebrated by the Ga people, featuring a special dish of ground corn mixed with palm oil...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card g">
                    <img src="Pictures/Kundum-festival.jpg" alt="">
                    <p>
                        <strong>Kundum Festival:</strong>
                        <span class="hidden-text">Celebrated from August to November in Western Region; includes drumming, dancing, and ancestral prayers...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card h">
                    <img src="Pictures/Fetu-Afahye-festival.jpg" alt="">
                    <p>
                        <strong>Fetu Afahye Festival:</strong>
                        <span class="hidden-text">Celebrated in Cape Coast, featuring durbars of chiefs and processions of Asafo Companies...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>
            </div>

            <div class="fest-row">
                <div class="fest-card d">
                    <img src="Pictures/Asafotu-Fiam-festival.jpg" alt="">
                    <p>
                        <strong>Asafotu-Fiam Festival:</strong>
                        <span class="hidden-text">Annual warrior’s festival in Ada commemorating victories in battle...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card e">
                    <img src="Pictures/odambea-festival.jpg" alt="">
                    <p>
                        <strong>Odambea Festival:</strong>
                        <span class="hidden-text">Celebrated by Nkusukum chiefs; commemorates migration and ancient lifestyles...</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card j">
                    <img src="Pictures/Hogbetsotso-Festival.jpg" alt="">
                    <p>
                        <strong>Hogbetsotso Festival:</strong>
                        <span class="hidden-text">“Festival of the Exodus” by the Anlo Ewes, including ceremonies and peace-making rituals...</span>
                        <button class="read-more-btn">...Read More </button>
                    </p>
                </div>
            </div>

            <div class="video-row">
                <video autoplay muted loop playsinline width="580" height="330" src="Pictures/Untitled video - Made with Clipchamp.mp4"></video>
                <video autoplay muted loop playsinline width="580" height="330" src="Pictures/VID-20250821-WA0007.mp4"></video>
                <video autoplay muted loop playsinline width="580" height="330" src="Pictures/VID-20250821-WA0008.mp4"></video>
            </div>

        </div>
        <script>
            // Read More button toggle
            document.querySelectorAll('.read-more-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const hiddenText = this.previousElementSibling;
                    const arrow = this.querySelector('i');
                    if (hiddenText.style.display === 'inline') {
                        hiddenText.style.display = 'none';
                        this.innerHTML = '...Read More <i class="fas fa-arrow-right"></i>';
                        arrow.style.transform = 'rotate(0deg)';
                    } else {
                        hiddenText.style.display = 'inline';
                        this.innerHTML = 'Read Less <i class="fas fa-arrow-left"></i>';
                        arrow.style.transform = 'rotate(90deg)';
                    }
                });
            });
        </script>
    </section>

    <!-- <section id="fest_2" class="main">
        <h3>Ngmayem Festival</h3>
        <p>The Ngmayem Festival is celebrated by the Krobo people to give thanks for the harvest. The name means "bringing forth the fruit of the earth"</p>
        <p>This festival is marked by processions, cultural displays, and thanksgiving ceremonies. It's a time when families reunite and the community comes together to celebrate their heritage.</p>
        <button class="read-more-btn">Read more <i class="fas fa-arrow-right"></i></button>
    </section>

    <section id="fest_3" class="main">
        <h3>Damba Festival</h3>
        <p>The Damba festival is celebrated by the Dagombas in the Northern Region of Ghana. It is divided into three parts: Somo Damba, Naa Damba, and Belkulsi.</p>
        <p>This festival commemorates the birth and naming of Prophet Muhammad. It features horse riding, drumming, and dancing, with participants wearing beautiful traditional smocks.</p>
        <button class="read-more-btn">Read more <i class="fas fa-arrow-right"></i></button>
    </section> -->

    <section id="contact">
        <h3>Contact Us</h3>
        <p>Have questions about Ghanaian festivals or want to share your experiences? We'd love to hear from you!</p>
        <p>Email: info@festivalsinghana.com</p>
        <p>Phone: +233 XX XXX XXXX</p>
    </section>

    <button id="feedbackBtn" title="Feedback">
        <i class="fas fa-comment-alt"></i>
    </button>

    <!-- Feedback Modal -->
    <div class="feedback-modal" id="feedbackModal">
        <div class="feedback-content">
            <span class="close" id="closeFeedback">&times;</span>
            <h3>Send us your feedback</h3>
            <p>We value your feedback to improve our services. Please share your thoughts or suggestions.</p>
            <!-- <textarea id="feedbackText" placeholder="Type your message..." rows="5"></textarea>
            <button id="sendFeedback">Send</button> -->


            <!-- Feedback Form -->
            <form method="POST" action="feedMesg.php">
                <textarea name="feedback" id="feedbackText" placeholder="Type your message..." rows="5" required></textarea>
                <button type="submit" name="submitFeedback">Send</button>
            </form>

            <?php
            // Display success/error message from feedMesg.php (if redirected back)
            if (isset($_SESSION['feedback_msg'])) {
                echo '<div style="margin-top:10px; color:green;">' . $_SESSION['feedback_msg'] . '</div>';
                unset($_SESSION['feedback_msg']);
            }
            if (isset($_SESSION['feedback_error'])) {
                echo '<div style="margin-top:10px; color:red;">' . $_SESSION['feedback_error'] . '</div>';
                unset($_SESSION['feedback_error']);
            }
            ?>
        </div>
    </div>

    <script>
        // Open modal
        const feedbackBtn = document.getElementById('feedbackBtn');
        const feedbackModal = document.getElementById('feedbackModal');
        const closeFeedback = document.getElementById('closeFeedback');
        const sendFeedback = document.getElementById('sendFeedback');
        const feedbackText = document.getElementById('feedbackText');
        const feedbackMsg = document.getElementById('feedbackMsg');

        feedbackBtn.addEventListener('click', () => {
            feedbackModal.style.display = 'flex';
        });

        closeFeedback.addEventListener('click', () => {
            feedbackModal.style.display = 'none';
        });

        // Close when clicking outside modal
        window.addEventListener('click', (e) => {
            if (e.target === feedbackModal) feedbackModal.style.display = 'none';
        });
    </script>

    <script>
        // ===== Sticky Dropdown =====
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        // Close dropdown when clicking outside
        window.onclick = function(e) {
            if (!e.target.matches('.upload') && !e.target.matches('.upload *')) {
                document.getElementById("profileDropdown").classList.remove("show");
            }
        };

        // ===== Scroll Spy for Active Section Highlight =====
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-links .nav-item');
        const navbar = document.getElementById('navbar');

        // Function to update active nav link
        function updateActiveNavLink() {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        }

        // Add scroll event listener
        window.addEventListener('scroll', () => {
            // Add scrolled class to navbar for style changes
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Update active nav link
            updateActiveNavLink();
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav-links a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);

                window.scrollTo({
                    top: targetSection.offsetTop - 80,
                    behavior: 'smooth'
                });
            });
        });

        // Initialize page with correct active link
        window.addEventListener('load', updateActiveNavLink);
    </script>

</body>

</html>