<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

include('../includes/config.php');

$teacher_id = $_SESSION['teacher_id'];
$today = date('Y-m-d');
$success = '';
$error = '';

// Check if already marked today
$stmt = $conn->prepare("SELECT id FROM teacher_attendance WHERE teacher_id = ? AND date = ?");
$stmt->bind_param("is", $teacher_id, $today);
$stmt->execute();
$stmt->store_result();

$alreadyMarked = $stmt->num_rows > 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyMarked) {
    $status = $_POST['status'] ?? '';

    if ($status) {
        $stmt = $conn->prepare("INSERT INTO teacher_attendance (teacher_id, date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $teacher_id, $today, $status);
        if ($stmt->execute()) {
            $success = "✅ Attendance marked successfully.";
            $alreadyMarked = true;
        } else {
            $error = "❌ Failed to mark attendance.";
        }
    } else {
        $error = "❗ Please select a status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark My Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef3f8;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">📅 Mark My Attendance</h3>

    <p><strong>Today's Date:</strong> <?= $today ?></p>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($alreadyMarked): ?>
        <div class="alert alert-info">✅ You have already marked your attendance today.</div>
    <?php else: ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Select Status:</label>
            <select name="status" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">✔️ Submit Attendance</button>
    </form>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

</body>
</html>
