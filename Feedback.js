document.addEventListener("DOMContentLoaded", function () {
    const feedbackInput = document.getElementById("feedback");
    const charCount = document.getElementById("charCount");
    const sendBtn = document.getElementById("sendBtn");
    const emailInput = document.getElementById("email");
    const popup = document.getElementById("popup");
    const form = document.getElementById("feedbackForm");
    const gearIcon = document.getElementById("gearIcon");
    const spinnerIcon = document.getElementById("spinnerIcon");


    function updateButtonState() {
        let textLength = feedbackInput.value.trim().length;
        charCount.textContent = textLength;

        if (textLength > 0) {
            sendBtn.classList.add("active");
            sendBtn.removeAttribute("disabled");
        } else {
            sendBtn.classList.remove("active");
            sendBtn.setAttribute("disabled", "true");
        }
    }

    // Initialize the character count
    charCount.textContent = "0/500";

    feedbackInput.addEventListener("input", updateButtonState);

    sendBtn.addEventListener("click", function () {
        if (feedbackInput.value.trim() !== "") {
            // Show the pop-up notification
            popup.style.display = "block";

            // Hide the pop-up after 3 seconds
            setTimeout(() => {
                popup.style.display = "none";
            }, 3000);

            // Reset form
            feedbackInput.value = "";
            emailInput.value = "";
            charCount.textContent = "0/500";
            updateButtonState();
        }
    });

    updateButtonState();

    // Show spinner and hide gear icon
    document.getElementById('sendBtn').addEventListener('click', function() {
        document.getElementById('gearIcon').style.display = 'none';
        document.getElementById('spinnerIcon').style.display = 'inline-block';

        // Simulate a delay (like processing)
        setTimeout(() => {
          document.getElementById('spinnerIcon').style.display = 'none';
          document.getElementById('gearIcon').style.display = 'inline-block';
        //   alert('Signed in successfully!');
        }, 2000); // 2-second delay to mimic processing time
      });
});