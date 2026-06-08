<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$date = $_POST['date'] ?? $_GET['date'] ?? date("Y-m-d");

// 🔥 SAVE ATTENDANCE
if (isset($_POST['save'])) {

    foreach ($_POST['status'] as $student_id => $status) {

        $student_id = (int)$student_id;
        $status = ($status === "present") ? "present" : "absent";

        $check = mysqli_query($conn, "
            SELECT id FROM attendance 
            WHERE student_id=$student_id AND attendance_date='$date'
        ");

        if (mysqli_num_rows($check) > 0) {

            mysqli_query($conn, "
                UPDATE attendance 
                SET status='$status' 
                WHERE student_id=$student_id AND attendance_date='$date'
            ");

        } else {

            mysqli_query($conn, "
                INSERT INTO attendance (student_id,attendance_date,status)
                VALUES ($student_id,'$date','$status')
            ");
        }
    }

    set_message("✅ Attendance saved successfully!");
    redirect("attendance.php?date=".$date);
}

// 🔥 FETCH STUDENTS
$students = mysqli_query($conn, "
    SELECT id,name,roll_number 
    FROM students 
    ORDER BY roll_number ASC
");

// 🔥 FETCH EXISTING ATTENDANCE
$attendanceData = [];
$res = mysqli_query($conn, "
    SELECT student_id,status 
    FROM attendance 
    WHERE attendance_date='$date'
");

while ($row = mysqli_fetch_assoc($res)) {
    $attendanceData[$row['student_id']] = $row['status'];
}
?>

<h2>Attendance Management</h2>

<div class="card">

<form method="POST">

<label>Select Date</label>
<input type="date" name="date" value="<?php echo $date; ?>">

<br><br>

<table class="table">
<thead>
<tr>
<th>Roll</th>
<th>Name</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($students) > 0): ?>

<?php while($s = mysqli_fetch_assoc($students)): 

$current = $attendanceData[$s['id']] ?? "";

?>

<tr>
<td><?php echo e($s['roll_number']); ?></td>
<td><?php echo e($s['name']); ?></td>

<td>
<select name="status[<?php echo $s['id']; ?>]"
        style="<?php echo ($current === 'present') ? 'background:#d4edda;' : (($current === 'absent') ? 'background:#f8d7da;' : ''); ?>">

<option value="present" <?php echo ($current === "present") ? "selected" : ""; ?>>Present</option>
<option value="absent" <?php echo ($current === "absent") ? "selected" : ""; ?>>Absent</option>

</select>
</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="3" style="text-align:center;padding:20px;">
No students found 👨‍🎓
</td>
</tr>

<?php endif; ?>

</tbody>
</table>

<br>

<button type="submit" name="save" class="btn success">
💾 Save Attendance
</button>

</form>

</div>

<?php include_once "../includes/footer.php"; ?>