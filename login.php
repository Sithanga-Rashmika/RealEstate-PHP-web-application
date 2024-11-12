<?php
session_start();

// Include the database connection file
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user details based on the email
    $sql = "SELECT UID, Username, MobileNumber, Email, Role, Password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password using `password_verify()`
        if (password_verify($password, $row['Password'])) {
            // Save user details in session except the password
            $_SESSION['UID'] = $row['UID'];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['MobileNumber'] = $row['MobileNumber'];
            $_SESSION['Address'] = $row['Address'];
            $_SESSION['Email'] = $row['Email'];
            $_SESSION['Role'] = $row['Role'];

            // Redirect based on the user role
            if ($row['Role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            // If the password is incorrect
            echo "<script>alert('Invalid Password! Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        // If no user found with the provided email
        echo "<script>alert('No user found with this email! Please register first.'); window.location.href='register.php';</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRIMENEST REALITY | Login</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="display: flex; align-items:center; justify-content:center;">
    <div class="form-wrapper">
        <div class="login-box">
            <h2>Login</h2>
            <form action="" method="post">
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Enter your email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Enter your password</label>
                </div>
                <div class="forgot">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>Don't have an account? <a href="register.php">Signup</a></p>
            </form>
        </div>
    </div>
</body>

</html>
