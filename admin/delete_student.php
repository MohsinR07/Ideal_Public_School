<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include('../includes/config.php');

$id = $_GET['id'] ?? null;
$class_name = $_GET['class'] ?? '';

if ($id && $class_name) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: students.php?class=" . urlencode($class_name));
exit;
