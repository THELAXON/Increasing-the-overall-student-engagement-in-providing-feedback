document.addEventListener("DOMContentLoaded", function () {
    var formContainer = document.getElementById("form-container");
    var wordcloudContainer = document.getElementById("wordcloud-container");

    // Function to create or update the word cloud
    function createWordCloud(data) {
        wordcloudContainer.innerHTML = ""; // Clear existing word cloud

        var wordFrequency = {};

        // Count the frequency of each word
        data.forEach(function (word) {
            wordFrequency[word] = (wordFrequency[word] || 0) + 1;
        });

        // Create a word cloud based on word frequency
        Object.keys(wordFrequency).forEach(function (word) {
            var wordElement = document.createElement("div");
            wordElement.textContent = word;
            wordElement.className = "word";

            // Set random color for each word
            var randomColor = getRandomColor();
            wordElement.style.color = randomColor;

            var fontSize = wordFrequency[word] * 10; // Adjust font size based on frequency
            wordElement.style.fontSize = fontSize + "px";
            wordElement.style.left = Math.random() * (wordcloudContainer.offsetWidth - fontSize) + "px";
            wordElement.style.top = Math.random() * (wordcloudContainer.offsetHeight - fontSize) + "px";
            wordcloudContainer.appendChild(wordElement);
        });

        // Hide the form container and show the word cloud container
        formContainer.style.display = "none";
        wordcloudContainer.style.display = "block";
    }

    // Function to generate a random color
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Check if the form has been submitted before fetching existing feedback
    var submitted = false;

    // Fetch existing feedback from the database after form submission
    fetch('get_feedback.php')
        .then(response => response.json())
        .then(data => {
            // Create or update the word cloud based on feedback data
            if (submitted) {
                createWordCloud(data);
            }
        })
        .catch(error => console.error('Error fetching feedback:', error));

    // Submit feedback form using AJAX
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault();

        // Collect form data
        var formData = new FormData(event.target);

        // Submit form data using AJAX
        fetch('submit_feedback.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitted = true; // Set submitted to true after form submission
            // Create or update the word cloud based on updated feedback data
            createWordCloud(data);
        })
        .catch(error => console.error('Error submitting feedback:', error));
    });
});
