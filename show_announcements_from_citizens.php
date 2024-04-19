<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Query to fetch announcements from the database table
$query = "SELECT *  FROM announcements_cityzen";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed.");
}

// Displaying the fetched announcements
echo "<h2>Announcements from Citizens</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Have Seen Date</th><th>Number of People</th><th>Accepted</th><th>Date of Response</th><th>Date of Completion</th><th>Username</th>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>"; 
    echo "<td>{$row['have_seen_date']}</td>";
    echo "<td>{$row['num_people']}</td>";
    echo "<td>{$row['accepted']}</td>";
    echo "<td>{$row['date_of_response']}</td>";
    echo "<td>{$row['date_of_comp']}</td>";
    echo "<td>{$row['username']}</td>";

    echo "</tr>";
}

echo "</table>";

// Release the result set
$result->free();

// Close the database connection
$conn->close();
?>