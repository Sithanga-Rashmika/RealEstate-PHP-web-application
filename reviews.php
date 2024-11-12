<?php
session_start();
if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// PHP code to handle logout request from JavaScript
if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    // Clear the session and destroy it
    $_SESSION = array();
    echo '<script>
        alert("Logged out successfully!");
        window.location.href = "home.php"; 
    </script>';
    exit();
}

include 'db_connect.php';

// Handle the review status update request
if (isset($_POST['rid']) && isset($_POST['status'])) {
    $rid = intval($_POST['rid']); // Sanitize the review ID
    $status = $_POST['status'];   // Get the status value

    // Prepare and execute the SQL update query
    $stmt = $conn->prepare("UPDATE review SET Status = ? WHERE RID = ?");
    $stmt->bind_param('si', $status, $rid);

    if ($stmt->execute()) {
        echo "Success"; // Send a success response
    } else {
        http_response_code(500);
        echo "Failed to update the review status.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    exit(); 
}

// Fetch all reviews for display
$reviews = $conn->query("SELECT * FROM review");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Admin Dashboard</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>


    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="home.php" class="brand">
            <i class='bx bxs-dashboard'></i>
            <span class="text">Admin Dashboard</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="admin.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="management.php">
                    <i class='bx bx-cog'></i>
                    <span class="text">Management</span>
                </a>
            </li>
            <li>
                <a href="property.php">
                    <i class='bx bx-building-house'></i>
                    <span class="text">Listed Properties</span>
                </a>
            </li>
            <li>
                <a href="owner.php">
                    <i class='bx bx-id-card'></i>
                    <span class="text">Owners</span>
                </a>
            </li>
            <li>
                <a href="agent.php">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="text">Agents</span>
                </a>
            </li>
            <li>
                <a href="user.php">
                    <i class='bx bx-user'></i>
                    <span class="text">User</span>
                </a>
            </li>
            <li class="active">
                <a href="reviews.php">
                    <i class='bx bx-comment-detail'></i>
                    <span class="text">Reviews</span>
                </a>
            </li>

        </ul>
        <ul class="side-menu">

            <li>
                <a href="#" onclick="logoutUser()" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input"></div>
            </form>

            <p href="#" class="nav-link">
                <?php
                // Check if session 'username' is set and display it
                if (isset($_SESSION['Username'])) {
                    echo $_SESSION['Username'];
                } else {
                    echo "Guest";
                }
                ?>
            </p>
            <a href="#" class="profile">
                <img src="http://localhost/project/images/user.png">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="reviews.php">Reviews</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Owners List</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>RID</th>
                                <th>Full Name</th>
                                <th>Job Title</th>
                                <th>Text</th>
                                <th>Rate</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $reviews->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['RID']; ?></td>
                                    <td><?php echo $row['Name']; ?></td>
                                    <td><?php echo $row['Job_title']; ?></td>
                                    <td><?php echo $row['Text']; ?></td>
                                    <td><?php echo $row['Rate']; ?></td>
                                    <td><?php echo $row['Status']; ?></td>
                                    <td style="display: flex; flex-direction:row;">
                                        <button onclick="approveReview(<?php echo $row['RID']; ?>)" title="Approve" style="cursor: pointer;">
                                            <i class="bx bx-check-circle" style="color: green; font-size: 20px;"></i>
                                        </button>
                                        <button onclick="rejectReview(<?php echo $row['RID']; ?>)" title="Reject" style="cursor: pointer;">
                                            <i class="bx bx-x-circle" style="color: red; font-size: 20px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="script.js"></script>
    <script>
        function logoutUser() {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'action';
            input.value = 'logout';
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <script>
        function approveReview(reviewId) {
            if (confirm("Are you sure you want to approve this review?")) {
                updateReviewStatus(reviewId, "Approved");
            }
        }

        function rejectReview(reviewId) {
            if (confirm("Are you sure you want to reject this review?")) {
                updateReviewStatus(reviewId, "Rejected");
            }
        }

        function updateReviewStatus(reviewId, status) {
            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();
            // Define the request
            xhr.open("POST", "reviews.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Handle the response
            xhr.onload = function() {
                if (this.status === 200) {
                    alert(`Review status updated to "${status}" successfully!`);
                    // Reload the page to reflect the changes
                    location.reload();
                } else {
                    alert("Failed to update the review status. Please try again.");
                }
            };

            // Send the request with review ID and status
            xhr.send(`rid=${reviewId}&status=${status}`);
        }
    </script>

</body>

</html>