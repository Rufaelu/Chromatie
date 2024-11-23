<?php
// Database connection
$host = "localhost";  
$username = "root";  
$password = "";  
$database = "chromatie";  

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch total stats
function fetch_total_stats($conn) {
    $sql = "SELECT * FROM dashboard_stats";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch data as an associative array
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Fetch stats data
$stats = fetch_total_stats($conn);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Manager Form</title>
    <link rel="stylesheet" href="../CSS/ASB.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php
        // Include the PHP file to fetch data

        // Check if stats data is available
        if ($stats) {
            $totalArtists = $stats['total_artists'];
            $totalCustomers = $stats['total_customers'];
            $totalServicePosts = $stats['total_services'];
        } else {
            $totalArtists = 0;
            $totalCustomers = 0;
            $totalServicePosts = 0;
        }
    ?>

    <div class="main-content">
        <div class="header">
            <h1>Welcome to Chromatie Stat <span>Board</span></h1>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Service Posts</h3>
                <p><?php echo $totalServicePosts; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Artist</h3>
                <p><?php echo $totalArtists; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total No of Customers</h3>
                <p><?php echo $totalCustomers; ?></p>
            </div>
        </div>

        <div class="charts-and-jobs">
            <div class="chart">
                <h3>Application Users</h3>
                <canvas id="responseChart"></canvas>
                <div class="legend">
                    <span class="TotalArtists">Total Artists: <?php echo $totalArtists; ?></span>
                    <span class="TotalCustomers">Total Customers: <?php echo $totalCustomers; ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('responseChart').getContext('2d');
        const responseChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Total Artists', 'Total Customers'],
                datasets: [{
                    label: 'Application Users',
                    data: [<?php echo $totalArtists; ?>, <?php echo $totalCustomers; ?>],
                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(75, 192, 192, 0.6)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
