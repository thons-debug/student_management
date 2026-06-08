<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";

// 🔒 VALID TYPE
$type = $_GET['type'] ?? '';

$allowed = ['students','courses','attendance','fees'];

if (!in_array($type, $allowed)) {
    die("Invalid export type");
}

// 🔥 HEADERS
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$type.'_report.csv');

$output = fopen("php://output", "w");

// ===============================
// 👨‍🎓 STUDENTS
// ===============================
if ($type === "students") {

    fputcsv($output, ["ID","Name","Roll","Course","Email","Phone"]);

    $result = mysqli_query($conn,"
        SELECT s.id, s.name, s.roll_number, 
               c.course_name, s.email, s.phone
        FROM students s
        LEFT JOIN courses c ON s.course_id=c.id
        ORDER BY s.id DESC
    ");

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['roll_number'],
                $row['course_name'],
                $row['email'],
                $row['phone']
            ]);
        }
    } else {
        fputcsv($output, ["No data found"]);
    }
}

// ===============================
// 📚 COURSES
// ===============================
elseif ($type === "courses") {

    fputcsv($output, ["ID","Course Name","Code","Duration"]);

    $result = mysqli_query($conn,"
        SELECT id, course_name, course_code, duration 
        FROM courses
    ");

    while($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            $row['course_name'],
            $row['course_code'],
            $row['duration']
        ]);
    }
}

// ===============================
// 📅 ATTENDANCE (TODAY)
// ===============================
elseif ($type === "attendance") {

    fputcsv($output, ["Student","Roll","Date","Status"]);

    $today = date("Y-m-d");

    $result = mysqli_query($conn,"
        SELECT s.name, s.roll_number, 
               a.attendance_date, a.status
        FROM attendance a
        JOIN students s ON a.student_id=s.id
        WHERE a.attendance_date='$today'
    ");

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['name'],
                $row['roll_number'],
                $row['attendance_date'],
                $row['status']
            ]);
        }
    } else {
        fputcsv($output, ["No attendance today"]);
    }
}

// ===============================
// 💰 FEES
// ===============================
elseif ($type === "fees") {

    fputcsv($output, ["Student","Roll","Amount","Date","Status"]);

    $result = mysqli_query($conn,"
        SELECT s.name, s.roll_number, 
               f.amount, f.payment_date, f.status
        FROM fees f
        JOIN students s ON f.student_id=s.id
        ORDER BY f.id DESC
    ");

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['name'],
                $row['roll_number'],
                $row['amount'],
                $row['payment_date'],
                $row['status']
            ]);
        }
    } else {
        fputcsv($output, ["No fee records found"]);
    }
}

// 🔥 CLOSE
fclose($output);
exit;