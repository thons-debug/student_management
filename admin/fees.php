<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

// 🔥 ADD FEE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_id   = (int)($_POST['student_id'] ?? 0);
    $amount       = (float)($_POST['amount'] ?? 0);
    $status       = $_POST['status'] ?? "pending";
    $payment_date = $_POST['payment_date'] ?: NULL;

    if ($student_id <= 0 || $amount <= 0) {
        set_message("Invalid data!");
        redirect("fees.php");
    }

    mysqli_query($conn, "
        INSERT INTO fees (student_id, amount, payment_date, status) 
        VALUES (
            $student_id,
            $amount,
            " . ($payment_date ? "'$payment_date'" : "NULL") . ",
            '$status'
        )
    ");

    set_message("✅ Fee record added!");
    redirect("fees.php");
}

// 📊 TOTALS
$total_paid = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(amount) as total FROM fees WHERE status='paid'")
)['total'] ?? 0;

$total_pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(amount) as total FROM fees WHERE status='pending'")
)['total'] ?? 0;

// 👨‍🎓 STUDENTS
$students = mysqli_query($conn, 
    "SELECT id,name,roll_number FROM students ORDER BY roll_number ASC"
);

// 📋 FEES
$fees = mysqli_query($conn, "
    SELECT f.*, s.name, s.roll_number 
    FROM fees f 
    JOIN students s ON f.student_id=s.id
    ORDER BY f.id DESC
");
?>

<h2>Fees Management</h2>

<!-- 🔥 SUMMARY -->
<div class="stats-grid">

    <div class="stat-card stat-green">
        Total Collected
        <h1>₹ <?php echo $total_paid; ?></h1>
    </div>

    <div class="stat-card stat-orange">
        Pending Fees
        <h1>₹ <?php echo $total_pending; ?></h1>
    </div>

</div>

<!-- ➕ ADD FORM -->
<div class="card">

<h3>Add Fee</h3>

<form method="POST">

<label>Student</label>
<select name="student_id" required>
<option value="">-- Select Student --</option>

<?php while($s = mysqli_fetch_assoc($students)): ?>
<option value="<?php echo $s['id']; ?>">
<?php echo $s['roll_number']." - ".$s['name']; ?>
</option>
<?php endwhile; ?>

</select>

<label>Amount</label>
<input type="number" step="0.01" name="amount" required>

<label>Payment Date</label>
<input type="date" name="payment_date">

<label>Status</label>
<select name="status">
<option value="paid">Paid</option>
<option value="pending">Pending</option>
<option value="unpaid">Unpaid</option>
</select>

<button type="submit">Add Fee</button>

</form>

</div>

<!-- 📋 RECORDS -->
<div class="card">

<h3>All Fee Records</h3>

<table class="table">

<thead>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($fees) > 0): ?>

    <?php while($f = mysqli_fetch_assoc($fees)): ?>
    <tr>

        <td><?php echo (int)$f['id']; ?></td>

        <td>
            <?php echo e($f['roll_number']." - ".$f['name']); ?>
        </td>

        <td>₹ <?php echo $f['amount']; ?></td>

        <td><?php echo $f['payment_date'] ?? '-'; ?></td>

        <td>
            <?php if($f['status'] === 'paid'): ?>
                <span style="color:green;font-weight:bold;">✔ Paid</span>

            <?php elseif($f['status'] === 'pending'): ?>
                <span style="color:orange;font-weight:bold;">⏳ Pending</span>

            <?php else: ?>
                <span style="color:red;font-weight:bold;">✖ Unpaid</span>
            <?php endif; ?>
        </td>

    </tr>
    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="5" style="text-align:center;">
        No fee records found 📭
    </td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php include_once "../includes/footer.php"; ?>