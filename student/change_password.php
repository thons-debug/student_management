<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = $_SESSION['user_id'];

// 🔥 Fetch current user
$userQuery = mysqli_query($conn,"
SELECT * FROM users
WHERE id=$user_id
");

$user = mysqli_fetch_assoc($userQuery);

// 🔥 Update Password
if($_SERVER['REQUEST_METHOD'] == "POST"){

    $current_password = $_POST['current_password'] ?? "";
    $new_password     = $_POST['new_password'] ?? "";
    $confirm_password = $_POST['confirm_password'] ?? "";

    // Empty validation
    if(
        $current_password == "" ||
        $new_password == "" ||
        $confirm_password == ""
    ){
        set_message("All fields are required!","danger");
        redirect("change_password.php");
    }

    // Verify current password
    if(!verify_password($current_password, $user['password'])){

        set_message("Current password is incorrect!","danger");
        redirect("change_password.php");
    }

    // Password length
    if(strlen($new_password) < 6){

        set_message(
            "Password must be at least 6 characters!",
            "danger"
        );

        redirect("change_password.php");
    }

    // Confirm password
    if($new_password !== $confirm_password){

        set_message(
            "New password and confirm password do not match!",
            "danger"
        );

        redirect("change_password.php");
    }

    // Prevent same password
    if(verify_password($new_password, $user['password'])){

        set_message(
            "New password cannot be same as old password!",
            "danger"
        );

        redirect("change_password.php");
    }

    // Hash password
    $hash = hash_password($new_password);

    // Update
    mysqli_query($conn,"
    UPDATE users
    SET password='$hash'
    WHERE id=$user_id
    ");

    set_message(
        "Password updated successfully!",
        "success"
    );

    redirect("settings.php");
}
?>

<div class="page-header">
    <h2>🔐 Change Password</h2>
    <p>Update your account password securely</p>
</div>

<div class="card form-card">

<form method="POST">

    <div class="form-group">

        <label>Current Password</label>

        <input
        type="password"
        name="current_password"
        placeholder="Enter current password"
        required>

    </div>

    <div class="form-group">

        <label>New Password</label>

        <input
        type="password"
        name="new_password"
        placeholder="Enter new password"
        required>

    </div>

    <div class="form-group">

        <label>Confirm Password</label>

        <input
        type="password"
        name="confirm_password"
        placeholder="Confirm new password"
        required>

    </div>

    <div style="
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-top:20px;
    ">

        <button type="submit" class="btn">
            🔒 Update Password
        </button>

        <a href="settings.php" class="btn secondary-btn">
            ← Back
        </a>

    </div>

</form>

</div>

<div class="card">

<h3>🛡 Password Tips</h3>

<ul style="
line-height:2;
padding-left:20px;
">

<li>Use at least 6 characters</li>

<li>Include numbers and special characters</li>

<li>Do not share your password with anyone</li>

<li>Change password regularly for security</li>

</ul>

</div>

<?php include_once "../includes/footer.php"; ?>