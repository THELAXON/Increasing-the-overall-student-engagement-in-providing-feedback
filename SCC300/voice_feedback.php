<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedbackType = $_POST['feedbackType'];
    $feedbackText = $_POST['feedbackText'];

    $con = new mysqli('localhost', 'root', '', 'voice');

    if ($con) {
        $sql = "INSERT INTO feedback (feedbackType, feedbackText) VALUES ('$feedbackType', '$feedbackText')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            echo "Feedback submitted successfully";
        } else {
            die(mysqli_error($con));
        }
    } else {
        die(mysqli_error($con));
    }
}
?>
