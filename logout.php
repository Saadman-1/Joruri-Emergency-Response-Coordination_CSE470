<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Destroy session and redirect to admin.php
session_unset();
session_destroy();
header('Location: admin.php');
exit();
