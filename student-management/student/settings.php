<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = $_SESSION['user_id'];

// 🔥 Fetch Student Info
$result = mysqli_query($conn,"
SELECT 
    s.name,
    s.roll_number,
    s.email,
    u.username
FROM students s
JOIN users u ON s.user_id=u.id
WHERE s.user_id=$user_id
");

$student = mysqli_fetch_assoc($result);
?>

<div class="page-header">

    <h2>⚙ Account Settings</h2>

    <p>
        Manage your account and security settings
    </p>

</div>

<!-- 🔥 PROFILE SUMMARY -->
<div class="card profile-summary-card">

    <div class="profile-summary">

        <div class="profile-avatar">

            <?php
            echo strtoupper(
                substr(
                    $student['name'] ?? 'S',
                    0,
                    1
                )
            );
            ?>

        </div>

        <div>

            <h2>
                <?php echo e($student['name']); ?>
            </h2>

            <p class="profile-subtitle">

                Roll Number:
                <?php echo e($student['roll_number']); ?>

            </p>

            <p class="profile-subtitle">

                Username:
                <?php echo e($student['username']); ?>

            </p>

        </div>

    </div>

</div>

<!-- 🔥 SETTINGS OPTIONS -->
<div class="settings-grid">

    <!-- CHANGE PASSWORD -->
    <div class="card settings-card">

        <div class="settings-icon password-icon">
            🔐
        </div>

        <h3>Change Password</h3>

        <p>

            Update your account password securely
            to protect your account.

        </p>

        <a
        href="change_password.php"
        class="btn">

            Update Password

        </a>

    </div>

    <!-- PROFILE -->
    <div class="card settings-card">

        <div class="settings-icon profile-icon">
            👤
        </div>

        <h3>Profile Settings</h3>

        <p>

            View and manage your personal
            profile information.

        </p>

        <a
        href="profile.php"
        class="btn secondary-btn">

            View Profile

        </a>

    </div>

    <!-- ATTENDANCE -->
    <div class="card settings-card">

        <div class="settings-icon attendance-icon">
            📅
        </div>

        <h3>Attendance</h3>

        <p>

            Check your attendance records
            and academic eligibility.

        </p>

        <a
        href="attendance.php"
        class="btn secondary-btn">

            View Attendance

        </a>

    </div>

</div>

<!-- 🔥 SECURITY INFO -->
<div class="card">

    <h3>🛡 Security Recommendations</h3>

    <ul style="
    margin-top:15px;
    line-height:2;
    padding-left:20px;
    color:#555;
    ">

        <li>Use strong passwords with numbers and symbols.</li>

        <li>Do not share your login credentials.</li>

        <li>Update your password regularly.</li>

        <li>Logout after using public systems.</li>

    </ul>

</div>

<?php include_once "../includes/footer.php"; ?>