<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid teacher ID.");
}

include('../../includes/config.php');

$teacher_id = intval($_GET['id']);

// पहले attendance delete करें
$conn->query("DELETE FROM teacher_attendance WHERE teacher_id = $teacher_id");

// फिर teacher delete करें
$delete = $conn->query("DELETE FROM teachers WHERE id = $teacher_id");

if ($delete) {
    header("Location: index.php?msg=deleted");
} else {
    die("❌ Failed to delete teacher.");
}
