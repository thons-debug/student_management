<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../includes/functions.php";
include_once "../includes/header.php";
?>

<div class="page-header">

    <h2>📢 Notices & Announcements</h2>

    <p>
        Stay updated with the latest academic notifications
    </p>

</div>

<!-- 🔥 NOTICE LIST -->
<div class="notice-container">

    <!-- NOTICE 1 -->
    <div class="card notice-card">

        <div class="notice-top">

            <div class="notice-icon exam-icon">
                📝
            </div>

            <div>

                <h3>Semester Examination</h3>

                <p class="notice-date">
                    Published: 08 May 2026
                </p>

            </div>

        </div>

        <p class="notice-text">

            Semester examinations will begin from next month.
            Students are advised to complete all pending assignments
            and maintain attendance eligibility.

        </p>

    </div>

    <!-- NOTICE 2 -->
    <div class="card notice-card">

        <div class="notice-top">

            <div class="notice-icon attendance-icon">
                📅
            </div>

            <div>

                <h3>Attendance Requirement</h3>

                <p class="notice-date">
                    Published: 06 May 2026
                </p>

            </div>

        </div>

        <p class="notice-text">

            Students must maintain a minimum of 75% attendance
            to appear for semester examinations.

        </p>

    </div>

    <!-- NOTICE 3 -->
    <div class="card notice-card">

        <div class="notice-top">

            <div class="notice-icon fee-icon">
                💰
            </div>

            <div>

                <h3>Fee Payment Reminder</h3>

                <p class="notice-date">
                    Published: 05 May 2026
                </p>

            </div>

        </div>

        <p class="notice-text">

            Last date for fee payment is 25th May 2026.
            Students with pending fees are requested
            to complete payment immediately.

        </p>

    </div>

    <!-- NOTICE 4 -->
    <div class="card notice-card">

        <div class="notice-top">

            <div class="notice-icon project-icon">
                🚀
            </div>

            <div>

                <h3>Project Submission</h3>

                <p class="notice-date">
                    Published: 04 May 2026
                </p>

            </div>

        </div>

        <p class="notice-text">

            Final year project submissions must be completed
            before Friday. Late submissions will not be accepted.

        </p>

    </div>

</div>

<!-- 🔥 EXTRA INFO -->
<div class="card">

    <h3>📌 Student Instructions</h3>

    <ul style="
    margin-top:15px;
    line-height:2;
    padding-left:20px;
    color:#555;
    ">

        <li>Check notices regularly for updates.</li>

        <li>Follow academic deadlines carefully.</li>

        <li>Maintain attendance and fee clearance.</li>

        <li>Contact administration for clarification if needed.</li>

    </ul>

</div>

<?php include_once "../includes/footer.php"; ?>