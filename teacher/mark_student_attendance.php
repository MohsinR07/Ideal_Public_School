<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$success = $error = "";
$today = date("Y-m-d");
$classFilter = $_POST['class'] ?? '';

$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_attendance'])) {
    $attendanceData = $_POST['attendance'];
    $selectedClass = $_POST['class'];

    foreach ($attendanceData as $studentId => $status) {
        $check = $conn->prepare("SELECT id FROM student_attendance WHERE student_id = ? AND date = ?");
        $check->bind_param("is", $studentId, $today);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO student_attendance (student_id, class, date, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $studentId, $selectedClass, $today, $status);
            $stmt->execute();
        }

        $check->close();
    }

    $success = "✅ Attendance marked successfully for today.";
}

// Fetch students for selected class
if (!empty($classFilter)) {
    $stmt = $conn->prepare("SELECT id, name, class FROM students WHERE class = ? ORDER BY name");
    $stmt->bind_param("s", $classFilter);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Student Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            h3 {
                font-size: 1.2rem;
            }
            .form-check-label {
                font-size: 0.9rem;
            }
            .table th, .table td {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <!-- 🔙 Back Button -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">🔙 Back</a>
    </div>

    <h3 class="mb-3">📝 Mark Attendance for <?= date("d M Y") ?></h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Class Selection Form -->
    <form method="POST" class="mb-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="class" class="form-label">📚 Select Class</label>
                <select name="class" id="class" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Select Class --</option>
                    <?php
                    $classes = ['LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
                    foreach ($classes as $cls) {
                        $selected = ($cls === $classFilter) ? 'selected' : '';
                        echo "<option value=\"$cls\" $selected>$cls</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Attendance Form -->
    <?php if (!empty($students)): ?>
        <form method="POST">
            <input type="hidden" name="class" value="<?= htmlspecialchars($classFilter) ?>">
            <div class="table-responsive">
                <table class="table table-bordered bg-white shadow-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th class="text-center">Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="attendance[<?= $student['id'] ?>]" id="present<?= $student['id'] ?>" value="Present" required>
                                        <label class="form-check-label" for="present<?= $student['id'] ?>">Present</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="attendance[<?= $student['id'] ?>]" id="absent<?= $student['id'] ?>" value="Absent">
                                        <label class="form-check-label" for="absent<?= $student['id'] ?>">Absent</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" name="mark_attendance" class="btn btn-primary mt-3 w-100 w-md-auto">✅ Submit Attendance</button>
        </form>
    <?php elseif (!empty($classFilter)): ?>
        <div class="alert alert-info">No students found in <strong><?= htmlspecialchars($classFilter) ?></strong>.</div>
    <?php endif; ?>
</div>
</body>
</html>
