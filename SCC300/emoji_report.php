<?php
// PHP code to retrieve data from the database and generate pie charts
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

// Retrieve data from the database
$sql = "SELECT q1, q2, q3 FROM feedback";
$result = $conn->query($sql);

// Initialize arrays to store counts for each option
$q1Counts = ['bad' => 0, 'neutral' => 0, 'good' => 0];
$q2Counts = ['bad' => 0, 'neutral' => 0, 'good' => 0];
$q3Counts = ['bad' => 0, 'neutral' => 0, 'good' => 0];

// Process the retrieved data and update counts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $q1Counts[$row['q1']]++;
        $q2Counts[$row['q2']]++;
        $q3Counts[$row['q3']]++;
    }
}

// Generate pie chart data
$q1Labels = ['Bad', 'Neutral', 'Good'];
$q1Values = array_values($q1Counts);
$q2Labels = ['Bad', 'Neutral', 'Good'];
$q2Values = array_values($q2Counts);
$q3Labels = ['Bad', 'Neutral', 'Good'];
$q3Values = array_values($q3Counts);

// Close the MySQL connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Feedback Report</h2>
    <div style="width: 25%;">
        <canvas id="question1Chart"></canvas>
    </div>
    <div style="width: 25%;">
        <canvas id="question2Chart"></canvas>
    </div>
    <div style="width: 25%;">
        <canvas id="question3Chart"></canvas>
    </div>

    <script>
        var question1Chart = new Chart(document.getElementById('question1Chart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($q1Labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($q1Values); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        var question2Chart = new Chart(document.getElementById('question2Chart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($q2Labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($q2Values); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        var question3Chart = new Chart(document.getElementById('question3Chart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($q3Labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($q3Values); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });
    </script>
</body>
</html>
