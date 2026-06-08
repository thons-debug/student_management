<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = (int)$_SESSION['user_id'];

// 🔍 Get student id
$student = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM students WHERE user_id=$user_id")
);

if (!$student) {
    echo "<div class='card'><p>Profile not linked yet.</p></div>";
    include_once "../includes/footer.php";
    exit();
}

$student_id = $student['id'];

// 📊 Stats
$total_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM attendance WHERE student_id=$student_id");
$total = mysqli_fetch_assoc($total_q)['total'];

$present_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM attendance WHERE student_id=$student_id AND status='present'");
$present = mysqli_fetch_assoc($present_q)['total'];

$absent = $total - $present;
$percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

// 📋 Records
$result = mysqli_query($conn, "
    SELECT attendance_date,status 
    FROM attendance 
    WHERE student_id=$student_id 
    ORDER BY attendance_date DESC
");
?>

<h2>My Attendance</h2>

<!-- 🔥 STATS -->
<div class="stats-grid">

    <div class="stat-card stat-blue">
        Total Days
        <h1><?php echo $total; ?></h1>
    </div>

    <div class="stat-card stat-green">
        Present
        <h1><?php echo $present; ?></h1>
    </div>

    <div class="stat-card stat-orange">
        Absent
        <h1><?php echo $absent; ?></h1>
    </div>

    <div class="stat-card stat-purple">
        Attendance %
        <h1><?php echo $percentage; ?>%</h1>
    </div>

</div>

<!-- 📋 TABLE -->
<div class="card">
<table class="table">
<thead>
<tr>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['attendance_date']; ?></td>

        <td>
            <?php if ($row['status'] === 'present'): ?>
                <span style="color:green;font-weight:bold;">✔ Present</span>
            <?php else: ?>
                <span style="color:red;font-weight:bold;">✖ Absent</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="2" style="text-align:center;">
        No attendance records found 📭
    </td>
</tr>

<?php endif; ?>

</tbody>
</table>
</div>

<?php include_once "../includes/footer.php"; ?>