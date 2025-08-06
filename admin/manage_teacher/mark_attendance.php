<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

include('../../includes/config.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Invalid teacher ID.");
}

$teacher_id = intval($_GET['id']);

// Get teacher info
$stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$teacher) {
    die("❌ Teacher not found.");
}

$success = $error = "";

// Attendance Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = trim($_POST['status']);
    $date = $_POST['date'] ?? date("Y-m-d");

    if (!$date || !$status) {
        $error = "❌ Date and status are required.";
    } else {
        // Check if already marked for this date
        $check = $conn->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? AND date = ?");
        $check->bind_param("is", $teacher_id, $date);
        $check->execute();
        $already = $check->get_result()->fetch_assoc();
        $check->close();

        if ($already) {
            // Update instead of insert
            $update = $conn->prepare("UPDATE teacher_attendance SET status = ? WHERE teacher_id = ? AND date = ?");
            $update->bind_param("sis", $status, $teacher_id, $date);
            if ($update->execute()) {
                $success = "✅ Attendance updated as $status for $date.";
            } else {
                $error = "❌ Failed to update attendance.";
            }
            $update->close();
        } else {
            // Insert new attendance
            $insert = $conn->prepare("INSERT INTO teacher_attendance (teacher_id, date, status) VALUES (?, ?, ?)");
            $insert->bind_param("iss", $teacher_id, $date, $status);
            if ($insert->execute()) {
                $success = "✅ Attendance marked as $status for $date.";
            } else {
                $error = "❌ Failed to mark attendance.";
            }
            $insert->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1607392252021-2b4387d9765f?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        h3 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-check-label {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
            h3 {
                font-size: 20px;
            }
            .btn, .form-check-label {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">🔙 Back to Manage Teachers</a>
        <h3>🗓️ Mark Attendance for <?= htmlspecialchars($teacher['name']) ?></h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="date" class="form-label">📅 Select Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-check form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="status" id="present" value="Present" required>
                <label class="form-check-label" for="present">✅ Present</label>
            </div>
            <div class="form-check form-check-inline mb-3">
                <input class="form-check-input" type="radio" name="status" id="absent" value="Absent">
                <label class="form-check-label" for="absent">❌ Absent</label>
            </div>

            <div>
                <button type="submit" class="btn btn-success mt-3">📌 Save Attendance</button>
            </div>
        </form>
    </div>
</body>
</html>
