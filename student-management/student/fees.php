<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = $_SESSION['user_id'];

// 🔥 Fetch Student Fee Records
$result = mysqli_query($conn,"
SELECT 
    f.*,
    s.name,
    s.roll_number
FROM fees f
JOIN students s ON f.student_id = s.id
WHERE s.user_id = $user_id
ORDER BY f.id DESC
");

// 🔥 Fee Summary
$total_paid = 0;
$total_pending = 0;

$temp = mysqli_query($conn,"
SELECT amount,status
FROM fees f
JOIN students s ON f.student_id=s.id
WHERE s.user_id=$user_id
");

while($fee = mysqli_fetch_assoc($temp)){

    if($fee['status'] == "paid"){
        $total_paid += $fee['amount'];
    }else{
        $total_pending += $fee['amount'];
    }
}
?>

<div class="page-header">

    <h2>💰 My Fee Status</h2>

    <p>
        View your fee payment details and status
    </p>

</div>

<!-- 🔥 SUMMARY CARDS -->
<div class="stats-grid">

    <div class="stat-card stat-green">

        Total Paid

        <h1>
            ₹ <?php echo number_format($total_paid,2); ?>
        </h1>

    </div>

    <div class="stat-card stat-orange">

        Pending Amount

        <h1>
            ₹ <?php echo number_format($total_pending,2); ?>
        </h1>

    </div>

</div>

<!-- 🔥 FEE TABLE -->
<div class="card">

<?php if(mysqli_num_rows($result) > 0): ?>

<table class="table">

<thead>

<tr>

<th>#</th>

<th>Amount</th>

<th>Payment Date</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php 
$count = 1;
while($row = mysqli_fetch_assoc($result)): 
?>

<tr>

<td>
<?php echo $count++; ?>
</td>

<td>

<strong>
₹ <?php echo number_format($row['amount'],2); ?>
</strong>

</td>

<td>

<?php if($row['payment_date']): ?>

    <?php echo date(
        "d M Y",
        strtotime($row['payment_date'])
    ); ?>

<?php else: ?>

    <span style="color:#ff9800;">
        Pending
    </span>

<?php endif; ?>

</td>

<td>

<?php if($row['status']=="paid"): ?>

<span class="badge-success">
    ✅ Paid
</span>

<?php elseif($row['status']=="pending"): ?>

<span class="badge-warning">
    ⏳ Pending
</span>

<?php else: ?>

<span class="badge-danger">
    ❌ Unpaid
</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<?php else: ?>

<!-- ❌ EMPTY STATE -->
<div class="empty-state">

    <div style="
    font-size:70px;
    margin-bottom:15px;
    ">
        💳
    </div>

    <h2>No Fee Records Found</h2>

    <p style="
    margin-top:10px;
    color:#666;
    ">

        Fee details are not available currently.

    </p>

</div>

<?php endif; ?>

</div>

<!-- 🔥 PAYMENT NOTE -->
<div class="card">

<h3>📌 Important Note</h3>

<p style="
margin-top:12px;
line-height:1.9;
color:#555;
">

Please ensure that all pending fees are paid
before the due date to avoid academic restrictions
or late payment penalties.

</p>

</div>

<?php include_once "../includes/footer.php"; ?>