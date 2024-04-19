<?php

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get start and end dates from the request
$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

// Fetch data for announcements with accepted status "expected"
$sqlAnnouncementsExpected = "SELECT COUNT(*) AS num_announcements, DATE(have_seen_date) AS announcement_date FROM announcements_cityzen WHERE accepted = 'expected' AND DATE(have_seen_date) BETWEEN '$startDate' AND '$endDate' GROUP BY DATE(have_seen_date)";
$resultAnnouncementsExpected = mysqli_query($conn, $sqlAnnouncementsExpected);

// Fetch data for announcements with accepted status "not_expected"
$sqlAnnouncementsNotExpected = "SELECT COUNT(*) AS num_announcements, DATE(have_seen_date) AS announcement_date FROM announcements_cityzen WHERE accepted = 'not_expected' AND DATE(have_seen_date) BETWEEN '$startDate' AND '$endDate' GROUP BY DATE(have_seen_date)";
$resultAnnouncementsNotExpected = mysqli_query($conn, $sqlAnnouncementsNotExpected);

// Fetch data for help offerings with accepted status "expected"
$sqlHelpOfferingsExpected = "SELECT COUNT(*) AS num_offerings, DATE(date) AS offering_date FROM help_offering WHERE accepted = 'expected' AND DATE(date) BETWEEN '$startDate' AND '$endDate' GROUP BY DATE(date)";
$resultHelpOfferingsExpected = mysqli_query($conn, $sqlHelpOfferingsExpected);

// Fetch data for help offerings with accepted status "not_expected"
$sqlHelpOfferingsNotExpected = "SELECT COUNT(*) AS num_offerings, DATE(date) AS offering_date FROM help_offering WHERE accepted = 'not_expected' AND DATE(date) BETWEEN '$startDate' AND '$endDate' GROUP BY DATE(date)";
$resultHelpOfferingsNotExpected = mysqli_query($conn, $sqlHelpOfferingsNotExpected);

// Close the database connection
mysqli_close($conn);

// Prepare the data for JSON encoding
$data = [];

// Process the result for announcements with accepted status "expected"
$announcementsExpectedData = [];
while ($row = mysqli_fetch_assoc($resultAnnouncementsExpected)) {
    $announcementsExpectedData[$row['announcement_date']] = $row['num_announcements'];
}

// Process the result for announcements with accepted status "not_expected"
$announcementsNotExpectedData = [];
while ($row = mysqli_fetch_assoc($resultAnnouncementsNotExpected)) {
    $announcementsNotExpectedData[$row['announcement_date']] = $row['num_announcements'];
}

// Process the result for help offerings with accepted status "expected"
$helpOfferingsExpectedData = [];
while ($row = mysqli_fetch_assoc($resultHelpOfferingsExpected)) {
    $helpOfferingsExpectedData[$row['offering_date']] = $row['num_offerings'];
}

// Process the result for help offerings with accepted status "not_expected"
$helpOfferingsNotExpectedData = [];
while ($row = mysqli_fetch_assoc($resultHelpOfferingsNotExpected)) {
    $helpOfferingsNotExpectedData[$row['offering_date']] = $row['num_offerings'];
}

// Loop through dates from start date to end date
for ($date = strtotime($startDate); $date <= strtotime($endDate); $date += 86400) { // Adding 86400 seconds (1 day)
    $dateStr = date('Y-m-d', $date);
    $announcementsExpectedCount = isset($announcementsExpectedData[$dateStr]) ? $announcementsExpectedData[$dateStr] : 0;
    $announcementsNotExpectedCount = isset($announcementsNotExpectedData[$dateStr]) ? $announcementsNotExpectedData[$dateStr] : 0;
    $helpOfferingsExpectedCount = isset($helpOfferingsExpectedData[$dateStr]) ? $helpOfferingsExpectedData[$dateStr] : 0;
    $helpOfferingsNotExpectedCount = isset($helpOfferingsNotExpectedData[$dateStr]) ? $helpOfferingsNotExpectedData[$dateStr] : 0;

    $data[] = [
        'date' => $dateStr,
        'announcementsExpected' => $announcementsExpectedCount,
        'announcementsNotExpected' => $announcementsNotExpectedCount,
        'helpOfferingsExpected' => $helpOfferingsExpectedCount,
        'helpOfferingsNotExpected' => $helpOfferingsNotExpectedCount
    ];
}

// Set the content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($data);

?>
