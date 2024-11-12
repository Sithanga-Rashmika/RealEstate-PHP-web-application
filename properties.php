<?php
session_start();
include 'db_connect.php';

// Get filters from GET parameters
$filterType = isset($_GET['propertyType']) ? htmlspecialchars($_GET['propertyType']) : '';
$filterState = isset($_GET['state']) ? htmlspecialchars($_GET['state']) : '';
$filterCity = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '';

$propertyCards = "";

// Build the query for displaying properties with dynamic filtering
$query = "SELECT property.*, users.Username 
          FROM property 
          JOIN users ON property.UID = users.UID 
          WHERE 1=1";
$types = '';
$params = [];

// Add conditions based on the filters
if (!empty($filterType)) {
    $query .= " AND Property_type = ?";
    $types .= 's';
    $params[] = &$filterType;
}
if (!empty($filterState)) {
    $query .= " AND State = ?";
    $types .= 's';
    $params[] = &$filterState;
}
if (!empty($filterCity)) {
    $query .= " AND City = ?";
    $types .= 's';
    $params[] = &$filterCity;
}

// Prepare and bind the parameters dynamically if filters are provided
$stmt = $conn->prepare($query);
if (!empty($types)) {
    array_unshift($params, $types);
    call_user_func_array([$stmt, 'bind_param'], $params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if any properties exist and generate HTML cards
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
        
        $propertyCards .= '        <div class="inquiry-btn-container">';
        $propertyCards .= '            <button class="inquiry-btn" onclick="navigateToInquiry(' . $row['PID'] . ')">Inquiry</button>';
        $propertyCards .= '        </div>';
        
        $propertyCards .= '    </div>';
        $propertyCards .= '</div>';
    }
    
} else {
    $propertyCards = "<p>No properties found.</p>";
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

// Close the statement and database connection
$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Property</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php
    // Include the header
    include 'header.php';
    ?>


    <div class="filter-section">
        <h2>Search Properties</h2>
        <form id="property-filter-form" method="GET" action="properties.php">
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

    <div class="property-container">
        <?php echo $propertyCards; ?>
    </div>

    <?php
    // Include the header
    include 'footer.php';
    ?>

<script>
    function navigateToInquiry(propertyID) {
        window.location.href = 'inquiry.php?propertyID=' + propertyID;
    }
</script>


</body>

</html>