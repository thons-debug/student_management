<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = (int)$_SESSION['user_id'];

// 🔍 Fetch student
$result = mysqli_query($conn, "SELECT * FROM students WHERE user_id=$user_id");
$student = mysqli_fetch_assoc($result);

// 🔥 UPDATE PROFILE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = clean_input($_POST['name']);
    $email   = clean_input($_POST['email']);
    $phone   = clean_input($_POST['phone']);
    $address = clean_input($_POST['address']);

    // 📸 IMAGE UPLOAD
    $photo_name = $student['photo'] ?? '';

    if (!empty($_FILES['photo']['name'])) {

        $target_dir = "../uploads/student_photos/";
        $file_name  = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

        $photo_name = $file_name;
    }

    // 🔄 UPDATE QUERY
    mysqli_query($conn, "
        UPDATE students SET 
        name='$name',
        email='$email',
        phone='$phone',
        address='$address',
        photo='$photo_name'
        WHERE user_id=$user_id
    ");

    set_message("Profile updated successfully!");
    redirect("profile.php");
}
?>

<h2>My Profile</h2>

<?php if ($student): ?>

<div class="card">

    <!-- 🔥 PROFILE IMAGE -->
    <div style="text-align:center;margin-bottom:20px;">
        <img 
            src="/student-management/uploads/student_photos/<?php echo $student['photo'] ?? 'default.png'; ?>" 
            alt="Profile"
            style="width:120px;height:120px;border-radius:50%;object-fit:cover;"
        >
    </div>

    <!-- 🔥 FORM -->
    <form method="POST" enctype="multipart/form-data">

        <label>Name</label>
        <input type="text" name="name" value="<?php echo e($student['name']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo e($student['email']); ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo e($student['phone']); ?>">

        <label>Address</label>
        <textarea name="address"><?php echo e($student['address']); ?></textarea>

        <label>Profile Photo</label>
        <input type="file" name="photo">

        <button type="submit">Update Profile</button>

    </form>

</div>

<?php else: ?>

<div class="card">
    <h3>🚧 Profile Not Available</h3>
    <p>Your profile is not yet created by admin.</p>
</div>

<?php endif; ?>

<?php include_once "../includes/footer.php"; ?>