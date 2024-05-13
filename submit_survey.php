<?php
// Start session
session_start();

// Connect to the database
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "survey_db";

$conn = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare SQL statement for inserting survey data
    $sql = "INSERT INTO surveys (full_name, email, date_of_birth, contact_number, favorite_foods, movies_rating, radio_rating, eat_out_rating, watch_tv_rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $fullName, $email, $dateOfBirth, $contactNumber, $favoriteFoodsText, $moviesRating, $radioRating, $eatOutRating, $watchTVRating);
    
    // Set parameters from form data
    $fullName = $_POST["Name"];
    $email = $_POST["Email"];
    $dateOfBirth = $_POST["DateOfBirth"];
    $contactNumber = $_POST["ContactNumber"];
    $favoriteFoods = isset($_POST["select"]) ? $_POST["select"] : [];
    $favoriteFoodsText = implode(", ", $favoriteFoods);
    $moviesRating = $_POST["movies"];
    $radioRating = $_POST["radio"];
    $eatOutRating = $_POST["eatOut"];
    $watchTVRating = $_POST["watchTV"];
    


// Calculate total number of surveys
$sql = "SELECT COUNT(*) AS total_surveys FROM surveys";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalSurveys = $row['total_surveys'];

// Calculate average age
$sql = "SELECT AVG(DATEDIFF(NOW(), date_of_birth) / 365) AS average_age FROM surveys";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$averageAge = $row['average_age'];

// Calculate oldest and youngest participants
$sql = "SELECT MIN(DATEDIFF(NOW(), date_of_birth) / 365) AS youngest, MAX(DATEDIFF(NOW(), date_of_birth) / 365) AS oldest FROM surveys";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$youngest = $row['youngest'];
$oldest = $row['oldest'];

// Calculate percentage of people who like pizza
$sql = "SELECT COUNT(*) AS total_pizza_lovers FROM surveys WHERE favorite_foods LIKE '%Pizza%'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalPizzaLovers = $row['total_pizza_lovers'];
$percentagePizzaLovers = round(($totalPizzaLovers / $totalSurveys) * 100, 1);

// Calculate average rating for people who like to eat out
$sql = "SELECT AVG(eat_out_rating) AS avg_eat_out_rating FROM surveys";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$averageEatOutRating = round($row['avg_eat_out_rating'], 1);

// Store calculated results in session variables
$_SESSION['totalSurveys'] = $totalSurveys;
$_SESSION['averageAge'] = $averageAge;
$_SESSION['maxAge'] = $oldest;
$_SESSION['minAge'] = $youngest;
$_SESSION['percentagePizzaLovers'] = $percentagePizzaLovers;
$_SESSION['percentagePastaLovers'] = 0; // Calculate this value if needed
$_SESSION['percentagePapAndWorsLovers'] = 0; // Calculate this value if needed
$_SESSION['averageEatOutRating'] = $averageEatOutRating;

// Execute statement
    $stmt->execute();
	
 // Close statement
    $stmt->close();
    
// Redirect to view_result.html
header("Location: view_result.html");
exit();
}

// Close database connection
$conn->close();
?>
