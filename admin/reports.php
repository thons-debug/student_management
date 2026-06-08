<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../includes/functions.php";
include_once "../includes/header.php";
?>

<h2>Reports Dashboard</h2>

<div class="stats-grid">

    <!-- 👨‍🎓 STUDENT REPORT -->
    <div class="card">
        <h3>👨‍🎓 Student Reports</h3>

        <a class="btn" href="exports_csv.php?type=students">
            ⬇ Export CSV
        </a>

        <a class="btn success" href="exports_pdf.php?type=students">
            📄 Export PDF
        </a>
    </div>

    <!-- 📚 COURSE REPORT -->
    <div class="card">
        <h3>📚 Course Reports</h3>

        <a class="btn" href="exports_csv.php?type=courses">
            ⬇ Export CSV
        </a>
    </div>

    <!-- 📅 ATTENDANCE REPORT -->
    <div class="card">
        <h3>📅 Attendance Report (Today)</h3>

        <a class="btn" href="exports_csv.php?type=attendance">
            ⬇ Export CSV
        </a>
    </div>

    <!-- 💰 FEES REPORT -->
    <div class="card">
        <h3>💰 Fee Reports</h3>

        <a class="btn" href="exports_csv.php?type=fees">
            ⬇ Export CSV
        </a>
    </div>

</div>

<?php include_once "../includes/footer.php"; ?>