<?php
// includes/auth_check.php

include_once __DIR__ . "/functions.php";

// Agar login nahi hai to redirect
if (!is_logged_in()) {
    redirect("../auth/login.php");
}
?>