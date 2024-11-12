<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and `UID` is set in the session
if (isset($_SESSION['UID'])) {
    $uid = $_SESSION['UID'];

    $filterType = isset($_GET['propertyType']) ? htmlspecialchars($_GET['propertyType']) : '';
    $filterState = isset($_GET['state']) ? htmlspecialchars($_GET['state']) : '';
    $filterCity = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '';

    // Fetch user details from the database using the UID
    $query = "SELECT * FROM users WHERE UID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists and fetch the data
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['Username'];
        $role = $user['Role'];
        $email = $user['Email'];
        $phone = $user['MobileNumber'];
        $address = $user['Address'];
    } else {
        echo "<p>User not found.</p>";
        exit();
    }

    // Close the statement
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
        switch ($_POST['form_type']) {
            case 'update_profile':
                // Update Profile Logic
                $new_username = htmlspecialchars($_POST['name']);
                $new_email = htmlspecialchars($_POST['email']);
                $new_phone = htmlspecialchars($_POST['phone']);
                $new_address = htmlspecialchars($_POST['address']);
                $new_pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';

                if (!empty($new_pwd)) {
                    $hashedPassword = password_hash($new_pwd, PASSWORD_DEFAULT);
                    $update_query = "UPDATE users SET Username = ?, Email = ?, MobileNumber = ?, Address = ?, Password = ? WHERE UID = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("sssssi", $new_username, $new_email, $new_phone, $new_address, $hashedPassword, $uid);
                } else {
                    $update_query = "UPDATE users SET Username = ?, Email = ?, MobileNumber = ?, Address = ? WHERE UID = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("ssssi", $new_username, $new_email, $new_phone, $new_address, $uid);
                }
                $update_stmt->execute();
                $update_stmt->close();
                echo "<script>alert('Profile updated successfully!'); window.location.href='myprofile.php';</script>";
                break;

            case 'list_property':
                // List Property Logic
                $title = htmlspecialchars($_POST['title']);
                $price = floatval($_POST['property_price']); // Convert to float
                $bedroom = intval($_POST['bedroom']); // Convert to integer
                $bathroom = intval($_POST['bathroom']); // Convert to integer
                $landsize = intval($_POST['landsize']); // Convert to integer
                $buildingsize = intval($_POST['buildingsize']); // Convert to integer
                $property_type = htmlspecialchars($_POST['property_type']);
                $state = htmlspecialchars($_POST['state']);
                $city = htmlspecialchars($_POST['city']);
                $country = htmlspecialchars($_POST['country']);
                $contact_no = htmlspecialchars($_POST['contact_no']);
                $target_dir = "uploads/";
                $image_name = basename($_FILES["property_image"]["name"]);
                $target_file = $target_dir . time() . "_" . $image_name;
                move_uploaded_file($_FILES["property_image"]["tmp_name"], $target_file);
                $sql = "INSERT INTO property (Title, Price, Bedroom, Bathroom, Landsize, Buildingsize, Property_type, Contact_no, UID, State, City, Country, Image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdiiiisssssss", $title, $price, $bedroom, $bathroom, $landsize, $buildingsize, $property_type, $contact_no, $uid, $state, $city, $country, $target_file);
                $stmt->execute();
                $stmt->close();
                echo "<script>alert('Property listed successfully!'); window.location.href='myprofile.php';</script>";
                break;

            default:
                echo "<p>Invalid form submission.</p>";
                break;
        }
    }

    $propertyCards = "";
    // Display Properties Listed by the User with dynamic filtering
    $query = "SELECT property.*, users.Username FROM property JOIN users ON property.UID = users.UID WHERE property.UID = ?";
    $types = 'i';
    $params = [&$uid];

    if (!empty($filterType)) {
        $query .= " AND property.Property_type = ?";
        $types .= 's';
        $params[] = &$filterType;
    }
    if (!empty($filterState)) {
        $query .= " AND property.State = ?";
        $types .= 's';
        $params[] = &$filterState;
    }
    if (!empty($filterCity)) {
        $query .= " AND property.City = ?";
        $types .= 's';
        $params[] = &$filterCity;
    }

    // Prepare and bind the parameters dynamically
    $stmt = $conn->prepare($query);
    array_unshift($params, $types);
    call_user_func_array([$stmt, 'bind_param'], $params);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user has any properties and generate HTML cards
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
            $propertyCards .= '            <span><i class="fa fa-user"></i> ' . htmlspecialchars($row['Username']) . '</span>';
            $propertyCards .= '            <span><i class="fa fa-calendar"></i> ' . htmlspecialchars($row['Date']) . '</span>';
            $propertyCards .= '        </div>';
            $propertyCards .= '    </div>';
            $propertyCards .= '</div>';
        }
    } else {
        $propertyCards = "<p>No properties found for this user.</p>";
    }


    $query = "SELECT inquiry.*, property.Title, users.Username, users.Email 
    FROM inquiry 
    JOIN property ON inquiry.PID = property.PID 
    JOIN users ON users.UID = property.UID 
    WHERE property.UID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display inquiries in a formatted way
    $inquiriesHTML = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $backgroundStyle = ($row['Name'] == 'Admin') ? 'background-color: #aeaeae;' : '';
            $inquiriesHTML .= '<div class="add-property-form" style="margin-bottom:30px;' . $backgroundStyle . '">';
            $inquiriesHTML .= '    <div class="user-details-inq">';
            $inquiriesHTML .= '        <div class="user-info">';
            $inquiriesHTML .= '            <h3>' . htmlspecialchars($row['Name']) . '</h3>';
            $inquiriesHTML .= '            <p>' . htmlspecialchars($row['Email']) . '</p>';
            $inquiriesHTML .= '        </div>';
            $inquiriesHTML .= '    </div>';
            $inquiriesHTML .= '    <div class="inquiry-content">';
            $inquiriesHTML .= '        "' . htmlspecialchars($row['Text']) . '"';
            $inquiriesHTML .= '    </div>';
            $inquiriesHTML .= '    <div class="inquiry-meta">';
            $inquiriesHTML .= '        ' . htmlspecialchars($row['Time']);
            $inquiriesHTML .= '    </div>';
            $inquiriesHTML .= '    <div class="btn-group-inq">';
            $inquiriesHTML .= '        <button class="btn-inq btn-reply" onclick="toggleReplyForm(' . $row['Inq_ID'] . ', ' . $row['PID'] . ')">Reply</button>';
            $inquiriesHTML .= '    </div>';
            $inquiriesHTML .= '    <div class="reply-form" id="reply-form-' . $row['Inq_ID'] . '" data-pid="' . $row['PID'] . '" style="display:none; margin-top:15px;">';
            $inquiriesHTML .= '        <textarea id="reply-text-' . $row['Inq_ID'] . '" placeholder="Type your reply here..."></textarea>';
            $inquiriesHTML .= '        <button class="btn-inq btn-reply" onclick="sendReply(' . $row['Inq_ID'] . ')">Send</button>';
            $inquiriesHTML .= '    </div>';
            $inquiriesHTML .= '</div>';
        }
    } else {
        $inquiriesHTML = "<p>No inquiries found for your properties.</p>";
    }
} else {
    echo "<p>Please log in to view your profile.</p>";
    exit();
}

