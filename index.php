<?php
include_once "includes/functions.php";

// Ensure session started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Security headers (basic)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// 🔁 Helper: safe redirect
function go($path) {
    redirect($path);
}

// 🔍 Check login
if (!is_logged_in()) {
    go("auth/login.php");
}

// 🧠 Get role safely
$role = strtolower($_SESSION['role'] ?? '');

// 🚨 Invalid session handling
if (!in_array($role, ['admin', 'student'])) {
    $_SESSION = [];
    session_destroy();
    go("auth/login.php");
}

// 🎯 Route mapping (clean system)
$routes = [
    'admin'   => 'admin/dashboard.php',
    'student' => 'student/dashboard.php'
];

// 🚀 Redirect to dashboard
go($routes[$role]);