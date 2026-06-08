<?php
include_once "../includes/auth_check.php";
check_role("student");

include_once "../includes/functions.php";
include_once "../includes/header.php";

// 🔥 Timetable Data
$timetable = [

    [
        "day" => "Monday",
        "subject" => "PHP Programming",
        "faculty" => "Mr. Rahul",
        "time" => "10:00 AM - 11:30 AM",
        "room" => "Lab 1"
    ],

    [
        "day" => "Tuesday",
        "subject" => "Database Management",
        "faculty" => "Mrs. Sneha",
        "time" => "11:00 AM - 12:30 PM",
        "room" => "Room 203"
    ],

    [
        "day" => "Wednesday",
        "subject" => "Web Development",
        "faculty" => "Mr. Arjun",
        "time" => "09:30 AM - 11:00 AM",
        "room" => "Lab 2"
    ],

    [
        "day" => "Thursday",
        "subject" => "JavaScript",
        "faculty" => "Mr. Naveen",
        "time" => "01:00 PM - 02:30 PM",
        "room" => "Lab 3"
    ],

    [
        "day" => "Friday",
        "subject" => "Project Work",
        "faculty" => "Mrs. Divya",
        "time" => "10:00 AM - 01:00 PM",
        "room" => "Innovation Lab"
    ]

];
?>

<div class="page-header">

    <h2>📅 Class Timetable</h2>

    <p>
        Weekly academic class schedule
    </p>

</div>

<!-- 🔥 TIMETABLE SUMMARY -->
<div class="stats-grid">

    <div class="stat-card stat-blue">

        Total Classes

        <h1>
            <?php echo count($timetable); ?>
        </h1>

    </div>

    <div class="stat-card stat-green">

        Weekly Schedule

        <h1>
            5 Days
        </h1>

    </div>

    <div class="stat-card stat-purple">

        Practical Labs

        <h1>
            3
        </h1>

    </div>

</div>

<!-- 🔥 TIMETABLE TABLE -->
<div class="card">

<table class="table">

<thead>

<tr>

<th>#</th>

<th>Day</th>

<th>Subject</th>

<th>Faculty</th>

<th>Time</th>

<th>Room</th>

</tr>

</thead>

<tbody>

<?php
$count = 1;

foreach($timetable as $row):
?>

<tr>

<td>
<?php echo $count++; ?>
</td>

<td>

<span class="badge-info">
    <?php echo e($row['day']); ?>
</span>

</td>

<td>

<strong>
<?php echo e($row['subject']); ?>
</strong>

</td>

<td>
<?php echo e($row['faculty']); ?>
</td>

<td>

<span style="
font-weight:600;
color:#667eea;
">

<?php echo e($row['time']); ?>

</span>

</td>

<td>
<?php echo e($row['room']); ?>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<!-- 🔥 STUDENT INSTRUCTIONS -->
<div class="card">

<h3>📌 Timetable Instructions</h3>

<ul style="
margin-top:15px;
line-height:2;
padding-left:20px;
color:#555;
">

<li>Students should arrive 10 minutes before class.</li>

<li>Carry ID card and required materials.</li>

<li>Lab sessions are mandatory.</li>

<li>Follow attendance requirements strictly.</li>

<li>Timetable may change based on academic schedule.</li>

</ul>

</div>

<!-- 🔥 TODAY HIGHLIGHT -->
<div class="card">

<h3>🚀 Today's Highlight</h3>

<p style="
margin-top:15px;
line-height:1.9;
color:#555;
">

Focus on practical learning and project development.
Consistent attendance and active participation
will improve both academic and technical skills.

</p>

</div>

<?php include_once "../includes/footer.php"; ?>