<?php
include_once "../config/db.php";
include_once "../includes/functions.php";

// Already logged in → redirect
if (is_logged_in()) {
    redirect("../index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = clean_input($_POST['username'] ?? "");
    $email    = clean_input($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        set_message("All fields are required!");
        redirect("register.php");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_message("Invalid email format!");
        redirect("register.php");
    }

    if (strlen($password) < 6) {
        set_message("Password must be at least 6 characters!");
        redirect("register.php");
    }

    // Escape
    $username = mysqli_real_escape_string($conn, $username);
    $email    = mysqli_real_escape_string($conn, $email);

    // 🔥 SINGLE CHECK (better)
    $check = mysqli_query($conn, 
        "SELECT id FROM users WHERE username='$username' OR email='$email'"
    );

    if (mysqli_num_rows($check) > 0) {
        set_message("Username or Email already exists!");
        redirect("register.php");
    }

    // Hash password
    $hash = hash_password($password);

    // Insert user (student, pending)
    $insert = mysqli_query($conn, "
        INSERT INTO users (username,email,password,role,status) 
        VALUES ('$username','$email','$hash','student','pending')
    ");

    if ($insert) {
        set_message("Registration submitted! Wait for admin approval.");
        redirect("login.php");
    } else {
        set_message("Something went wrong. Try again!");
        redirect("register.php");
    }
}
?>

<?php include_once "../includes/header.php"; ?>

<div class="card" style="max-width:420px;margin:60px auto;">
    <h2 style="text-align:center;">Student Registration</h2>

    <?php display_message(); ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" style="width:100%;margin-top:10px;">
            Register
        </button>

    </form>

    <p style="text-align:center;margin-top:15px;">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>

<?php include_once "../includes/footer.php"; ?>