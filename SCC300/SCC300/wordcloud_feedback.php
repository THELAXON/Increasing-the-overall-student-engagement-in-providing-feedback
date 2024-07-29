<?php
// wordcloud_feedback.php

// Read the list of bad words from words.json
$badWordsJson = file_get_contents('words.json');
$badWords = json_decode($badWordsJson, true);

// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wordcloud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a basic stemming function
function stemWord($word) {
    // Convert the word to lowercase for case-insensitivity
    $word = strtolower($word);

    // Define a list of common suffixes to remove
    $suffixes = array("ing", "ed");

    // Check if the word ends with any of the suffixes
    $suffixFound = false;
    foreach ($suffixes as $suffix) {
        if (endsWith($word, $suffix)) {
            // If the word ends with the suffix, remove it
            $word = substr($word, 0, -strlen($suffix));
            $suffixFound = true;
            break;
        }
    }

    // If a suffix is found, add the default "ing" suffix
    if ($suffixFound) {
        $word .= "ing";
    }
    
    // Return the stemmed word
    return $word;
}

// Function to check if a string ends with a specific suffix
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

// Retrieve words from the form and perform stemming
$word1 = stemWord(strtolower($_POST['word1']));
$word2 = stemWord(strtolower($_POST['word2']));
$word3 = stemWord(strtolower($_POST['word3']));

// Check if any of the words contain bad words
if (in_array($word1, $badWords) || in_array($word2, $badWords) || in_array($word3, $badWords)) {
    echo json_encode(["error" => "Please refrain from using inappropriate language."]);
} else {
    // Insert stemmed words into the database
    $sql = "INSERT INTO feedback (word1, word2, word3) VALUES ('$word1', '$word2', '$word3')";

    if ($conn->query($sql) === TRUE) {
        // Fetch all words from the feedback table after submission
        $fetchSql = "SELECT word1, word2, word3 FROM feedback";
        $result = $conn->query($fetchSql);

        $words = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $words[] = $row['word1'];
                $words[] = $row['word2'];
                $words[] = $row['word3'];
            }
        }

        // Output JSON response with updated feedback data
        header('Content-Type: application/json');
        echo json_encode($words);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

$conn->close();
?>
