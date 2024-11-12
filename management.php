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


// Fetch data from each table
$property_types = $conn->query("SELECT * FROM property_type");
$countries = $conn->query("SELECT * FROM country");
$states = $conn->query("SELECT * FROM state");
$cities = $conn->query("SELECT * FROM city");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_name'])) {
        switch ($_POST['form_name']) {
            case 'add_property_type':
                $property_type = $_POST['property_type'];
                if (!empty($property_type)) {
                    $stmt = $conn->prepare("INSERT INTO property_type (Name) VALUES (?)");
                    $stmt->bind_param("s", $property_type);
                    if ($stmt->execute()) {
                        echo "<script>alert('Property type added successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add property type.'); window.location.href='management.php';</script>";
                    }
                    $stmt->close();
                }
                break;

            case 'add_country':
                $country_name = $_POST['country_name'];
                if (!empty($country_name)) {
                    $stmt = $conn->prepare("INSERT INTO country (Country_name) VALUES (?)");
                    $stmt->bind_param("s", $country_name);
                    if ($stmt->execute()) {
                        echo "<script>alert('Country added successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add country.'); window.location.href='management.php';</script>";
                    }
                    $stmt->close();
                }
                break;

            case 'add_state':
                $state_name = $_POST['state_name'];
                if (!empty($state_name)) {
                    $stmt = $conn->prepare("INSERT INTO state (State_name) VALUES (?)");
                    $stmt->bind_param("s", $state_name);
                    if ($stmt->execute()) {
                        echo "<script>alert('State added successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add state.'); window.location.href='management.php';</script>";
                    }
                    $stmt->close();
                }
                break;

            case 'add_city':
                $city_name = $_POST['city_name'];
                if (!empty($city_name)) {
                    $stmt = $conn->prepare("INSERT INTO city (City_name) VALUES (?)");
                    $stmt->bind_param("s", $city_name);
                    if ($stmt->execute()) {
                        echo "<script>alert('City added successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add city.'); window.location.href='management.php';</script>";
                    }
                    $stmt->close();
                }
                break;

                // Update Property Type
            case 'update_property_type':
                $property_type_id = $_POST['property_type_id'];
                $property_type = $_POST['property_type'];

                if (!empty($property_type_id) && !empty($property_type)) {
                    $stmt = $conn->prepare("UPDATE property_type SET Name = ? WHERE Property_ID = ?");
                    $stmt->bind_param("si", $property_type, $property_type_id);  // "s" = string, "i" = integer

                    if ($stmt->execute()) {
                        echo "<script>alert('Property type updated successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to update property type.'); window.location.href='management.php';</script>";
                    }

                    $stmt->close();
                }
                break;

                // Update Country
            case 'update_country':
                $country_id = $_POST['country_id'];
                $country_name = $_POST['country_name'];

                if (!empty($country_id) && !empty($country_name)) {
                    $stmt = $conn->prepare("UPDATE country SET Country_name = ? WHERE Country_ID = ?");
                    $stmt->bind_param("si", $country_name, $country_id);  // "s" = string, "i" = integer

                    if ($stmt->execute()) {
                        echo "<script>alert('Country updated successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to update country.'); window.location.href='management.php';</script>";
                    }

                    $stmt->close();
                }
                break;

                // Update State
            case 'update_state':
                $state_id = $_POST['state_id'];
                $state_name = $_POST['state_name'];

                if (!empty($state_id) && !empty($state_name)) {
                    $stmt = $conn->prepare("UPDATE state SET State_name = ? WHERE State_ID = ?");
                    $stmt->bind_param("si", $state_name, $state_id);  // "s" = string, "i" = integer

                    if ($stmt->execute()) {
                        echo "<script>alert('State updated successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to update state.'); window.location.href='management.php';</script>";
                    }

                    $stmt->close();
                }
                break;

                // Update City
            case 'update_city':
                $city_id = $_POST['city_id'];
                $city_name = $_POST['city_name'];

                if (!empty($city_id) && !empty($city_name)) {
                    $stmt = $conn->prepare("UPDATE city SET City_name = ? WHERE City_ID = ?");
                    $stmt->bind_param("si", $city_name, $city_id);  // "s" = string, "i" = integer

                    if ($stmt->execute()) {
                        echo "<script>alert('City updated successfully!'); window.location.href='management.php';</script>";
                    } else {
                        echo "<script>alert('Failed to update city.'); window.location.href='management.php';</script>";
                    }

                    $stmt->close();
                }
                break;
            default:
                echo "<script>alert('Invalid form submission!'); window.location.href='management.php';</script>";
                break;
        }
    }
}

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
            <li class="active">
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
                    <h1>Manage </h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="management.php">Manage</a>
                        </li>
                    </ul>
                </div>

            </div>



            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Property Types</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Property ID</th>
                                <th>Property Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $property_types->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['Property_ID']; ?></td>
                                    <td><?php echo $row['Name']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Add Type</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <label for="property_type">Property Type Name</label>
                            <input type="hidden" name="form_name" value="add_property_type">
                            <input type="text" name="property_type" id="property_type" placeholder="Enter Property Type" required>
                        </div>
                        <button type="submit" class="btn">Add Property Type</button>
                    </form>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Update Type</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <input type="hidden" name="form_name" value="update_property_type">
                            <input type="text" name="property_type_id" id="property_type_id" placeholder="Enter Property Type Id" required style="margin-bottom: 10px;">
                            <input type="text" name="property_type" id="property_type" placeholder="Enter Property Type" required>
                        </div>
                        <button type="submit" class="btn">Update Property Type</button>
                    </form>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Countries </h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Country ID</th>
                                <th>Country Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $countries->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['Country_ID']; ?></td>
                                    <td><?php echo $row['Country_name']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Add Counrty</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <label for="country_name">Country Name</label>
                            <input type="hidden" name="form_name" value="add_country">
                            <input type="text" name="country_name" id="country_name" placeholder="Enter Country Name" required>
                        </div>
                        <button type="submit" class="btn">Add Country</button>
                    </form>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Update Country</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <input type="hidden" name="form_name" value="update_country">
                            <input type="text" name="country_id" id="country_id" placeholder="Enter Country ID" required style="margin-bottom: 10px;">
                            <input type="text" name="country_name" id="country_name" placeholder="Enter New Country Name" required>
                        </div>
                        <button type="submit" class="btn">Update Country</button>
                    </form>
                </div>

            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Cities </h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>City ID</th>
                                <th>City Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $cities->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['City_ID']; ?></td>
                                    <td><?php echo $row['City_name']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Add City</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <label for="city_name">City Name</label>
                            <input type="hidden" name="form_name" value="add_city">
                            <input type="text" name="city_name" id="city_name" placeholder="Enter City Name" required>
                        </div>
                        <button type="submit" class="btn">Add City</button>
                    </form>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Update City</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <input type="hidden" name="form_name" value="update_city">
                            <input type="text" name="city_id" id="city_id" placeholder="Enter City ID" required style="margin-bottom: 10px;">
                            <input type="text" name="city_name" id="city_name" placeholder="Enter New City Name" required>
                        </div>
                        <button type="submit" class="btn">Update City</button>
                    </form>
                </div>

            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>States </h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>State ID</th>
                                <th>State Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $states->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['State_ID']; ?></td>
                                    <td><?php echo $row['State_name']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Add State</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <label for="state_name">State Name</label>
                            <input type="hidden" name="form_name" value="add_state">
                            <input type="text" name="state_name" id="state_name" placeholder="Enter State Name" required>
                        </div>
                        <button type="submit" class="btn">Add State</button>
                    </form>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Update State</h3>
                    </div>
                    <form action="management.php" method="POST">
                        <div class="input-box">
                            <input type="hidden" name="form_name" value="update_state">
                            <input type="text" name="state_id" id="state_id" placeholder="Enter State ID" required style="margin-bottom: 10px;">
                            <input type="text" name="state_name" id="state_name" placeholder="Enter New State Name" required>
                        </div>
                        <button type="submit" class="btn">Update State</button>
                    </form>
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