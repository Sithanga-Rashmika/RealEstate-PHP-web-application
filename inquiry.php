<?php
session_start();
include 'db_connect.php'; // Include database connection

// Get the Property ID from the URL or form
$propertyID = isset($_GET['propertyID']) ? intval($_GET['propertyID']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

if ($propertyID == 0) {
    echo "<script>alert('Invalid Property ID!'); window.location.href = 'properties.php';</script>";
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['UID'])) {
    echo "<script>alert('Please login to place an inquiry.'); window.location.href = 'login.php';</script>";
    exit();
}

// Get the logged-in user details from the session
$UID = $_SESSION['UID'];
$name = $email = $contact = "";

// Fetch user details from the database to auto-fill the form
$query = "SELECT Username, Email, MobileNumber FROM users WHERE UID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $UID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['Username'];
    $email = $user['Email'];
    $contact = $user['MobileNumber'];
}

$stmt->close();

// Handle the form submission for inquiry placement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $contact = isset($_POST['number']) ? htmlspecialchars($_POST['number']) : '';
    $text = isset($_POST['testimonial']) ? htmlspecialchars($_POST['testimonial']) : '';

    // Prepare the SQL statement for inserting inquiry
    $query = "INSERT INTO inquiry (PID, Name, Email, Contact, Text) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $propertyID, $name, $email, $contact, $text);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('Inquiry submitted successfully!'); window.location.href = 'properties.php';</script>";
    } else {
        echo "<script>alert('Failed to submit the inquiry. Please try again.');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Inquiry</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    // Include the header
    include 'header.php';
    ?>

    <div class="testo-form-container" style="margin:50px auto;">
        <h1>Place Inquiry</h1>
        <form method="POST" action="">
            <div class="testo-form-group">
                <input type="text" id="id" name="id_display" value="<?php echo $propertyID; ?>" disabled>
                <input type="hidden" id="id" name="id" value="<?php echo $propertyID; ?>">
            </div>
            <div class="testo-form-group">
                <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo $name; ?>" required>
            </div>

            <div class="testo-form-group">
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>" required>
            </div>
            <div class="testo-form-group">
                <input type="text" id="number" name="number" placeholder="Enter your contact no" value="<?php echo $contact; ?>" required>
            </div>

            <div class="testo-form-group">
                <textarea id="testimonial" name="testimonial" placeholder="Write your inquiry here..." required></textarea>
            </div>

            <button type="submit" class="testo-submit-btn">Submit</button>
        </form>
    </div>

    <?php
    // Include the footer
    include 'footer.php';
    ?>
</body>
</html>
