<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "emoji";

// Create a MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Output received data for debugging purposes
var_dump($_POST);

// Get the selected emoji values from the form
$q1 = isset($_POST['q1']) ? $_POST['q1'] : null;
$q2 = isset($_POST['q2']) ? $_POST['q2'] : null;
$q3 = isset($_POST['q3']) ? $_POST['q3'] : null;

// Insert the data into the database
$sql = "INSERT INTO feedback (q1, q2, q3) VALUES ('$q1', '$q2', '$q3')";

if ($conn->query($sql) === TRUE) {
    echo "Feedback submitted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the MySQL connection
$conn->close();
?>
