<?php
session_start();
require 'db.php'; // Include your database connection file

// Handle signup
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $usertype = $_POST['user-type'];

    // Check if username already exists in both tables
    $checkAdminQuery = "SELECT * FROM client WHERE name = ?";
    $checkUserQuery = "SELECT * FROM service_provider WHERE username = ?";

    // Check in admins table
    $stmt = $conn->prepare($checkAdminQuery);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    // Check in users table
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($adminResult->num_rows > 0 || $userResult->num_rows > 0) {
        echo "<script>alert('Username already exists.');</script>";
    } else {
        // Determine which table to insert into
        if ($usertype === 'customer') {
            // Insert into admins table
            $insertQuery = "INSERT INTO client (name,email,phone_number,adress,date_of_birth,gender) VALUES (?, ?,?,?,?,?)";
        } else {
            // Insert into service provider table
            $insertQuery = "INSERT INTO service_provider (username, password,role) VALUES (?, ?,?)";
        }

        // Prepare and execute the insert statement
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $username, $role, $password, $name, $email, $phone_number, $adress, $date_of_birth, $gende);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now log in.'); window.location.href='login.hml';</script>";
        } else {
            echo "<script>alert('Error during signup.');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up - Glam Salon</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>

    <div class="signup-container">
        <h1>Sign Up</h1>
        <form action="signup.php" method="post" class="signup-form">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="user-type">Register as:</label>
                <select id="user-type" name="user-type" required>
                    <option value="customer">Customer</option>
                    <option value="salonist">Salonist</option>
                </select>
            </div>
            <button type="submit" class="btn signup-btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.html">Login</a></p>
    </div>

</body>

</html>