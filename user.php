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

$users = $conn->query("SELECT UID, Username, MobileNumber, Address, Email FROM users WHERE Role='user'");

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
            <li  class="active">
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
                    <h1>User</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="users.php">users</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Agents List</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>UID</th>
                                <th>Full Name</th>
                                <th>Mobile Number</th>
                                <th>Address</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['UID']; ?></td>
                                    <td><?php echo $row['Username']; ?></td>
                                    <td><?php echo $row['MobileNumber']; ?></td>
                                    <td><?php echo $row['Address']; ?></td>
                                    <td><?php echo $row['Email']; ?></td>
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
</body>

</html>