<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../includes/functions.php";
include_once "../includes/header.php";

// 🔥 Demo Result Data
$results = [
    [
        "subject" => "PHP Programming",
        "marks" => 85
    ],
    [
        "subject" => "Database Management",
        "marks" => 78
    ],
    [
        "subject" => "JavaScript",
        "marks" => 88
    ],
    [
        "subject" => "Web Development",
        "marks" => 91
    ]
];

// 🔥 Calculate Total
$total = 0;

foreach($results as $r){
    $total += $r['marks'];
}

$percentage = $total / count($results);

$status = ($percentage >= 40)
? "PASS"
: "FAIL";
?>

<div class="page-header">

    <h2>📊 Academic Results</h2>

    <p>
        View your semester academic performance
    </p>

</div>

<!-- 🔥 RESULT SUMMARY -->
<div class="stats-grid">

    <div class="stat-card stat-blue">

        Total Subjects

        <h1>
            <?php echo count($results); ?>
        </h1>

    </div>

    <div class="stat-card stat-green">

        Percentage

        <h1>
            <?php echo number_format($percentage,1); ?>%
        </h1>

    </div>

    <div class="stat-card <?php echo ($status=="PASS") ? 'stat-purple' : 'stat-orange'; ?>">

        Final Status

        <h1>
            <?php echo $status; ?>
        </h1>

    </div>

</div>

<!-- 🔥 RESULT TABLE -->
<div class="card">

<table class="table">

<thead>

<tr>

<th>#</th>

<th>Subject</th>

<th>Marks</th>

<th>Grade</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php 
$count = 1;

foreach($results as $row):

$marks = $row['marks'];

// 🔥 Grade Logic
if($marks >= 90){
    $grade = "A+";
}
elseif($marks >= 80){
    $grade = "A";
}
elseif($marks >= 70){
    $grade = "B";
}
elseif($marks >= 60){
    $grade = "C";
}
elseif($marks >= 40){
    $grade = "D";
}
else{
    $grade = "F";
}

$result_status = ($marks >= 40)
? "Pass"
: "Fail";
?>

<tr>

<td>
<?php echo $count++; ?>
</td>

<td>
<?php echo e($row['subject']); ?>
</td>

<td>

<strong>
<?php echo $marks; ?>
</strong>

</td>

<td>

<span class="badge-info">
    <?php echo $grade; ?>
</span>

</td>

<td>

<?php if($marks >= 40): ?>

<span class="badge-success">
    ✅ Pass
</span>

<?php else: ?>

<span class="badge-danger">
    ❌ Fail
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<!-- 🔥 PERFORMANCE MESSAGE -->
<div class="card">

<h3>🎯 Performance Summary</h3>

<p style="
margin-top:15px;
line-height:1.9;
color:#555;
">

You have successfully completed the current semester
with an overall percentage of
<strong>
<?php echo number_format($percentage,1); ?>%
</strong>.

Continue maintaining consistent academic performance
and improve your practical skills for better career growth.

</p>

</div>

<!-- 🔥 GRADE LEGEND -->
<div class="card">

<h3>📘 Grade Legend</h3>

<div class="course-grid">

    <div class="info-box">
        <h3>A+</h3>
        <p>90 - 100</p>
    </div>

    <div class="info-box">
        <h3>A</h3>
        <p>80 - 89</p>
    </div>

    <div class="info-box">
        <h3>B</h3>
        <p>70 - 79</p>
    </div>

    <div class="info-box">
        <h3>C</h3>
        <p>60 - 69</p>
    </div>

    <div class="info-box">
        <h3>D</h3>
        <p>40 - 59</p>
    </div>

</div>

</div>

<?php include_once "../includes/footer.php"; ?>