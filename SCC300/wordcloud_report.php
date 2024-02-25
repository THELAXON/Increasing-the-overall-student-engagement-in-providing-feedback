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

// Fetch all words from the feedback table
$sql = "SELECT word1, word2, word3 FROM feedback";
$result = $conn->query($sql);

$words = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $words[] = $row['word1'];
        $words[] = $row['word2'];
        $words[] = $row['word3'];
    }
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($words);

$conn->close();
?>
