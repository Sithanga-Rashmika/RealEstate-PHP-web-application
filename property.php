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

// Initialize filter variables
$filterPropertyID = isset($_GET['propertyID']) ? trim($_GET['propertyID']) : '';
$filterPropertyName = isset($_GET['propertyName']) ? trim($_GET['propertyName']) : '';
$filterContactNumber = isset($_GET['contactNumber']) ? trim($_GET['contactNumber']) : '';

// Build the SQL query with dynamic filtering
$query = "SELECT property.*, users.Username 
          FROM property 
          JOIN users ON property.UID = users.UID 
          WHERE 1=1";

// Dynamically add conditions based on filter inputs
$params = [];
$types = "";

if (!empty($filterPropertyID)) {
    $query .= " AND property.PID = ?";
    $types .= 'i';
    $params[] = $filterPropertyID;
}

if (!empty($filterPropertyName)) {
    $query .= " AND property.Title LIKE ?";
    $types .= 's';
    $params[] = "%" . $filterPropertyName . "%"; // Use LIKE for partial matching
}

if (!empty($filterContactNumber)) {
    $query .= " AND property.Contact_no = ?";
    $types .= 's';
    $params[] = $filterContactNumber;
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Generate property cards
$propertyCards = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $propertyCards .= '<div class="property-card">';
        $propertyCards .= '    <div class="image-wrapper">';
        if ($row['Image']) {
            $propertyCards .= '        <img src="' . htmlspecialchars($row['Image']) . '" alt="Property Image">';
        } else {
            $propertyCards .= '        <img src="https://via.placeholder.com/300x200" alt="Property Image">';
        }
        $propertyCards .= '        <div class="price-tag">$' . htmlspecialchars($row['Price']) . '/Rs</div>';
        $propertyCards .= '        <span class="label popular">Popular</span>';
        $propertyCards .= '    </div>';
        $propertyCards .= '    <div class="property-details">';
        $propertyCards .= '        <h3>' . htmlspecialchars($row['Title']) . '</h3>';
        $propertyCards .= '        <p>Bedroom: ' . htmlspecialchars($row['Bedroom']) . ' &nbsp; Bathroom: ' . htmlspecialchars($row['Bathroom']) . ' &nbsp; Land Size: ' . htmlspecialchars($row['Landsize']) . ' perch <br> Building Size: ' . htmlspecialchars($row['Buildingsize']) . ' square</p>';
        $propertyCards .= '        <span class="property-type">' . htmlspecialchars($row['Property_type']) . '</span>';
        $propertyCards .= '        <p class="address"><i class="fa fa-map-marker"></i> ' . htmlspecialchars($row['State']) . ', ' . htmlspecialchars($row['City']) . ', ' . htmlspecialchars($row['Country']) . '</p>';
        $propertyCards .= '        <p class="address"><i class="fa fa-phone"></i> ' . htmlspecialchars($row['Contact_no']) . '</p>';
        $propertyCards .= '        <div class="author-date">';
        $propertyCards .= '            <span><i class="fa fa-user"></i>' . htmlspecialchars($row['Username']) . '</span>';
        $propertyCards .= '            <span><i class="fa fa-calendar"></i> ' . htmlspecialchars($row['Date']) . '</span>';
        $propertyCards .= '        </div>';
        $propertyCards .= '    </div>';
        $propertyCards .= '</div>';
    }
} else {
    $propertyCards = "<p>No properties found matching your criteria.</p>";
}

$stmt->close();
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            <li class="active">
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
                    <h1>Listed Properties</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="property.php">Properties</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Property List</h3>
                    </div>

                    <div class="filter-section">
                        <h2>Search Properties</h2>
                        <form id="property-filter-form" method="GET" action="property.php" class="search-form">
                            <div class="form-group">
                                <input type="text" id="propertyID" name="propertyID" placeholder="Enter Property ID">
                            </div>

                            <div class="form-group">
                                <input type="text" id="propertyName" name="propertyName" placeholder="Enter Title">
                            </div>

                            <div class="form-group">
                                <input type="text" id="contactNumber" name="contactNumber" placeholder="Enter Mobile Number">
                            </div>

                            <button type="submit" class="search-btn">Search</button>
                        </form>
                    </div>

                    <div class="property-container">
                        <?php echo $propertyCards; ?>
                    </div>
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