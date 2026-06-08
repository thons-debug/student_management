<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$search = "";
if (isset($_GET['q'])) {
    $search = clean_input($_GET['q']);
    $search = mysqli_real_escape_string($conn, $search);
}

$sql = "SELECT s.*, c.course_name 
        FROM students s 
        LEFT JOIN courses c ON s.course_id = c.id";

if ($search != "") {
    $sql .= " WHERE s.name LIKE '%$search%' 
              OR s.roll_number LIKE '%$search%' 
              OR s.email LIKE '%$search%'";
}

$sql .= " ORDER BY s.id DESC";

$result = mysqli_query($conn, $sql);
?>

<h2>Students Management</h2>

<!-- 🔍 SEARCH + ADD -->
<div class="card" style="margin-bottom:20px;">
    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;align-items:center;">

        <form method="GET" style="display:flex;gap:10px;">
            <input 
                type="text" 
                name="q" 
                value="<?php echo e($search); ?>" 
                placeholder="Search by name / roll / email"
            >
            <button type="submit" class="btn">Search</button>
        </form>

        <a class="btn success" href="add_student.php">
            ➕ Add Student
        </a>

    </div>
</div>

<!-- 📋 TABLE -->
<div class="card">
<table class="table">

<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Roll No</th>
    <th>Course</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Admission</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($result) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo e($row['name']); ?></td>
        <td><?php echo e($row['roll_number']); ?></td>
        <td><?php echo e($row['course_name'] ?? 'N/A'); ?></td>
        <td><?php echo e($row['email'] ?? 'N/A'); ?></td>
        <td><?php echo e($row['phone'] ?? 'N/A'); ?></td>
        <td><?php echo e($row['admission_date'] ?? 'N/A'); ?></td>

        <td>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">

                <a class="btn" href="edit_student.php?id=<?php echo (int)$row['id']; ?>">
                    ✏ Edit
                </a>

                <form method="POST" action="delete_student.php" 
                      onsubmit="return confirm('Delete this student?')">

                    <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">

                    <button class="btn danger" type="submit">
                        🗑 Delete
                    </button>

                </form>

            </div>
        </td>
    </tr>
    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="8" style="text-align:center;padding:20px;">
        🚫 No students found
    </td>
</tr>

<?php endif; ?>

</tbody>
</table>
</div>

<?php include_once "../includes/footer.php"; ?>