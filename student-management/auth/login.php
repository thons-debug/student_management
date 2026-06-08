<?php
include_once "../config/db.php";
include_once "../includes/functions.php";

// Already logged in → redirect
if (is_logged_in()) {
    redirect("../index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = clean_input($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";

    if (empty($username) || empty($password)) {
        set_message("All fields are required!");
        redirect("login.php");
    }

    $username = mysqli_real_escape_string($conn, $username);

    $result = mysqli_query($conn, 
        "SELECT * FROM users WHERE username='$username' LIMIT 1"
    );

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        if (verify_password($password, $user['password'])) {

            // 🔥 STUDENT APPROVAL CHECK
            if ($user['role'] === "student") {

                if ($user['status'] === "pending") {
                    set_message("Your account is waiting for admin approval.");
                    redirect("login.php");
                }

                if ($user['status'] === "rejected") {
                    set_message("Your request was rejected by admin.");
                    redirect("login.php");
                }
            }

            // 🔒 Reset session
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = strtolower($user['role']);

            set_message("Login successful!");

            // Redirect
            if ($_SESSION['role'] === "admin") {
                redirect("../admin/dashboard.php");
            } 
            elseif ($_SESSION['role'] === "student") {
                redirect("../student/dashboard.php");
            } 
            else {
                set_message("Invalid role assigned.");
                redirect("login.php");
            }

        } else {
            set_message("Invalid password!");
            redirect("login.php");
        }

    } else {
        set_message("User not found!");
        redirect("login.php");
    }
}
?>

<?php include_once "../includes/header.php"; ?>

<div class="card" style="max-width:420px;margin:60px auto;">

    <h2 style="text-align:center;margin-bottom:20px;">Login</h2>

    <?php display_message(); ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" style="width:100%;margin-top:10px;">
            Login
        </button>

    </form>

    <p style="text-align:center;margin-top:15px;">
        Don’t have an account? 
        <a href="register.php">Register</a>
    </p>

</div>

<?php include_once "../includes/footer.php"; ?>