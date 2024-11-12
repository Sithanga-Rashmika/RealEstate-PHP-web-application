<?php
// Start PHP session
session_start();

// Include the database connection file
include 'db_connect.php';

// Registration logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['fullname'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $pass = $_POST['password'];

    // Secure password storage
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please use a different email.');</script>";
    } else {
        // Insert new user into the `users` table
        $sql = "INSERT INTO users (Username, MobileNumber,Address, Email, Password, Role) VALUES ('$user', '$contact', '$address', '$email', '$hashedPassword', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Something went wrong..!'); window.location.href='register.php';</script>";
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
    <title>PRIMENEST REALITY | Register</title>
    <link rel="icon" href="./images/log2.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="display: flex; align-items:center; justify-content:center;">
    <div class="form-wrapper">
        <div class="login-box">
            <h2 class="hello">Signup</h2>
            <form action="" method="post">
                <div class="input-box">
                    <input type="text" name="fullname" required>
                    <label>Enter your Fullname</label>
                </div>
                <div class="input-box">
                    <input type="text" name="contact" required>
                    <label>Enter your Contact Number</label>
                </div>

                <div class="input-box">
                    <input type="text" name="address" required>
                    <label>Enter your Address</label>
                </div>
                <div class="input-box">
                    <div class="custom-select-wrapper">
                        <select name="role" required>
                            <option value="" disabled selected></option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                            <option value="owner">Owner</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>
                    <label>Select Role</label>
                </div>
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Enter your email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Enter your password</label>
                </div>
                <button type="submit" class="btn">Signup</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>

</html>
