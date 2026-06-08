<?php
include_once __DIR__ . "/functions.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>
        Student Management System
    </title>

    <!-- 🔥 GOOGLE FONT -->
    <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

    <!-- 🔥 MAIN CSS -->
    <link
    rel="stylesheet"
    href="/student-management/assets/css/style.css">

</head>

<body>

<!-- =======================================
     🔥 HEADER START
======================================= -->

<header class="main-header">

    <!-- 🔥 TOP HEADER -->
    <div class="header-top">

        <!-- LOGO -->
        <div class="logo-section">

            <h2 class="logo">
                Student Management System
            </h2>

            <span class="logo-subtitle">
                Smart Academic Portal
            </span>

        </div>

        <!-- USER INFO -->
        <?php if(is_logged_in()): ?>

        <div class="header-user-info">

            <div class="user-avatar">

                <?php
                echo strtoupper(
                    substr(
                        $_SESSION['role'],
                        0,
                        1
                    )
                );
                ?>

            </div>

            <div>

                <h4 class="user-role">

                    <?php
                    echo ucfirst(
                        $_SESSION['role']
                    );
                    ?>

                </h4>

                <span class="user-status">
                    Online
                </span>

            </div>

        </div>

        <?php endif; ?>

    </div>

    <!-- =======================================
         🔥 NAVIGATION BAR
    ======================================= -->

    <?php if(is_logged_in()): ?>

    <nav class="nav-bar">

        <!-- LEFT NAVIGATION -->
        <div class="nav-left">

            <!-- =======================================
                 🔥 ADMIN MENU
            ======================================= -->

            <?php if($_SESSION['role'] === 'admin'): ?>

                <a
                href="/student-management/admin/dashboard.php">

                    🏠 Dashboard

                </a>

                <a
                href="/student-management/admin/students.php">

                    👨‍🎓 Students

                </a>

                <a
                href="/student-management/admin/student_requests.php"
                class="highlight">

                    🔔 Requests

                </a>

                <a
                href="/student-management/admin/courses.php">

                    📚 Courses

                </a>

                <a
                href="/student-management/admin/attendance.php">

                    📅 Attendance

                </a>

                <a
                href="/student-management/admin/fees.php">

                    💰 Fees

                </a>

                <a
                href="/student-management/admin/reports.php">

                    📊 Reports

                </a>

            <?php endif; ?>

            <!-- =======================================
                 🔥 STUDENT MENU
            ======================================= -->

            <?php if($_SESSION['role'] === 'student'): ?>

                <a
                href="/student-management/student/dashboard.php">

                    🏠 Dashboard

                </a>

                <a
                href="/student-management/student/profile.php">

                    👤 Profile

                </a>

                <a
                href="/student-management/student/attendance.php">

                    📅 Attendance

                </a>

                <a
                href="/student-management/student/fees.php">

                    💰 Fees

                </a>

                <a
                href="/student-management/student/courses.php">

                    📚 Courses

                </a>

                <a
                href="/student-management/student/results.php">

                    📊 Results

                </a>

                <a
                href="/student-management/student/notices.php">

                    📢 Notices

                </a>

                <a
                href="/student-management/student/timetable.php">

                    ⏰ Timetable

                </a>

                <a
                href="/student-management/student/settings.php">

                    ⚙ Settings

                </a>

            <?php endif; ?>

        </div>

        <!-- =======================================
             🔥 RIGHT SIDE
        ======================================= -->

        <div class="nav-right">

            <a
            href="/student-management/auth/logout.php"
            class="logout">

                🚪 Logout

            </a>

        </div>

    </nav>

    <?php endif; ?>

</header>

<!-- =======================================
     🔥 MAIN CONTENT
======================================= -->

<div class="main-content">

<?php display_message(); ?>