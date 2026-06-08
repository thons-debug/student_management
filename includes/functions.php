<?php
// includes/functions.php

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===============================
   SECURITY FUNCTIONS
=================================*/

// Secure input
function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Short escape helper (used in echo e())
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/* ===============================
   REDIRECT
=================================*/
function redirect($url) {
    header("Location: $url");
    exit();
}

/* ===============================
   AUTH CHECKS
=================================*/

// Check login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Role check
function check_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        redirect("../auth/login.php");
    }
}

/* ===============================
   PASSWORD FUNCTIONS
=================================*/
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/* ===============================
   FLASH MESSAGES
=================================*/
function set_message($msg) {
    $_SESSION['message'] = $msg;
}

function display_message() {
    if (isset($_SESSION['message'])) {
        echo "<div class='alert' style='padding:12px;background:#d4edda;color:#155724;border-radius:8px;margin-bottom:15px;'>
                ".e($_SESSION['message'])."
              </div>";
        unset($_SESSION['message']);
    }
}
?>