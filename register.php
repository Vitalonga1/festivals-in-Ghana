<?php

session_start();
if (isset($_SESSION['errors'])) {
  $errors = $_SESSION['errors'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signup</title>
  <!-- <link rel="stylesheet" href="register.css"> -->
  <link rel="stylesheet" href="signup.css">
  <!-- Latest Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
  <div class="form" id="signup">
    <?php
    if (isset($errors['user_exist'])) {
      echo '<div class="error-main">
                    <p>' . $errors['user_exist'] . '</p>
                    </div>';
      unset($errors['user_exist']);
    }
    ?>
    <form method="POST" action="user-account.php" enctype="multipart/form-data">
      <img
        src="Pictures/festival-logo-A28D3A42CD-seeklogo.com.png"
        alt="Signup Icon"
        class="signup-icon" />
      <h1>REGISTER</h1>

      <div class="input_box">
        <input type="text" name="Fname" id="Fname" placeholder="Firstname" required>
        <i class="fas fa-user"></i>
        <?php
        if (isset($errors['name'])) {
          echo '<div class="error">
                <p>' . $errors['name'] . '</p>
              </div>';
        }
        ?>
      </div>

      <div class="input_box">
        <input type="text" name="Lname" id="Lname" placeholder="Lastname" required>
        <i class="fas fa-user"></i>
        <?php
        if (isset($errors['name'])) {
          echo '<div class="error">
                <p>' . $errors['name'] . '</p>
              </div>';
        }
        ?>
      </div>

      <div class="input_box">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <i class="fas fa-envelope"></i>
        <?php
        if (isset($errors['email'])) {
          echo '<div class="error">
                <p>' . $errors['email'] . '</p>
              </div>';
          unset($errors['email']);
        }
        ?>
      </div>

      <div class="input_box">
        <input type="password" name="password" id="password" placeholder="Password(minimum 8) eg.Passw0rd123" required>
        <i id="lock" class="fa fa-lock"></i>
        <?php
        if (isset($errors['password'])) {
          echo '<div class="error">
                <p>' . $errors['password'] . '</p>
              </div>';
          unset($errors['password']);
        }
        ?>
      </div>

      <div class="input_box">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        <i id="eye" class="fa fa-eye-slash"></i>
        <?php
        if (isset($errors['confirm_password'])) {
          echo '<div class="error">
                <p>' . $errors['confirm_password'] . '</p>
              </div>';
          unset($errors['confirm_password']);
        }
        ?>
      </div>

      <!-- Date of Birth Field -->
      <div class="input_box">
        <input type="date" name="dob" id="dob" placeholder="Date of Birth(yyyy/mm/dd)" required>
        <!-- <i class="fas fa-calendar-alt"></i> -->
        <?php
        if (isset($errors['dob'])) {
          echo '<div class="error">
                <p>' . $errors['dob'] . '</p>
              </div>';
          unset($errors['dob']);
        }
        ?>
        <style>
          #dob {
            color: #424242;
          }
        </style>
      </div>

      <!-- Gender Field -->
      <div class="input_box">
        <select name="gender" id="gender" required>
          <option value="" disabled selected>Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
        <i class="fas fa-venus-mars"></i>
        <?php
        if (isset($errors['gender'])) {
          echo '<div class="error">
                <p>' . $errors['gender'] . '</p>
              </div>';
          unset($errors['gender']);
        }
        ?>
      </div>

      <!-- Country Field -->
      <div class="input_box">
        <select name="country" id="country" required>
          <option value="" disabled selected>Select your country</option>
          <!-- Add all countries here -->
          <option value="AF">Afghanistan</option>
          <option value="AL">Albania</option>
          <option value="DZ">Algeria</option>
          <option value="AS">American Samoa</option>
          <option value="AD">Andorra</option>
          <option value="AO">Angola</option>
          <option value="AI">Anguilla</option>
          <option value="AQ">Antarctica</option>
          <option value="AG">Antigua and Barbuda</option>
          <option value="AR">Argentina</option>
          <option value="AM">Armenia</option>
          <option value="AW">Aruba</option>
          <option value="AU">Australia</option>
          <option value="AT">Austria</option>
          <option value="AZ">Azerbaijan</option>
          <option value="BS">Bahamas</option>
          <option value="BH">Bahrain</option>
          <option value="BD">Bangladesh</option>
          <option value="BB">Barbados</option>
          <option value="BY">Belarus</option>
          <option value="BE">Belgium</option>
          <option value="BZ">Belize</option>
          <option value="BJ">Benin</option>
          <option value="BM">Bermuda</option>
          <option value="BT">Bhutan</option>
          <option value="BO">Bolivia</option>
          <option value="BA">Bosnia and Herzegovina</option>
          <option value="BW">Botswana</option>
          <option value="BR">Brazil</option>
          <option value="IO">British Indian Ocean Territory</option>
          <option value="BN">Brunei Darussalam</option>
          <option value="BG">Bulgaria</option>
          <option value="BF">Burkina Faso</option>
          <option value="BI">Burundi</option>
          <option value="KH">Cambodia</option>
          <option value="CM">Cameroon</option>
          <option value="CA">Canada</option>
          <option value="CV">Cape Verde</option>
          <option value="KY">Cayman Islands</option>
          <option value="CF">Central African Republic</option>
          <option value="TD">Chad</option>
          <option value="CL">Chile</option>
          <option value="CN">China</option>
          <option value="CX">Christmas Island</option>
          <option value="CC">Cocos (Keeling) Islands</option>
          <option value="CO">Colombia</option>
          <option value="KM">Comoros</option>
          <option value="CG">Congo</option>
          <option value="CD">Congo, the Democratic Republic of the</option>
          <option value="CK">Cook Islands</option>
          <option value="CR">Costa Rica</option>
          <option value="CI">Cote d'Ivoire</option>
          <option value="HR">Croatia (Hrvatska)</option>
          <option value="CU">Cuba</option>
          <option value="CY">Cyprus</option>
          <option value="CZ">Czech Republic</option>
          <option value="DK">Denmark</option>
          <option value="DJ">Djibouti</option>
          <option value="DM">Dominica</option>
          <option value="DO">Dominican Republic</option>
          <option value="EC">Ecuador</option>
          <option value="EG">Egypt</option>
          <option value="SV">El Salvador</option>
          <option value="GQ">Equatorial Guinea</option>
          <option value="ER">Eritrea</option>
          <option value="EE">Estonia</option>
          <option value="ET">Ethiopia</option>
          <option value="FK">Falkland Islands (Malvinas)</option>
          <option value="FO">Faroe Islands</option>
          <option value="FJ">Fiji</option>
          <option value="FI">Finland</option>
          <option value="FR">France</option>
          <option value="GF">French Guiana</option>
          <option value="PF">French Polynesia</option>
          <option value="GA">Gabon</option>
          <option value="GM">Gambia</option>
          <option value="GE">Georgia</option>
          <option value="DE">Germany</option>
          <option value="GH">Ghana</option>
          <option value="GI">Gibraltar</option>
          <option value="GR">Greece</option>
          <option value="GL">Greenland</option>
          <option value="GD">Grenada</option>
          <option value="GP">Guadeloupe</option>
          <option value="GU">Guam</option>
          <option value="GT">Guatemala</option>
          <option value="GN">Guinea</option>
          <option value="GW">Guinea-Bissau</option>
          <option value="GY">Guyana</option>
          <option value="HT">Haiti</option>
          <option value="HN">Honduras</option>
          <option value="HK">Hong Kong</option>
          <option value="HU">Hungary</option>
          <option value="IS">Iceland</option>
          <option value="IN">India</option>
          <option value="ID">Indonesia</option>
          <option value="IR">Iran (Islamic Republic of)</option>
          <option value="IQ">Iraq</option>
          <option value="IE">Ireland</option>
          <option value="IL">Israel</option>
          <option value="IT">Italy</option>
          <option value="JM">Jamaica</option>
          <option value="JP">Japan</option>
          <option value="JO">Jordan</option>
          <option value="KZ">Kazakhstan</option>
          <option value="KE">Kenya</option>
          <option value="KI">Kiribati</option>
          <option value="KP">Korea, Democratic People's Republic of</option>
          <option value="KR">Korea, Republic of</option>
          <option value="KW">Kuwait</option>
          <option value="KG">Kyrgyzstan</option>
          <option value="LA">Lao People's Democratic Republic</option>
          <option value="LV">Latvia</option>
          <option value="LB">Lebanon</option>
          <option value="LS">Lesotho</option>
          <option value="LR">Liberia</option>
          <option value="LY">Libyan Arab Jamahiriya</option>
          <option value="LI">Liechtenstein</option>
          <option value="LT">Lithuania</option>
          <option value="LU">Luxembourg</option>
          <option value="MO">Macao</option>
          <option value="MK">Macedonia, the Former Yugoslav Republic of</option>
          <option value="MG">Madagascar</option>
          <option value="MW">Malawi</option>
          <option value="MY">Malaysia</option>
          <option value="MV">Maldives</option>
          <option value="ML">Mali</option>
          <option value="MT">Malta</option>
          <option value="MH">Marshall Islands</option>
          <option value="MQ">Martinique</option>
          <option value="MR">Mauritania</option>
          <option value="MU">Mauritius</option>
          <option value="YT">Mayotte</option>
          <option value="MX">Mexico</option>
          <option value="FM">Micronesia, Federated States of</option>
          <option value="MD">Moldova, Republic of</option>
          <option value="MC">Monaco</option>
          <option value="MN">Mongolia</option>
          <option value="MS">Montserrat</option>
          <option value="MA">Morocco</option>
          <option value="MZ">Mozambique</option>
          <option value="MM">Myanmar</option>
          <option value="NA">Namibia</option>
          <option value="NR">Nauru</option>
          <option value="NP">Nepal</option>
          <option value="NL">Netherlands</option>
          <option value="AN">Netherlands Antilles</option>
          <option value="NC">New Caledonia</option>
          <option value="NZ">New Zealand</option>
          <option value="NI">Nicaragua</option>
          <option value="NE">Niger</option>
          <option value="NG">Nigeria</option>
          <option value="NU">Niue</option>
          <option value="NF">Norfolk Island</option>
          <option value="MP">Northern Mariana Islands</option>
          <option value="NO">Norway</option>
          <option value="OM">Oman</option>
          <option value="PK">Pakistan</option>
          <option value="PW">Palau</option>
          <option value="PS">Palestinian Territory, Occupied</option>
          <option value="PA">Panama</option>
          <option value="PG">Papua New Guinea</option>
          <option value="PY">Paraguay</option>
          <option value="PE">Peru</option>
          <option value="PH">Philippines</option>
          <option value="PN">Pitcairn</option>
          <option value="PL">Poland</option>
          <option value="PT">Portugal</option>
          <option value="PR">Puerto Rico</option>
          <option value="QA">Qatar</option>
          <option value="RE">Reunion</option>
          <option value="RO">Romania</option>
          <option value="RU">Russian Federation</option>
          <option value="RW">Rwanda</option>
          <option value="SH">Saint Helena</option>
          <option value="KN">Saint Kitts and Nevis</option>
          <option value="LC">Saint Lucia</option>
          <option value="PM">Saint Pierre and Miquelon</option>
          <option value="VC">Saint Vincent and the Grenadines</option>
          <option value="WS">Samoa</option>
          <option value="SM">San Marino</option>
          <option value="ST">Sao Tome and Principe</option>
          <option value="SA">Saudi Arabia</option>
          <option value="SN">Senegal</option>
          <option value="SC">Seychelles</option>
          <option value="SL">Sierra Leone</option>
          <option value="SG">Singapore</option>
          <option value="SK">Slovakia</option>
          <option value="SI">Slovenia</option>
          <option value="SB">Solomon Islands</option>
          <option value="SO">Somalia</option>
          <option value="ZA">South Africa</option>
          <option value="GS">South Georgia and the South Sandwich Islands</option>
          <option value="ES">Spain</option>
          <option value="LK">Sri Lanka</option>
          <option value="SD">Sudan</option>
          <option value="SR">Suriname</option>
          <option value="SJ">Svalbard and Jan Mayen</option>
          <option value="SZ">Swaziland</option>
          <option value="SE">Sweden</option>
          <option value="CH">Switzerland</option>
          <option value="SY">Syrian Arab Republic</option>
          <option value="TW">Taiwan, Province of China</option>
          <option value="TJ">Tajikistan</option>
          <option value="TZ">Tanzania, United Republic of</option>
          <option value="TH">Thailand</option>
          <option value="TL">Timor-Leste</option>
          <option value="TG">Togo</option>
          <option value="TK">Tokelau</option>
          <option value="TO">Tonga</option>
          <option value="TT">Trinidad and Tobago</option>
          <option value="TN">Tunisia</option>
          <option value="TR">Turkey</option>
          <option value="TM">Turkmenistan</option>
          <option value="TC">Turks and Caicos Islands</option>
          <option value="TV">Tuvalu</option>
          <option value="UG">Uganda</option>
          <option value="UA">Ukraine</option>
          <option value="AE">United Arab Emirates</option>
          <option value="GB">United Kingdom</option>
          <option value="US">United States</option>
          <option value="UM">United States Minor Outlying Islands</option>
          <option value="UY">Uruguay</option>
          <option value="UZ">Uzbekistan</option>
          <option value="VU">Vanuatu</option>
          <option value="VE">Venezuela</option>
          <option value="VN">Viet Nam</option>
          <option value="VG">Virgin Islands, British</option>
          <option value="VI">Virgin Islands, U.S.</option>
          <option value="WF">Wallis and Futuna</option>
          <option value="EH">Western Sahara</option>
          <option value="YE">Yemen</option>
          <option value="ZM">Zambia</option>
          <option value="ZW">Zimbabwe</option>
        </select>
        <i class="fas fa-globe"></i>
        <?php
        if (isset($errors['country'])) {
          echo '<div class="error">
                <p>' . $errors['country'] . '</p>
              </div>';
          unset($errors['country']);
        }
        ?>
      </div>

      <div style="border: 1px solid #d3d3d3; border-radius: 3px; padding: 12px; width: 300px; font-family: Arial, sans-serif;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
          <div id="captcha-checkbox" style="border: 2px solid #d3d3d3; border-radius: 4px; width: 18px; height: 18px; margin-right: 10px; cursor: pointer;"></div>
          <span style="font-size: 14px;">I'm not a robot</span>
        </div>

        <div id="captcha-container" style="background: #f9f9f9; padding: 10px; border-radius: 2px; margin-bottom: 15px; display: none;">
          <div style="margin-bottom: 10px;">
            <div id="captcha-word" style="font-family: 'Comic Sans MS', cursive; font-size: 20px; color: #3366ff; letter-spacing: 2px;"></div>
          </div>
          <div style="display: flex; gap: 8px;">
            <input type="text" id="captcha-input" placeholder="Type the word" style="flex-grow: 1; padding: 5px; border: 1px solid #d3d3d3; border-radius: 2px;">
            <button id="verify-btn" style="padding: 5px 10px; background: #4285f4; color: white; border: none; border-radius: 2px; cursor: pointer;">Verify</button>
          </div>
          <div id="captcha-message" style="font-size: 12px; margin-top: 8px;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 10px; color: #555;">
          <span>reCAPTOHA</span>
          <div>
            <a href="#" style="color: #555; text-decoration: none; margin-right: 10px;">Privacy</a>
            <a href="#" style="color: #555; text-decoration: none;">Terms</a>
          </div>
        </div>
      </div>

      <button type="submit" name="signup" id="logBtn" class="btn" value="Sign Up" disabled>
        <i id="gearIcon" class="fa fa-gear" style="display: none;"></i>
        <i id="spinnerIcon" class="fa fa-spinner fa-spin" style="display: none;"></i>
        Sign Up
      </button>
      <br>
      ------------OR-------------
      <br>
      <div class="links">
        <p>Already Have Account ?</p>
        <a href="index.php">Sign In</a>
      </div>
    </form>
    <script>
      // Get references to inputs, checkbox, and button
      const Fname = document.getElementById("Fname");
      const Lname = document.getElementById("Lname");
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const confirm_passwordInput = document.getElementById("confirm_password");
      const dobInput = document.getElementById("dob");
      const genderInput = document.getElementById("gender");
      const countryInput = document.getElementById("country");
      // const humanCheck = document.getElementById("human_check");
      const logBtn = document.getElementById("logBtn");

      // CAPTCHA variables
      let isCaptchaVerified = false;
      const words = ["SeCuRe", "AcCeSs", "VeRiFy", "SaFe", "LoGiN", "SyStEm"];
      let currentWord = "";

      function generateCaptcha() {
        currentWord = words[Math.floor(Math.random() * words.length)];
        document.getElementById("captcha-word").textContent = currentWord;
      }

      function verifyCaptcha() {
        const userInput = document.getElementById("captcha-input").value;
        const messageElement = document.getElementById("captcha-message");

        if (userInput === currentWord) {
          messageElement.textContent = "Verification complete!";
          messageElement.style.color = "green";
          document.getElementById("verify-btn").disabled = true;
          document.getElementById("captcha-input").disabled = true;
          document.getElementById("captcha-checkbox").style.backgroundColor = "#0f9d58";
          document.getElementById("captcha-checkbox").style.borderColor = "#0f9d58";
          isCaptchaVerified = true;
          validateInputs(); // Revalidate all inputs
        } else {
          messageElement.textContent = "Incorrect. Try again.";
          messageElement.style.color = "red";
          generateCaptcha();
          document.getElementById("captcha-input").value = "";
          document.getElementById("captcha-input").focus();
          isCaptchaVerified = false;
          validateInputs();
        }
      }

      // Function to enable/disable sign-up button
      function validateInputs() {
        if (
          Fname.value.trim() &&
          Lname.value.trim() &&
          emailInput.value.trim() &&
          passwordInput.value.trim() &&
          confirm_passwordInput.value.trim() &&
          dobInput.value.trim() &&
          genderInput.value.trim() &&
          countryInput.value.trim() &&
          isCaptchaVerified

        ) {
          logBtn.classList.add("active");
          logBtn.removeAttribute("disabled");
        } else {
          logBtn.classList.remove("active");
          logBtn.setAttribute("disabled", true);
        }
      }

      // Initialize CAPTCHA checkbox
      document.getElementById('captcha-checkbox').addEventListener('click', function() {
        this.style.backgroundColor = "#4285f4";
        this.style.borderColor = "#4285f4";
        this.innerHTML = '✓';
        this.style.color = "white";
        this.style.display = "flex";
        this.style.justifyContent = "center";
        this.style.alignItems = "center";

        document.getElementById('captcha-container').style.display = 'block';
        generateCaptcha();
      });

      // Verify button click handler
      document.getElementById('verify-btn').addEventListener('click', verifyCaptcha);

      // Enter key handler for CAPTCHA input
      document.getElementById('captcha-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          verifyCaptcha();
        }
      });

      // Attach event listeners to all required fields
      Fname.addEventListener("input", validateInputs);
      Lname.addEventListener("input", validateInputs);
      emailInput.addEventListener("input", validateInputs);
      passwordInput.addEventListener("input", validateInputs);
      confirm_passwordInput.addEventListener("input", validateInputs);
      dobInput.addEventListener("input", validateInputs);
      genderInput.addEventListener("change", validateInputs);
      countryInput.addEventListener("change", validateInputs);
      // humanCheck.addEventListener("change", validateInputs);

      // Show password visibility toggle
      const eye = document.getElementById("eye");
      eye.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        confirm_passwordInput.type = confirm_passwordInput.type === "password" ? "text" : "password";

        eye.classList.toggle("fa-eye-slash");
        eye.classList.toggle("fa-eye");
      });

      document.getElementById('logBtn').addEventListener('click', function() {
        document.getElementById('gearIcon').style.display = 'none';
        document.getElementById('spinnerIcon').style.display = 'inline-block';

        // Simulate a delay (like processing)
        setTimeout(() => {
          document.getElementById('spinnerIcon').style.display = 'none';
          document.getElementById('gearIcon').style.display = 'inline-block';
          alert('Signed in successfully!');
        }, 2000); // 2-second delay to mimic processing time
      });
    </script>
</body>

</html>
<?php
if (isset($_SESSION['errors'])) {
  unset($_SESSION['errors']);
}
?>