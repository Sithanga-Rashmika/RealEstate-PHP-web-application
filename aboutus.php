<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | About Us</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    // Include the database connection file
    include 'db_connect.php';
    ?>
    <?php
    // Include the header
    include 'header.php';
    ?>
    <div class="about-us-container">
        <div class="about-us-image">
            <img src="http://localhost/project/images/abt.jpeg" alt="About Us Image">
        </div>
        <div class="about-us-content">
            <h2>Who We Are</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <h3>Why Choose Us?</h3>
            <div class="why-choose-us">
                <ul>
                    <li>Years of Experience</li>
                    <li>Fully Insured</li>
                    <li>Cost Control Experts</li>
                    <li>100% Satisfaction Guarantee</li>
                </ul>
                <ul>
                    <li>Free Consultation</li>
                    <li>Satisfied Customers</li>
                    <li>Project Management</li>
                    <li>Affordable Pricing</li>
                </ul>
            </div>
        </div>
    </div>


    <?php
    // Include the header
    include 'footer.php';
    ?>


</body>

</html>