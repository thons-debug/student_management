</div>

<!-- =======================================
     🔥 FOOTER START
======================================= -->

<footer class="main-footer">

    <div class="footer-container">

        <!-- =======================================
             🔥 LEFT SECTION
        ======================================= -->

        <div class="footer-left">

            <h2 class="footer-logo">
                🎓 Student Management System
            </h2>

            <p class="footer-text">

                A modern academic management platform
                designed for efficient student,
                attendance, course, and fee management.

            </p>

        </div>

        <!-- =======================================
             🔥 CENTER SECTION
        ======================================= -->

        <div class="footer-center">

            <h3>Quick Links</h3>

            <ul class="footer-links">

                <?php if(is_logged_in()): ?>

                    <?php if($_SESSION['role'] === 'admin'): ?>

                        <li>
                            <a href="/student-management/admin/dashboard.php">
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a href="/student-management/admin/students.php">
                                Students
                            </a>
                        </li>

                        <li>
                            <a href="/student-management/admin/reports.php">
                                Reports
                            </a>
                        </li>

                    <?php endif; ?>

                    <?php if($_SESSION['role'] === 'student'): ?>

                        <li>
                            <a href="/student-management/student/dashboard.php">
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a href="/student-management/student/profile.php">
                                Profile
                            </a>
                        </li>

                        <li>
                            <a href="/student-management/student/results.php">
                                Results
                            </a>
                        </li>

                    <?php endif; ?>

                <?php endif; ?>

            </ul>

        </div>

        <!-- =======================================
             🔥 RIGHT SECTION
        ======================================= -->

        <div class="footer-right">

            <h3>System Features</h3>

            <ul class="footer-features">

                <li>✔ Student Management</li>

                <li>✔ Attendance Tracking</li>

                <li>✔ Fee Management</li>

                <li>✔ Course Management</li>

                <li>✔ Academic Reports</li>

            </ul>

        </div>

    </div>

    <!-- =======================================
         🔥 FOOTER BOTTOM
    ======================================= -->

    <div class="footer-bottom">

        <p>

            © <?php echo date("Y"); ?>

            Student Management System |

            All Rights Reserved |

            Developed using PHP, MySQL,
            HTML, CSS & JavaScript 🚀

        </p>

    </div>

</footer>

<!-- =======================================
     🔥 SCRIPT
======================================= -->

<script src="/student-management/assets/js/script.js"></script>

</body>
</html>