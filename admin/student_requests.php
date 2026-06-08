<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

// 🔥 APPROVE / REJECT (POST ONLY)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id > 0) {

        // 🔍 Get user
        $userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
        $user = mysqli_fetch_assoc($userQuery);

        if ($user) {

            if ($action === 'approve') {

                // ✅ Approve user
                mysqli_query($conn, "UPDATE users SET status='approved' WHERE id=$id");

                // 🔍 Check if already exists
                $check = mysqli_query($conn, "SELECT id FROM students WHERE user_id=$id");

                if (mysqli_num_rows($check) == 0) {

                    $name  = mysqli_real_escape_string($conn, $user['username']);
                    $email = mysqli_real_escape_string($conn, $user['email']);

                    // ✅ Insert student
                    mysqli_query($conn, "
                        INSERT INTO students (user_id, name, email)
                        VALUES ($id, '$name', '$email')
                    ");
                }

                set_message("✅ Student approved & added successfully!");

            } elseif ($action === 'reject') {

                mysqli_query($conn, "UPDATE users SET status='rejected' WHERE id=$id");

                set_message("❌ Student rejected!");
            }
        }
    }

    redirect("student_requests.php");
}

// 🔍 FETCH PENDING
$result = mysqli_query($conn, 
    "SELECT * FROM users WHERE role='student' AND status='pending' ORDER BY id DESC"
);
?>

<h2>Student Registration Requests</h2>

<?php display_message(); ?>

<div class="card">

<table class="table">

<thead>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo e($row['username']); ?></td>
        <td><?php echo e($row['email']); ?></td>

        <td>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">

                <!-- ✅ APPROVE -->
                <form method="POST" 
                      onsubmit="return confirm('Approve this student?')">

                    <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                    <input type="hidden" name="action" value="approve">

                    <button class="btn success" type="submit">
                        ✔ Approve
                    </button>
                </form>

                <!-- ❌ REJECT -->
                <form method="POST" 
                      onsubmit="return confirm('Reject this student?')">

                    <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                    <input type="hidden" name="action" value="reject">

                    <button class="btn danger" type="submit">
                        ✖ Reject
                    </button>
                </form>

            </div>
        </td>
    </tr>
    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="4" style="text-align:center;padding:20px;">
        🎉 No pending requests
    </td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php include_once "../includes/footer.php"; ?>