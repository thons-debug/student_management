<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../config/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";

$user_id = $_SESSION['user_id'];

// 🔥 Fetch Student + Course Details
$result = mysqli_query($conn,"
SELECT 
    s.name,
    s.roll_number,
    c.course_name,
    c.course_code,
    c.duration
FROM students s
LEFT JOIN courses c ON s.course_id = c.id
WHERE s.user_id = $user_id
");

$course = mysqli_fetch_assoc($result);
?>

<div class="page-header">

    <h2>📚 My Course</h2>

    <p>
        View your enrolled course details
    </p>

</div>

<?php if($course && $course['course_name']): ?>

<!-- 🔥 COURSE CARD -->
<div class="card course-card">

    <div class="course-top">

        <div>

            <h2>
                <?php echo e($course['course_name']); ?>
            </h2>

            <p class="course-subtitle">
                Student Course Information
            </p>

        </div>

        <div class="course-badge">
            ACTIVE
        </div>

    </div>

    <div class="course-grid">

        <div class="info-box">

            <span class="info-title">
                👨‍🎓 Student Name
            </span>

            <h3>
                <?php echo e($course['name']); ?>
            </h3>

        </div>

        <div class="info-box">

            <span class="info-title">
                🆔 Roll Number
            </span>

            <h3>
                <?php echo e($course['roll_number']); ?>
            </h3>

        </div>

        <div class="info-box">

            <span class="info-title">
                📘 Course Code
            </span>

            <h3>
                <?php echo e($course['course_code']); ?>
            </h3>

        </div>

        <div class="info-box">

            <span class="info-title">
                ⏳ Duration
            </span>

            <h3>
                <?php echo e($course['duration']); ?>
            </h3>

        </div>

    </div>

</div>

<!-- 🔥 EXTRA INFO -->
<div class="card">

    <h3>📖 Course Overview</h3>

    <p style="
    margin-top:12px;
    line-height:1.9;
    color:#555;
    ">

        This course is assigned to your academic profile.
        Please maintain proper attendance and complete
        all assignments and assessments within the
        scheduled duration.

    </p>

</div>

<?php else: ?>

<!-- ❌ NO COURSE -->
<div class="card empty-state">

    <div style="
    font-size:65px;
    margin-bottom:15px;
    ">
        📚
    </div>

    <h2>No Course Assigned</h2>

    <p style="
    margin-top:10px;
    color:#666;
    ">

        Your account is currently not linked
        with any course.

    </p>

    <p style="
    margin-top:10px;
    color:#666;
    ">

        Please contact administrator.

    </p>

</div>

<?php endif; ?>

<?php include_once "../includes/footer.php"; ?>