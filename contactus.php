<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Contact Us</title>
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
    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact US</h2>
            <p>Aut voluptas consequatur unde sed omnis ex placeat quis eos. Aut natus officia corrupti qui autem fugit consectetur quo. Et ipsum eveniet laboriosam voluptas beatae possimus qui ducimus. Et voluptatem deleniti.</p>
        </div>
    </div>

    <div class="contact-form-container">
        <div class="form-section">
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
        <div class="contact-info-section">
            <h4>Say Hello</h4>
            <p>Email: info@primenest.com</p>
            <p>Phone: +54 356 945234</p>
            <h4>Find us in</h4>
            <p>No 41/ A Malwaththa road, Colombo 05</p>
            <h4>Social networks</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81536311032!2d79.81500560504831!3d6.921836877833458!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1727203824651!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>


    <?php
    // Include the header
    include 'footer.php';
    ?>


</body>

</html>