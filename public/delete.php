<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

if ($id) {
    $sql = "DELETE FROM students WHERE id = $id";
    $conn->query($sql);
}


header("Location: index.php");
exit;
?>
