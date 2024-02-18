<?php
// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wordcloud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve words from the form
$word1 = $_POST['word1'];
$word2 = $_POST['word2'];
$word3 = $_POST['word3'];

// Check if all three words are provided
if (empty($word1) || empty($word2) || empty($word3)) {
    echo json_encode(["error" => "Please provide all three words of feedback."]);
} else {
    // Insert words into the database
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
