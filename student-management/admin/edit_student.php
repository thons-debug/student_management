<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    exit("Invalid student ID");
}

// 🔍 Fetch student
$studentRes = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($studentRes);

if (!$student) {
    exit("Student not found");
}

// 📚 Courses
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_name ASC");

// 🔥 UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = clean_input($_POST['name'] ?? "");
    $roll = clean_input($_POST['roll_number'] ?? "");
    $course_id = ($_POST['course_id'] ?? "") !== "" ? (int)$_POST['course_id'] : NULL;

    $email = clean_input($_POST['email'] ?? "");
    $phone = clean_input($_POST['phone'] ?? "");
    $address = clean_input($_POST['address'] ?? "");

    $dob = $_POST['dob'] ?: NULL;
    $gender = $_POST['gender'] ?: NULL;
    $admission_date = $_POST['admission_date'] ?: NULL;

    if ($name === "" || $roll === "") {
        set_message("⚠ Name and Roll Number are required!");
        redirect("edit_student.php?id=$id");
    }

    // 🔍 Unique roll check
    $check = mysqli_query($conn, "
        SELECT id FROM students 
        WHERE roll_number='$roll' AND id!=$id
    ");

    if (mysqli_num_rows($check) > 0) {
        set_message("⚠ Roll number already exists!");
        redirect("edit_student.php?id=$id");
    }

    // 🔄 UPDATE QUERY
    $query = "
        UPDATE students SET 
            name='$name',
            roll_number='$roll',
            course_id=" . ($course_id ? $course_id : "NULL") . ",
            email='$email',
            phone='$phone',
            address='$address',
            dob=" . ($dob ? "'$dob'" : "NULL") . ",
            gender=" . ($gender ? "'$gender'" : "NULL") . ",
            admission_date=" . ($admission_date ? "'$admission_date'" : "NULL") . "
        WHERE id=$id
    ";

    mysqli_query($conn, $query);

    set_message("✅ Student updated successfully!");
    redirect("students.php");
}
?>

<h2>Edit Student</h2>

<div class="card">

<form method="POST">

<label>Name *</label>
<input name="name" required value="<?php echo e($student['name']); ?>">

<label>Roll Number *</label>
<input name="roll_number" required value="<?php echo e($student['roll_number']); ?>">

<label>Course</label>
<select name="course_id">
<option value="">-- Select Course --</option>

<?php while($c = mysqli_fetch_assoc($courses)): ?>
<option value="<?php echo (int)$c['id']; ?>"
    <?php echo ((int)$student['course_id'] === (int)$c['id']) ? "selected" : ""; ?>>
    <?php echo e($c['course_name']); ?>
</option>
<?php endwhile; ?>

</select>

<label>Email</label>
<input name="email" value="<?php echo e($student['email'] ?? ""); ?>">

<label>Phone</label>
<input name="phone" value="<?php echo e($student['phone'] ?? ""); ?>">

<label>Address</label>
<textarea name="address"><?php echo e($student['address'] ?? ""); ?></textarea>

<!-- 🔥 GRID ROW -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">

    <div>
        <label>DOB</label>
        <input type="date" name="dob" value="<?php echo e($student['dob'] ?? ""); ?>">
    </div>

    <div>
        <label>Gender</label>
        <select name="gender">
            <option value="">-- Select --</option>
            <?php foreach(["male","female","other"] as $g): ?>
                <option value="<?php echo $g; ?>"
                    <?php echo (($student['gender'] ?? "") === $g) ? "selected" : ""; ?>>
                    <?php echo ucfirst($g); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Admission Date</label>
        <input type="date" name="admission_date" value="<?php echo e($student['admission_date'] ?? ""); ?>">
    </div>

</div>

<br>

<button type="submit" class="btn success">Update Student</button>

</form>

</div>

<?php include_once "../includes/footer.php"; ?>