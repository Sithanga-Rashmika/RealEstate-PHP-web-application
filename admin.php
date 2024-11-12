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

// SQL queries to get the counts of each entity
$totalPropertyTypes = $conn->query("SELECT COUNT(*) AS total FROM property_type")->fetch_assoc()['total'];
$totalCountries = $conn->query("SELECT COUNT(*) AS total FROM country")->fetch_assoc()['total'];
$totalStates = $conn->query("SELECT COUNT(*) AS total FROM state")->fetch_assoc()['total'];
$totalCities = $conn->query("SELECT COUNT(*) AS total FROM city")->fetch_assoc()['total'];
$totalAgents = $conn->query("SELECT COUNT(*) AS total FROM users WHERE Role='agent'")->fetch_assoc()['total'];
$totalOwners = $conn->query("SELECT COUNT(*) AS total FROM users WHERE Role='owner'")->fetch_assoc()['total'];
$totalBuyers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE Role='user'")->fetch_assoc()['total'];
$totalProperties = $conn->query("SELECT COUNT(*) AS total FROM property")->fetch_assoc()['total'];

// Close the database connection
$conn->close();


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
            <li class="active">
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
            <li>
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
                            <a class="active" href="admin.php">Dashboard</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-home'></i>
                    <span class="text">
                        <h3><?php echo $totalProperties; ?></h3>
                        <p>Total Properties</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-category'></i>
                    <span class="text">
                        <h3><?php echo $totalPropertyTypes; ?></h3>
                        <p>Total Property Types</p>
                    </span>
                </li>
            </ul>
            <ul class="box-info">
                <li>
                    <i class='bx bxs-flag'></i>
                    <span class="text">
                        <h3><?php echo $totalCountries; ?></h3>
                        <p>Total Countries</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-map'></i>
                    <span class="text">
                        <h3><?php echo $totalStates; ?></h3>
                        <p>Total States</p>
                    </span>
                </li>
            </ul>
            <ul class="box-info">
                <li>
                    <i class='bx bxs-buildings'></i>
                    <span class="text">
                        <h3><?php echo $totalCities; ?></h3>
                        <p>Total Cities</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-id-card'></i>
                    <span class="text">
                        <h3><?php echo $totalAgents; ?></h3>
                        <p>Total Agents</p>
                    </span>
                </li>
            </ul>
            <ul class="box-info">
                <li>
                    <i class='bx bx-user'></i>
                    <span class="text">
                        <h3><?php echo $totalOwners; ?></h3>
                        <p>Total Owners</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-user-check'></i>
                    <span class="text">
                        <h3><?php echo $totalBuyers; ?></h3>
                        <p>Total Buyers/Users</p>
                    </span>
                </li>
            </ul>
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
</body>

</html>