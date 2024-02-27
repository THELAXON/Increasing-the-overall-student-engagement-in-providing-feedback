<?php
// PHP code to retrieve data from the database and generate pie chart
$host = "localhost";
$username = "root";
$password = "";
$database = "voice";
// Create a MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the database for pie chart
$sqlPieChart = "SELECT feedbackType, COUNT(*) as count FROM feedback GROUP BY feedbackType";
$resultPieChart = $conn->query($sqlPieChart);

// Initialize arrays to store pie chart data
$feedbackTypes = array();
$feedbackCounts = array();

// Process the retrieved data for pie chart
if ($resultPieChart->num_rows > 0) {
    while ($row = $resultPieChart->fetch_assoc()) {
        $feedbackTypes[] = $row['feedbackType'];
        $feedbackCounts[] = $row['count'];
    }
}

// Retrieve data from the database for comments table
$sqlComments = "SELECT feedbackText FROM feedback";
$resultComments = $conn->query($sqlComments);

// Close the MySQL connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #333;
            color: #fff;
        }
        h2,h3 {
            text-align: center;
        }
        .chart-container {
            display: flex;
            justify-content: space-around;
            max-width: 1200px;
            margin: 0 auto;
        }
        .chart {
            width: 30%;
        }
        table {
            margin: 0 auto;
            width: 50%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Feedback Report</h2>
    <div class="chart-container">
        <div class="chart">
            <canvas id="feedbackChart"></canvas>
        </div>
    </div>

    <!-- Table for comments -->
    <h3>Comments</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Feedback Text</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display comments in the table
            if ($resultComments->num_rows > 0) {
                while ($row = $resultComments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['feedbackText'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='1'>No comments found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Generate pie chart for feedback types
        var feedbackChart = new Chart(document.getElementById('feedbackChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($feedbackTypes); ?>,
                datasets: [{
                    data: <?php echo json_encode($feedbackCounts); ?>,
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>