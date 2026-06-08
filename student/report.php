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

// 📊 Total Paid
$paid_q = mysqli_query($conn, "
    SELECT SUM(amount) as total 
    FROM fees 
    WHERE student_id=$student_id AND status='paid'
");
$paid = mysqli_fetch_assoc($paid_q)['total'] ?? 0;

// 📊 Total Pending
$pending_q = mysqli_query($conn, "
    SELECT SUM(amount) as total 
    FROM fees 
    WHERE student_id=$student_id AND status='pending'
");
$pending = mysqli_fetch_assoc($pending_q)['total'] ?? 0;

// 📋 Records
$result = mysqli_query($conn, "
    SELECT amount, payment_date, status 
    FROM fees 
    WHERE student_id=$student_id
    ORDER BY payment_date DESC
");
?>

<h2>My Fee Report</h2>

<!-- 🔥 STATS -->
<div class="stats-grid">

    <div class="stat-card stat-green">
        Total Paid
        <h1>₹ <?php echo $paid; ?></h1>
    </div>

    <div class="stat-card stat-orange">
        Pending Fees
        <h1>₹ <?php echo $pending; ?></h1>
    </div>

</div>

<!-- 📋 TABLE -->
<div class="card">
<table class="table">

<thead>
<tr>
    <th>Amount</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td>₹ <?php echo $row['amount']; ?></td>
        <td><?php echo $row['payment_date']; ?></td>

        <td>
            <?php if ($row['status'] === 'paid'): ?>
                <span style="color:green;font-weight:bold;">✔ Paid</span>
            <?php else: ?>
                <span style="color:red;font-weight:bold;">✖ Pending</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="3" style="text-align:center;">
        No fee records found 📭
    </td>
</tr>

<?php endif; ?>

</tbody>

</table>
</div>

<?php include_once "../includes/footer.php"; ?>