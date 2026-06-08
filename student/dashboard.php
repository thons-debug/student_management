<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = (int)$_SESSION['user_id'];

// 🔥 Fetch Student Details
$result = mysqli_query($conn,"
SELECT 
    s.*,
    c.course_name,
    c.course_code,
    c.duration
FROM students s
LEFT JOIN courses c ON s.course_id = c.id
WHERE s.user_id = $user_id
");

$student = mysqli_fetch_assoc($result);
?>

<div class="page-header">

    <h2>🎓 Student Dashboard</h2>

    <p>
        Welcome to your academic dashboard
    </p>

</div>

<?php if($student): ?>

<?php

// =======================================
// 📅 ATTENDANCE STATS
// =======================================

// Total Attendance
$attendance = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM attendance
    WHERE student_id = {$student['id']}
")
)['total'] ?? 0;

// Present Days
$present = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM attendance
    WHERE student_id = {$student['id']}
    AND status='present'
")
)['total'] ?? 0;

// Absent Days
$absent = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM attendance
    WHERE student_id = {$student['id']}
    AND status='absent'
")
)['total'] ?? 0;

// Attendance Percentage
$attendance_percentage = 0;

if($attendance > 0){
    $attendance_percentage =
    ($present / $attendance) * 100;
}

// =======================================
// 💰 FEES
// =======================================

// Paid Fees
$fees_paid = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT SUM(amount) as total
    FROM fees
    WHERE student_id = {$student['id']}
    AND status='paid'
")
)['total'] ?? 0;

// Pending Fees
$fees_pending = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT SUM(amount) as total
    FROM fees
    WHERE student_id = {$student['id']}
    AND status!='paid'
")
)['total'] ?? 0;

?>

<!-- =======================================
     🔥 PROFILE OVERVIEW
======================================= -->

<div class="card profile-summary-card">

    <div class="profile-summary">

        <!-- PROFILE AVATAR -->
        <div class="profile-avatar">

            <?php
            echo strtoupper(
                substr(
                    $student['name'],
                    0,
                    1
                )
            );
            ?>

        </div>

        <!-- PROFILE DETAILS -->
        <div>

            <h2>
                Welcome,
                <?php echo e($student['name']); ?> 👋
            </h2>

            <p class="profile-subtitle">

                Roll Number:
                <strong>
                    <?php echo e($student['roll_number']); ?>
                </strong>

            </p>

            <p class="profile-subtitle">

                Course:
                <strong>

                    <?php
                    echo e(
                        $student['course_name']
                        ?? 'Not Assigned'
                    );
                    ?>

                </strong>

            </p>

        </div>

    </div>

</div>

<!-- =======================================
     📊 DASHBOARD STATS
======================================= -->

<div class="stats-grid">

    <div class="stat-card stat-blue">

        Total Attendance

        <h1>
            <?php echo $attendance; ?>
        </h1>

    </div>

    <div class="stat-card stat-green">

        Present Days

        <h1>
            <?php echo $present; ?>
        </h1>

    </div>

    <div class="stat-card stat-orange">

        Attendance %

        <h1>
            <?php echo number_format($attendance_percentage,1); ?>%
        </h1>

    </div>

    <div class="stat-card stat-purple">

        Fees Paid

        <h1>
            ₹ <?php echo number_format($fees_paid,2); ?>
        </h1>

    </div>

</div>

<!-- =======================================
     🚀 QUICK ACTIONS
======================================= -->

<div class="card">

    <h3>⚡ Quick Actions</h3>

    <div class="quick-actions">

        <a href="profile.php" class="action-card">

            👤
            <h4>My Profile</h4>

        </a>

        <a href="attendance.php" class="action-card">

            📅
            <h4>Attendance</h4>

        </a>

        <a href="fees.php" class="action-card">

            💰
            <h4>Fee Status</h4>

        </a>

        <a href="courses.php" class="action-card">

            📚
            <h4>My Course</h4>

        </a>

        <a href="results.php" class="action-card">

            📊
            <h4>Results</h4>

        </a>

        <a href="notices.php" class="action-card">

            📢
            <h4>Notices</h4>

        </a>

    </div>

</div>

<!-- =======================================
     📘 COURSE DETAILS
======================================= -->

<div class="card">

    <h3>📘 Course Information</h3>

    <div class="course-grid">

        <div class="info-box">

            <span class="info-title">
                Course Name
            </span>

            <h3>
                <?php
                echo e(
                    $student['course_name']
                    ?? 'N/A'
                );
                ?>
            </h3>

        </div>

        <div class="info-box">

            <span class="info-title">
                Course Code
            </span>

            <h3>
                <?php
                echo e(
                    $student['course_code']
                    ?? 'N/A'
                );
                ?>
            </h3>

        </div>

        <div class="info-box">

            <span class="info-title">
                Duration
            </span>

            <h3>
                <?php
                echo e(
                    $student['duration']
                    ?? 'N/A'
                );
                ?>
            </h3>

        </div>

    </div>

</div>

<!-- =======================================
     📢 ANNOUNCEMENTS
======================================= -->

<div class="card">

    <h3>📢 Latest Announcements</h3>

    <ul style="
    margin-top:15px;
    line-height:2;
    padding-left:20px;
    color:#555;
    ">

        <li>
            Semester exams begin next month.
        </li>

        <li>
            Attendance should be above 75%.
        </li>

        <li>
            Project submission deadline this Friday.
        </li>

        <li>
            Fee payment due before 25th.
        </li>

    </ul>

</div>

<!-- =======================================
     💡 PERFORMANCE MESSAGE
======================================= -->

<div class="card">

    <h3>🚀 Performance Insight</h3>

    <p style="
    margin-top:15px;
    line-height:1.9;
    color:#555;
    ">

        Your current attendance percentage is
        <strong>
            <?php echo number_format($attendance_percentage,1); ?>%
        </strong>.

        Continue maintaining good attendance and
        complete all academic activities on time
        to improve your overall performance.

    </p>

</div>

<?php else: ?>

<!-- =======================================
     ❌ EMPTY STATE
======================================= -->

<div class="card empty-state">

    <div style="
    font-size:70px;
    margin-bottom:20px;
    ">
        🚧
    </div>

    <h2>Profile Setup Pending</h2>

    <p style="
    margin-top:12px;
    color:#666;
    line-height:1.8;
    ">

        Your account exists but is not yet linked
        with student records.

    </p>

    <p style="
    margin-top:10px;
    color:#666;
    ">

        Please contact administrator.

    </p>

</div>

<?php endif; ?>

<?php include_once "../includes/footer.php"; ?>