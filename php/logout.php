<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy(); // Uništava sve podatke iz sesije
header("Location: index.php"); // Vraća te na početnu stranu
exit();
?>