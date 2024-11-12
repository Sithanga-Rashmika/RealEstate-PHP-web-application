<?php
// Include database connection file once at the beginning
include 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// PHP code to handle logout request from JavaScript
if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_start();
    session_unset();
    session_destroy();
    echo "<script>alert('Logged out successfully!'); window.location.href = 'home.php';</script>";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for a valid database connection and session status
    if (!isset($conn) || $conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize form inputs
    $name = htmlspecialchars($_POST['name']);
    $jobTitle = htmlspecialchars($_POST['job']);
    $testimonial = htmlspecialchars($_POST['testimonial']);
    $rating = intval($_POST['rating']);

    // Prepare the SQL statement
    $sql = "INSERT INTO review (Name, Job_title, Text, Rate) VALUES (?, ?, ?, ?)";

    // Initialize the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $name, $jobTitle, $testimonial, $rating);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Testimonial added successfully!'); window.location.href='home.php'</script>";
        } else {
            echo "<script>alert('Error: Could not add the testimonial.');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error: Could not prepare the statement.');</script>";
    }
}

$reviews = $conn->query("SELECT Name, Job_title, Text, Rate, Status FROM review WHERE Status = 'Approved'");
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Home</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap">
</head>

<body>

    <?php
    // Include the header
    include 'header.php';
    ?>
    <div class="home_img">
        <div class="home_text">
            <h1>Buy & Sell Your Property with Primenest Realty</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam. Sed commodo nibh ante facilisis bibendum dolor feugiat at.</p>
            <button>LEARN MORE</button>
        </div>
    </div>

    <div class="container1">
        <div class="sub_container">
            <img src="http://localhost/project/images/icon2.png" alt="Icon 1">
            <h3>Full Furnished</h3>
            <p>Mauris eu porta orci. In at erat enim. Suspendisse felis erat, volutpat at nisl sit amet, maximus molestie nisi.</p>
        </div>
        <div class="sub_container">
            <img src="http://localhost/project/images/icon4.png" alt="Icon 2">
            <h3>Living Inside a Nature</h3>
            <p>Mauris eu porta orci. In at erat enim. Suspendisse felis erat, volutpat at nisl sit amet, maximus molestie nisi.</p>
        </div>
        <div class="sub_container">
            <img src="http://localhost/project/images/icon3.png" alt="Icon 1">
            <h3>Luxurious Fittings</h3>
            <p>Mauris eu porta orci. In at erat enim. Suspendisse felis erat, volutpat at nisl sit amet, maximus molestie nisi.</p>
        </div>
    </div>
    <div class="container2_home">
        <div class="home2_sub image-section">
            <img src="http://localhost/project/images/house img.jpeg" alt="City Real Estate">
        </div>
        <div class="home2_sub text-section">
            <h4>AWARDS WINNING REAL ESTATE COMPANY</h4>
            <h1>Welcome to Primenest Realty</h1>
            <p>Quisque eget nisl id nulla sagittis auctor quis id. Aliquam quis vehicula enim, non aliquam risus. Sed a tellus quis mi rhoncus dignissim.</p>
            <p>Aenean eleifend in felis id viverra. Vestibulum semper ex eu molestie pulvinar. Maecenas non efficitur metus, et semper sem. Mauris ligula sapien, gravida a scelerisque ut, vehicula sed mauris. Proin dapibus mi id vulputate euismod.</p>
            <button>Read More</button>
        </div>
    </div>

    <div class="testo">
        <!-- Testimonial Section -->
        <div class="test-wrapper">
            <div class="test-row">
                <?php
                if ($reviews->num_rows > 0) {
                    while ($row = $reviews->fetch_assoc()) {
                        $stars = str_repeat('⭐', $row['Rate']);
                        echo '
                <div class="test-col">
                    <p class="review-text">"' . htmlspecialchars($row['Text']) . '"</p>
                    <div class="star-rating">' . $stars . '</div>
                    <div class="reviewer-name">' . htmlspecialchars($row['Name']) . '</div>
                    <div class="reviewer-title">' . htmlspecialchars($row['Job_title']) . '</div>
                </div>';
                    }
                } else {
                    echo '<p>No testimonials available.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Testimonial Form Section -->
        <div class="testo-form-container">
            <h1>Add a New Testimonial</h1>
            <form method="POST" action="home.php">
                <div class="testo-form-group">
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>

                <div class="testo-form-group">
                    <input type="text" id="job" name="job" placeholder="Enter your job title" required>
                </div>

                <div class="testo-form-group">
                    <textarea id="testimonial" name="testimonial" placeholder="Write your testimonial here..." required></textarea>
                </div>

                <div class="testo-form-group">
                    <select id="rating" name="rating" required>
                        <option value="" disabled selected>Select Rating</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>

                <button type="submit" class="testo-submit-btn">Submit Testimonial</button>
            </form>
        </div>
    </div>

    <?php
    // Include the header
    include 'footer.php';
    ?>


</body>

</html>