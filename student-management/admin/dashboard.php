<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

// 📊 STATS
$students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'] ?? 0;

$courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM courses"))['total'] ?? 0;

$fees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM fees WHERE status='paid'"))['total'] ?? 0;

$today = date("Y-m-d");
$attendance = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM attendance WHERE attendance_date='$today'")
)['total'] ?? 0;

$pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='student' AND status='pending'")
)['total'] ?? 0;

// 🔥 Recent Students
$recentStudents = mysqli_query($conn, "
    SELECT name, roll_number, admission_date 
    FROM students 
    ORDER BY id DESC 
    LIMIT 5
");
?>

<h2>Admin Dashboard</h2>

<!-- 🔔 ALERT -->
<?php if($pending > 0): ?>
<div class="card" style="border-left:5px solid #ff4b2b;">
    <strong>🔔 You have <?php echo $pending; ?> pending student requests!</strong>
    <br>
    <a class="btn danger" href="student_requests.php">Review Now</a>
</div>
<?php endif; ?>

<!-- 🔥 STATS -->
<div class="stats-grid">

    <div class="stat-card stat-blue">
        Total Students
        <h1><?php echo $students; ?></h1>
    </div>

    <div class="stat-card stat-purple">
        Total Courses
        <h1><?php echo $courses; ?></h1>
    </div>

    <div class="stat-card stat-green">
        Fees Collected
        <h1>₹ <?php echo $fees ?: 0; ?></h1>
    </div>

    <div class="stat-card stat-orange">
        Today's Attendance
        <h1><?php echo $attendance; ?></h1>
    </div>

    <div class="stat-card" style="background:linear-gradient(135deg,#ff416c,#ff4b2b);">
        Pending Requests
        <h1><?php echo $pending; ?></h1>
    </div>

</div>

<!-- ⚡ QUICK ACTIONS -->
<div class="card">
    <h3>⚡ Quick Actions</h3>

    <div class="quick-actions">

        <a href="add_student.php" class="action-card">
            ➕ Add Student
        </a>

        <a href="student_requests.php" class="action-card">
            🔔 View Requests
        </a>

        <a href="attendance.php" class="action-card">
            📅 Mark Attendance
        </a>

        <a href="fees.php" class="action-card">
            💰 Manage Fees
        </a>

        <a href="reports.php" class="action-card">
            📊 Reports
        </a>

    </div>
</div>

<!-- 🆕 RECENT STUDENTS -->
<div class="card">
    <h3>🆕 Recently Added Students</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Admission Date</th>
            </tr>
        </thead>
        <tbody>

        <?php if(mysqli_num_rows($recentStudents) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($recentStudents)): ?>
                <tr>
                    <td><?php echo e($row['name']); ?></td>
                    <td><?php echo e($row['roll_number']); ?></td>
                    <td><?php echo e($row['admission_date'] ?? "-"); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center;">No data available</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php include_once "../includes/footer.php"; ?>