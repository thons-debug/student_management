<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

// 🔥 ADD COURSE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = clean_input($_POST['course_name'] ?? "");
    $code = clean_input($_POST['course_code'] ?? "");
    $duration = clean_input($_POST['duration'] ?? "");

    if ($name === "" || $code === "") {
        set_message("⚠ Course name & code required!");
        redirect("courses.php");
    }

    // 🔍 Check duplicate
    $check = mysqli_query($conn, "SELECT id FROM courses WHERE course_code='$code'");
    if (mysqli_num_rows($check) > 0) {
        set_message("⚠ Course code already exists!");
        redirect("courses.php");
    }

    mysqli_query($conn, "
        INSERT INTO courses (course_name,course_code,duration)
        VALUES ('$name','$code','$duration')
    ");

    set_message("✅ Course added successfully!");
    redirect("courses.php");
}

// 🔥 DELETE COURSE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query($conn, "DELETE FROM courses WHERE id=$id");

    set_message("❌ Course deleted!");
    redirect("courses.php");
}

// 📚 FETCH COURSES
$result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>

<h2>Course Management</h2>

<!-- ➕ ADD FORM -->
<div class="card">
    <h3>Add New Course</h3>

    <form method="POST">

        <label>Course Name *</label>
        <input name="course_name" required>

        <label>Course Code *</label>
        <input name="course_code" required>

        <label>Duration</label>
        <input name="duration" placeholder="6 months / 1 year">

        <button type="submit" class="btn success">Add Course</button>

    </form>
</div>

<!-- 📋 LIST -->
<div class="card">
    <h3>All Courses</h3>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>

        <?php if(mysqli_num_rows($result) > 0): ?>

            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo e($row['course_name']); ?></td>
                    <td><?php echo e($row['course_code']); ?></td>
                    <td><?php echo e($row['duration']); ?></td>

                    <td>
                        <a class="btn danger"
                           onclick="return confirm('Delete this course?')"
                           href="?delete=<?php echo $row['id']; ?>">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="5" style="text-align:center;padding:20px;">
                    No courses found 📚
                </td>
            </tr>

        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php include_once "../includes/footer.php"; ?>