<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

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

    <script>
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
            } else {
                messageElement.textContent = "Incorrect. Try again.";
                messageElement.style.color = "red";
                generateCaptcha();
                document.getElementById("captcha-input").value = "";
                document.getElementById("captcha-input").focus();
            }
        }

        // Checkbox click handler
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

        // Enter key handler
        document.getElementById('captcha-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyCaptcha();
            }
        });
    </script>

</body>

</html>