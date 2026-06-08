<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

// 📚 COURSES
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_name ASC");

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

    $plainPass = $_POST['student_password'] ?: "student@123";

    if ($name === "" || $roll === "") {
        set_message("⚠ Name and Roll Number required!");
        redirect("add_student.php");
    }

    // 🔍 Roll check
    $check = mysqli_query($conn, "SELECT id FROM students WHERE roll_number='$roll'");
    if (mysqli_num_rows($check) > 0) {
        set_message("⚠ Roll number already exists!");
        redirect("add_student.php");
    }

    // 🔐 Username generate
    $username = "std_" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $roll));

    // 🔍 Username duplicate check
    $checkUser = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($checkUser) > 0) {
        $username .= rand(100,999); // fallback
    }

    $hashPass = hash_password($plainPass);

    // 🔥 TRANSACTION START
    mysqli_begin_transaction($conn);

    try {

        // 👤 INSERT USER
        mysqli_query($conn, "
            INSERT INTO users (username,password,role,status) 
            VALUES ('$username','$hashPass','student','approved')
        ");

        $user_id = mysqli_insert_id($conn);

        // 🎓 INSERT STUDENT
        mysqli_query($conn, "
            INSERT INTO students 
            (user_id,name,roll_number,course_id,email,phone,address,dob,gender,admission_date)
            VALUES (
                $user_id,
                '$name',
                '$roll',
                ".($course_id ? $course_id : "NULL").",
                '$email',
                '$phone',
                '$address',
                ".($dob ? "'$dob'" : "NULL").",
                ".($gender ? "'$gender'" : "NULL").",
                ".($admission_date ? "'$admission_date'" : "NULL")."
            )
        ");

        // ✅ COMMIT
        mysqli_commit($conn);

        set_message("✅ Student added! Login → $username / $plainPass");

    } catch (Exception $e) {

        mysqli_rollback($conn);
        set_message("❌ Error adding student!");
    }

    redirect("students.php");
}
?>

<h2>Add Student</h2>

<div class="card">

<form method="POST">

<label>Name *</label>
<input name="name" required>

<label>Roll Number *</label>
<input name="roll_number" required>

<label>Course</label>
<select name="course_id">
<option value="">-- Select Course --</option>

<?php while($c = mysqli_fetch_assoc($courses)): ?>
<option value="<?php echo (int)$c['id']; ?>">
<?php echo e($c['course_name']); ?>
</option>
<?php endwhile; ?>

</select>

<label>Email</label>
<input name="email">

<label>Phone</label>
<input name="phone">

<label>Address</label>
<textarea name="address"></textarea>

<!-- 🔥 GRID -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">

    <div>
        <label>DOB</label>
        <input type="date" name="dob">
    </div>

    <div>
        <label>Gender</label>
        <select name="gender">
            <option value="">-- Select --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div>
        <label>Admission Date</label>
        <input type="date" name="admission_date">
    </div>

</div>

<label>Student Password (optional)</label>
<input name="student_password" placeholder="default: student@123">

<br>

<button type="submit" class="btn success">
➕ Save Student
</button>

</form>

</div>

<?php include_once "../includes/footer.php"; ?>