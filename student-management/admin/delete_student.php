<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";
include_once "../includes/functions.php";

// ❌ Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request");
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    set_message("Invalid student ID!");
    redirect("students.php");
}

// 🔥 START TRANSACTION
mysqli_begin_transaction($conn);

try {

    // 🔍 Get user_id
    $res = mysqli_query($conn, "SELECT user_id FROM students WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    if (!$row) {
        throw new Exception("Student not found!");
    }

    $user_id = (int)$row['user_id'];

    // 🔥 DELETE RELATED DATA FIRST

    // Attendance
    mysqli_query($conn, "DELETE FROM attendance WHERE student_id=$id");

    // Fees
    mysqli_query($conn, "DELETE FROM fees WHERE student_id=$id");

    // Student
    mysqli_query($conn, "DELETE FROM students WHERE id=$id");

    // User account (optional but good)
    if ($user_id > 0) {
        mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
    }

    // ✅ COMMIT
    mysqli_commit($conn);

    set_message("✅ Student and all related data deleted successfully!");

} catch (Exception $e) {

    // ❌ ROLLBACK
    mysqli_rollback($conn);

    set_message("❌ Error: " . $e->getMessage());
}

redirect("students.php");