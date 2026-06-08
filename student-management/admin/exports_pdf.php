<?php
include_once "../includes/auth_check.php";
check_role("admin");

include_once "../config/db.php";

// 🔥 LOAD DOMPDF
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

$type = $_GET['type'] ?? '';

$html = "<h2 style='text-align:center;'>Student Management Report</h2>";

if ($type === "students") {

    $result = mysqli_query($conn, "
        SELECT s.id,s.name,s.roll_number,c.course_name
        FROM students s
        LEFT JOIN courses c ON s.course_id=c.id
    ");

    $html .= "
    <table border='1' cellpadding='8' width='100%' style='border-collapse:collapse;'>
        <tr style='background:#2a5298;color:white;'>
            <th>ID</th>
            <th>Name</th>
            <th>Roll</th>
            <th>Course</th>
        </tr>
    ";

    while($row = mysqli_fetch_assoc($result)) {

        $html .= "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['roll_number']}</td>
            <td>{$row['course_name']}</td>
        </tr>
        ";
    }

    $html .= "</table>";
}

// 🔥 GENERATE PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

// 🔥 DOWNLOAD
$dompdf->stream($type . "_report.pdf", ["Attachment" => true]);
exit;