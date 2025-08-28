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

<?php if (isset($_SESSION['feedback_msg']) || isset($_SESSION['feedback_error'])): ?>
    <div class="feedback-notice <?php echo isset($_SESSION['feedback_msg']) ? 'success' : 'error'; ?>" id="feedbackNotice">
        <span class="message"><?php echo $_SESSION['feedback_msg'] ?? $_SESSION['feedback_error']; ?></span>
        <div class="progress-bar"></div>
    </div>
<?php
    unset($_SESSION['feedback_msg'], $_SESSION['feedback_error']);
endif; ?>

<style>
    .feedback-notice {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 280px;
        max-width: 400px;
        padding: 15px 20px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        color: #fff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        animation: slideIn 0.4s ease-out;
    }

    .feedback-notice.success {
        background: linear-gradient(135deg, #28a745, #218838);
    }

    .feedback-notice.error {
        background: linear-gradient(135deg, #dc3545, #b52a37);
    }

    .feedback-notice .message {
        flex: 1;
        margin-right: 10px;
    }

    .feedback-notice .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        background: rgba(255, 255, 255, 0.7);
        animation: drain 5s linear forwards;
        border-radius: 0 0 10px 10px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes drain {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }
</style>

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
                },
                {
                    name: "Odwira Festival",
                    containerClass: "k"
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

    <section id="fest_1" class="main">
        <h3>Explore</h3>

        <div class="fest-container">

            <div class="fest-row">
                <div class="fest-card a">
                    <img src="Pictures/Aboakyir-festival.jpeg" alt="">
                    <p>
                        <strong>Aboakyer (Deer Hunt) Festival:</strong>
                        <span class="hidden-text">“Aboakyer” literally, means “game hunting”. This popular festival is celebrated on the first Saturday of May by the chiefs and people of Winneba.
                            The festival begins with a competitive hunt between 2 traditional warrior groups in a nearby game reserve, where each tries to catch an antelope live.
                            It is an adventurous event to test the strength, bravery, determination, and intuition of the 2 rival groups.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card b">
                    <img src="Pictures/Ngmayem-festival.jpg" alt="">
                    <p>
                        <strong>Ngmayem Festival:</strong>
                        <span class="hidden-text">This is the annual traditional harvest and thanksgiving festival of the Krobo people.
                            It is celebrated in September by the people of Manya Krobo in the towns of Odumase-Krobo in the Eastern Region.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card c">
                    <img src="Pictures/damba-festival.jpg" alt="">
                    <p>
                        <strong>Damba Festival:</strong>
                        <span class="hidden-text">Damba is celebrated to mark the birth and naming of the Holy prophet, Muhammad, but the main purpose of the celebration is a glorification of the chieftaincy,
                            not specific Islamic motifs.The festival is categorized into three sessions; the Somo Damba, the Naa Damba and the Belkulsi.
                            The Damba festival is celebrated by the chiefs and people of the Northern, Savanna, North East and Upper West Regions of Ghana.
                            This festival is celebrated in the Dagomba lunar month of Damba, corresponding to the third month of the Islamic calendar, Rabia al-Awwal.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>
            </div>

            <div class="fest-row">
                <div class="fest-card f">
                    <img src="Pictures/homowo.jpg" alt="">
                    <p>
                        <strong>Homowo Festival:</strong>
                        <span class="hidden-text">This is a harvest festival celebrated by the people of the Ga Traditional Area, in the Greater Accra Region.
                            It originated from a period of great famine which was eventually followed by a bumper harvest in grain and fish. Thus, the word “Homowo”, literally means “hooting at hunger”.
                            The main highlight of this month-long festival is the special dish prepared from ground corn, steamed and mixed with palm oil and eaten with palmnut soup.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card g">
                    <img src="Pictures/Kundum-festival.jpg" alt="">
                    <p>
                        <strong>Kundum Festival:</strong>
                        <span class="hidden-text">Kundum is celebrated from August to November by the Western Region’s coastal tribes, the Ahantas and Nzemas.
                            Beginning in August, the festival moves west from Takoradi to town after town at weekly intervals.
                            Rituals include purification of the stools and prayers to the ancestors for a good harvest. Traditional drumming and dancing feature prominently.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card h">
                    <img src="Pictures/Fetu-Afahye-festival.jpg" alt="">
                    <p>
                        <strong>Fetu Afahye Festival:</strong>
                        <span class="hidden-text">It is celebrated annually on the first Saturday of September by communities in the Cape Coast Traditional Area (Fetu).
                            It is characterized by a durbar of chiefs and processions of “Asafo Companies” (traditional warrior groups) and numerous social organisations.
                            Every member of the group is adorned in rich and colourful clothes, thus creating the grandeur of this festival which literally means “adorning of new clothes”.
                            A procession of the “7 Asafo Companies” in their unique costumes depicts a fusion of the “Fante” and European cultures, (typically, Portuguese, Dutch, Swedish and British),
                            which have been sustained over many centuries. Customary rites include the slaughter of a cow to the 77 Deities in the area to obtain their blessings.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>
            </div>

            <div class="fest-row">
                <div class="fest-card d">
                    <img src="Pictures/Asafotu-Fiam-festival.jpg" alt="">
                    <p>
                        <strong>Asafotu-Fiam Festival:</strong>
                        <span class="hidden-text">“Asafotufiam” is an annual warrior’s festival celebrated by the people of Ada, in the Greater Accra Region from the last Thursday of July to the first
                            weekend of August. <br> It commemorates the victories of the warriors in battle and those who fell on the battlefield.
                            To re-enact these historic events, the “warrior” dresses in traditional battle dress and stage a mock battle. This is also a time when the young men are introduced to warfare.
                            The festival also ushers in the harvest cycle, for this special customs and ceremonies are performed.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card e">
                    <img src="Pictures/odambea-festival.jpg" alt="">
                    <p>
                        <strong>Odambea Festival:</strong>
                        <span class="hidden-text">“Odambea” is celebrated on the last Saturday of August by the “Nkusukum” chiefs and people of the Saltpond Traditional Area.
                            This event commemorates the migration of the “Nkusukum” people centuries ago from Techiman (500km away) to their present settlement. “Odambea” means “fortified link”,
                            a name resulting from the role played by the “Nkusukum” people in keeping the migrant groups in touch with each other following their exodus from Techiman.
                            A special feature of the festival is the re-enactment of the ancient life styles of the people, which will provide you with a unique opportunity to learn more about how they migrated.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>

                <div class="fest-card j">
                    <img src="Pictures/Hogbetsotso-Festival.jpg" alt="">
                    <p>
                        <strong>Hogbetsotso Festival:</strong>
                        <span class="hidden-text">The “Anlo Ewes”, an ethnic group on the eastern cost (Volta Region) of Ghana, are believed to have settled in Notsie in
                            Togo when they first migrated from Southern Sudan.
                            Legend has it that they escaped from the tyrannical ruler of Notsie, Ago-Koli, by walking backwards. In order to commemorate the exodus and the bravery of their traditional rulers who led them on the journey, the people created this annual “Festival of the Exodus”. There are many ceremonies associated with the festival, including a peace-making period where all outstanding problems are supposed to be resolved.</span>
                        <button class="read-more-btn">...Read More </button>
                    </p>
                </div>
            </div>

            <div class="video-row">
                <div class="fest-card k">
                    <img src="Pictures/Odwira-Festival.jpg" alt="">
                    <p>
                        <strong>Odwira Festival:</strong>
                        <span class="hidden-text"> This festival is celebrated in most Akwapim towns during the months of September and October, with the most colourful festivities taking place at Akropong, Amanokrom and Aburi, in the Eastern Region.
                            During “Odwira”, the Chiefs sit in state and receive homage from the people. The ceremonies include purification of the stools and performance of traditional rites.
                            Libations are poured to the gods for prosperity and the general well-being of the people during the ensuing year. Drumming and dancing accompany the celebration.</span>
                        <button class="read-more-btn">...Read More</button>
                    </p>
                </div>
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
        <div class="contact-container">
            <!-- Header -->
            <div class="contact-header">
                <h1>Contact Us</h1>
                <p>
                    Have questions about Ghanaian festivals or want to share your
                    experiences? We'd love to hear from you!
                </p>
            </div>

            <!-- Content -->
            <div class="contact-content">
                <!-- Left: Contact Info -->
                <div class="contact-info">
                    <h2>Get In Touch</h2>

                    <div class="contact-detail">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <a href="mailto:nicholastawiah801@gmail.com">nicholastawiah801@gmail.com</a>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <a href="tel:+233594141333">+233 59 414 1333</a>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Address</h3>
                            <p>123 Festival Road, Accra, Ghana</p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-section">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Right: Map -->
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63557.30417285802!2d-0.7029391891479178!3d5.366301984870348!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfde34e8ffe3e31b%3A0x4a1a58fce2485ac9!2sWinneba!5e0!3m2!1sen!2sgh!4v1741446436838!5m2!1sen!2sgh" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Section Background */
        #contact {
            padding: 70px 20px;
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
        }

        .contact-container {
            max-width: 1100px;
            margin: auto;
            text-align: center;
        }

        /* Header */
        .contact-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #222;
        }

        .contact-header p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 50px;
        }

        /* Content Layout */
        .contact-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        @media (min-width: 768px) {
            .contact-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }
        }

        /* Contact Info */
        .contact-info {
            flex: 1;
            text-align: left;
            max-width: 500px;
        }

        .contact-info h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .contact-detail {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .contact-detail i {
            font-size: 1.8rem;
            color: #e63946;
            min-width: 40px;
            text-align: center;
        }

        .contact-detail h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #222;
        }

        .contact-detail a,
        .contact-detail p {
            font-size: 0.95rem;
            color: #555;
            text-decoration: none;
        }

        /* Social Icons */
        .social-section {
            margin-top: 30px;
            text-align: center;
        }

        .social-section h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            color: #444;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icons a {
            font-size: 1.5rem;
            color: #333;
            transition: 0.3s ease;
        }

        .social-icons a:hover {
            color: #e63946;
        }

        /* Map */
        .map-container {
            flex: 1;
            max-width: 500px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        footer {
            padding: 20px 10px;
            text-align: center;
            /* centers text */
            display: flex;
            /* centers flex items */
            justify-content: center;
            /* horizontally center */
            align-items: center;
            /* vertically center */
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        footer p {
            margin: 0;
        }
    </style>


    <footer>
        <p>&copy; 2025 Festivals in Ghana. All rights reserved.</p>
    </footer>

    <?php
    require_once 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
        $user_id = $_SESSION['user']['id'] ?? null;
        $email = $_SESSION['user']['email'] ?? null;
        $feedback = trim($_POST['feedback']);

        if (!$user_id || !$email) {
            $_SESSION['feedback_error'] = "You must be logged in to submit feedback.";
        } elseif (empty($feedback)) {
            $_SESSION['feedback_error'] = "Feedback cannot be empty.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO feedback_table (user_id, email, feedback, sent_at) VALUES (:user_id, :email, :feedback, :sent_at)");
                $stmt->execute([
                    ':user_id' => $user_id,
                    ':email' => $email,
                    ':feedback' => $feedback,
                    ':sent_at' => date('Y-m-d H:i:s')
                ]);
                $_SESSION['feedback_msg'] = "Feedback submitted successfully!";
            } catch (PDOException $e) {
                $_SESSION['feedback_error'] = "Error submitting feedback. Try again later.";
                error_log($e->getMessage());
            }
        }
        exit;
    }
    ?>

    <button id="feedbackBtn" title="Feedback">
        <i class="fas fa-comment-alt"></i>
    </button>

    <div class="feedback-modal" id="feedbackModal">
        <div class="feedback-content">
            <span class="close" id="closeFeedback">&times;</span>
            <h3>Send us your feedback</h3>
            <p>Your feedback helps us improve your experience. Please share your thoughts!</p>
            <form id="feedbackForm" method="POST">
                <textarea name="feedback" placeholder="Type your message..." rows="5" required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
    <script>
        document.querySelector("#feedbackForm").addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("submit_feedback.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    // Create popup dynamically
                    let notice = document.createElement("div");
                    notice.className = "feedback-notice " + (data.status === "success" ? "success" : "error");
                    notice.innerHTML = `
            <span class="message">${data.msg}</span>
            <div class="progress-bar"></div>`;
                    document.body.appendChild(notice);

                    // Animate & auto-remove
                    setTimeout(() => {
                        notice.style.opacity = "0";
                        setTimeout(() => notice.remove(), 600);
                    }, 4000);
                })
                .catch(err => {
                    console.error(err);
                });
        });
    </script>

    <script>
        const feedbackBtn = document.getElementById('feedbackBtn');
        const feedbackModal = document.getElementById('feedbackModal');
        const closeFeedback = document.getElementById('closeFeedback');

        feedbackBtn.addEventListener('click', () => feedbackModal.style.display = 'flex');
        closeFeedback.addEventListener('click', () => feedbackModal.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === feedbackModal) feedbackModal.style.display = 'none';
        });
        document.addEventListener("DOMContentLoaded", function() {
            const notice = document.getElementById("feedbackNotice");
            if (notice) {
                setTimeout(() => {
                    notice.style.transition = "opacity 0.6s ease";
                    notice.style.opacity = "0";
                    setTimeout(() => notice.remove(), 600);
                }, 5000); // same duration as progress bar
            }
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