// Retrieve Property Types
$type_option = "";
$type_query = "SELECT * FROM property_type";
$result = $conn->query($type_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $type_option .= "<option value='" . htmlspecialchars($row['Name']) . "'>" . htmlspecialchars($row['Name']) . "</option>";
    }
}

// Retrieve States
$state_options = "";
$state_query = "SELECT * FROM state";
$result = $conn->query($state_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $state_options .= "<option value='" . htmlspecialchars($row['State_name']) . "'>" . htmlspecialchars($row['State_name']) . "</option>";
    }
}

// Retrieve Cities
$city_options = "";
$city_query = "SELECT * FROM city";
$result = $conn->query($city_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $city_options .= "<option value='" . htmlspecialchars($row['City_name']) . "'>" . htmlspecialchars($row['City_name']) . "</option>";
    }
}

// Retrieve Countries
$country_options = "";
$country_query = "SELECT * FROM country";
$result = $conn->query($country_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $country_options .= "<option value='" . htmlspecialchars($row['Country_name']) . "'>" . htmlspecialchars($row['Country_name']) . "</option>";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $replyText = isset($_POST['replyText']) ? htmlspecialchars($_POST['replyText']) : null;
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;

    if (!isset($conn)) {
        die("Database connection not found.");
    }

    if ($pid > 0 && $replyText !== null) {
        $query = "INSERT INTO inquiry (PID, Text) VALUES (?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }

        $stmt->bind_param("is", $pid, $replyText);

        if ($stmt->execute()) {
            echo "Reply saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing or invalid data.";
    }
}
// Close the database connection
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | My Profile</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php
    // Include the header
    include 'header.php';
    ?>

    <div class="profile-page">
        <div class="profile-wrapper">
            <div class="user-details">
                <img src="./images//user.png" alt="User Image">
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <p class="user-role">Role: <?php echo htmlspecialchars($role); ?></p>
                <p>User ID: <?php echo htmlspecialchars($uid); ?></p>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                <p>Phone: <?php echo htmlspecialchars($phone); ?></p>
                <p>Address: <?php echo htmlspecialchars($address); ?></p>
            </div>

            <!-- Update Profile Section -->
            <div class="update-profile">
                <h2>Update Profile</h2>
                <form action="myprofile.php" method="POST">
                    <!-- Hidden Field to Identify Form Type -->
                    <input type="hidden" name="form_type" value="update_profile">

                    <div class="input-box">
                        <input type="text" id="name" name="name" placeholder="Enter Name" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="input-box">
                        <input type="email" id="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="input-box">
                        <input type="text" id="phone" name="phone" placeholder="Enter Mobile Number" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>
                    <div class="input-box">
                        <input id="address" name="address" placeholder="Enter Address" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>
                    <div class="input-box">
                        <input id="pwd" type="password" name="pwd" placeholder="Enter New Password">
                    </div>

                    <button type="submit" class="btn-up">Update Profile</button>
                </form>
            </div>

        </div>

        <?php
        // Check if the user is logged in and their role is 'owner'
        if (isset($_SESSION['Role']) && $_SESSION['Role'] == 'owner') {
        ?>
            <div style="margin: 20px; " >
                <h2 style="color: #c69d33;">My Inquiries</h2>
            </div>
            <?php echo $inquiriesHTML; ?>
            <script>
                function toggleReplyForm(inquiryId, propertyId) {
                    var replyForm = document.getElementById('reply-form-' + inquiryId);
                    if (replyForm.style.display === 'none') {
                        replyForm.style.display = 'block';
                        replyForm.setAttribute("data-pid", propertyId);
                    } else {
                        replyForm.style.display = 'none';
                    }
                }

                function sendReply(inquiryId) {
                    var replyForm = document.getElementById('reply-form-' + inquiryId);
                    var replyText = document.getElementById('reply-text-' + inquiryId).value;

                    var propertyId = replyForm.getAttribute("data-pid");

                    if (replyText.trim() === '') {
                        alert('Please enter a reply message.');
                        return;
                    }

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'myprofile.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            alert('Reply sent successfully!');
                            document.getElementById('reply-form-' + inquiryId).style.display = 'none';
                        }
                    };

                    xhr.send('inquiryId=' + inquiryId + '&replyText=' + encodeURIComponent(replyText) + '&pid=' + propertyId);
                }
            </script>

            <!-- Add New Property Form -->
            <div class="add-property-form">
                <h2>List a New Property</h2>
                <form action="myprofile.php" method="POST" enctype="multipart/form-data">
                    <!-- Hidden Field to Identify Form Type -->
                    <input type="hidden" name="form_type" value="list_property">

                    <div class="form-row">
                        <div class="input-box">
                            <input type="text" id="property-title" placeholder="Title" name="title" required>
                        </div>
                        <div class="input-box">
                            <input type="number" id="property-price" placeholder="Price" name="property_price" required>
                        </div>
                    </div>

                    <!-- Bedrooms and Bathrooms -->
                    <div class="form-row">
                        <div class="input-box">
                            <input type="number" id="bedroom" placeholder="Bedroom" name="bedroom" min="1" required>
                        </div>
                        <div class="input-box">
                            <input type="number" id="bathroom" placeholder="Bathroom" name="bathroom" min="1" required>
                        </div>
                    </div>

                    <!-- Land Size and Building Size -->
                    <div class="form-row">
                        <div class="input-box">
                            <input type="number" id="landsize" placeholder="Land Size (perch)" name="landsize" required>
                        </div>
                        <div class="input-box">
                            <input type="number" id="buildingsize" placeholder="Building Size (sq ft)" name="buildingsize" required>
                        </div>
                    </div>

                    <!-- Property Type, State, City, and Country Dropdowns -->
                    <div class="form-row">
                        <div class="input-box">
                            <select id="property-type" name="property_type" required>
                                <option value="" disabled selected>Select Property Type</option>
                                <?php echo $type_option; ?>
                            </select>
                        </div>


                        <div class="input-box">
                            <select id="state" name="state" required>
                                <option value="" disabled selected>Select State</option>
                                <?php echo $state_options; ?>
                            </select>
                        </div>
                        <div class="input-box">
                            <select id="city" name="city" required>
                                <option value="" disabled selected>Select City</option>
                                <?php echo $city_options; ?>
                            </select>
                        </div>
                        <div class="input-box">
                            <select id="country" name="country" required>
                                <option value="" disabled selected>Select Country</option>
                                <?php echo $country_options; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-row">
                        <div class="input-box">
                            <input type="text" id="contact_no" placeholder="Contact Number" name="contact_no" required>
                        </div>
                        <div class="input-box">
                            <input type="file" id="property-image" placeholder="Property Image" name="property_image" accept="image/*" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-up">List Property</button>
                </form>
            </div>


            <!-- Listed Properties Section -->
            <div class="add-property-form" style="margin-top:30px;">
                <h2>My Listed Properties</h2>

                <div class="filter-section">
                    <h2>Search Properties</h2>
                    <form id="property-filter-form" method="GET" action="myprofile.php">
                        <select id="propertyType" name="propertyType">
                            <option value="" disabled selected>Select Property Type</option>
                            <?php echo $type_option; ?>
                        </select>

                        <select id="state" name="state">
                            <option value="" disabled selected>Select State</option>
                            <?php echo $state_options; ?>
                        </select>

                        <select id="city" name="city">
                            <option value="" disabled selected>Select City</option>
                            <?php echo $city_options; ?>
                        </select>

                        <button type="submit">Search</button>
                    </form>

                </div>

                <div class="property-container2">
                    <?php echo $propertyCards; ?>
                </div>
            </div>
        <?php
        }
        ?>

    </div>


    <?php
    // Include the header
    include 'footer.php';
    ?>


</body>

</html>