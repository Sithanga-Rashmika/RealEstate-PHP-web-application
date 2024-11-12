<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// PHP code to handle logout request from JavaScript
if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    $_SESSION = array();
    echo '<script>
        alert("Logged out successfully!");
        window.location.href = "home.php"; 
    </script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary" style="padding-left:30px; padding-right:30px;">
        <div class="container-fluid">
            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img src="./images/logo.png" height="50" alt="logo" loading="lazy" />
                </a>
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="properties.php">Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactus.php">Contact Us</a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>

            <!-- Right elements -->
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <p style="font-size: 16px; font-weight:600; margin-right:10px;">
                    <?php
                    // Check if session variable 'Username' is set
                    if (isset($_SESSION['Username'])) {
                        echo 'Welcome, ' . $_SESSION['Username'];
                    } else {
                        echo 'Hello, Guest';
                    }
                    ?>
                </p>
                <div class="dropdown">
                    <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" aria-expanded="false">
                        <i class="fas fa-user" style="color: #333;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <a class="dropdown-item" href="myprofile.php">My profile</a>
                        </li>
                        <?php
                        // Check if session variable 'Username' is set
                        if (isset($_SESSION['Username'])) {
                            // Display the Logout button if session exists
                            echo '<li><a class="dropdown-item" href="#" onclick="logoutUser()">Logout</a></li>';
                        } else {
                            // Display the Login button if no session exists
                            echo '<li><a class="dropdown-item" href="login.php">Login</a></li>';
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {
                            echo '<li><a class="dropdown-item" href="admin.php">Admin Dashboard</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- Right elements -->
        </div>
    </nav>
    <!-- Navbar -->

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>

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