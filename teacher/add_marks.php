<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

include('../includes/config.php');

$success = "";
$error = "";
$class = $_POST['class'] ?? '';
$student_id = $_POST['student_id'] ?? '';

// Step 1: Fetch distinct class list
$classList = [];
$res = $conn->query("SELECT DISTINCT class FROM students ORDER BY class");
while ($row = $res->fetch_assoc()) {
    $classList[] = $row['class'];
}

// Step 2: Fetch students based on selected class
$students = [];
if ($class) {
    $stmt = $conn->prepare("SELECT id, name, roll_no FROM students WHERE class = ? ORDER BY roll_no");
    $stmt->bind_param("s", $class);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Step 3: Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_marks'])) {
    $subject = $_POST['subject'] ?? '';
    $marks_obtained = $_POST['marks_obtained'] ?? '';
    $total_marks = $_POST['total_marks'] ?? '';
    $exam_type = $_POST['exam_type'] ?? '';
    $date = $_POST['date'] ?? '';

    if ($student_id && $subject && $marks_obtained !== '' && $total_marks && $exam_type && $date) {
        $stmt = $conn->prepare("INSERT INTO results (student_id, subject, marks_obtained, total_marks, exam_type, date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiss", $student_id, $subject, $marks_obtained, $total_marks, $exam_type, $date);
        if ($stmt->execute()) {
            $success = "✅ Marks added successfully.";
        } else {
            $error = "❌ Failed to insert marks.";
        }
    } else {
        $error = "❗ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Marks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f8ff;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 22px;
            }

            .btn, input, select {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 class="text-center mb-4">📚 Add Student Marks</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Step 1: Select Class -->
    <form method="POST">
        <div class="mb-3">
            <label for="class" class="form-label">🏫 Select Class</label>
            <select name="class" id="class" class="form-select" onchange="this.form.submit()" required>
                <option value="">-- Select Class --</option>
                <?php foreach ($classList as $c): ?>
                    <option value="<?= $c ?>" <?= $c === $class ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- Step 2: Show form if class is selected -->
    <?php if ($class): ?>
    <form method="POST">
        <input type="hidden" name="class" value="<?= htmlspecialchars($class) ?>">

        <div class="mb-3">
            <label class="form-label">👤 Select Student (by Name / Roll)</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $stu): ?>
                    <option value="<?= $stu['id'] ?>" <?= $stu['id'] == $student_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($stu['name']) ?> (Roll: <?= $stu['roll_no'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">📘 Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">📝 Marks Obtained</label>
            <input type="number" name="marks_obtained" class="form-control" required min="0">
        </div>

        <div class="mb-3">
            <label class="form-label">🎯 Total Marks</label>
            <input type="number" name="total_marks" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label class="form-label">📑 Exam Type</label>
            <select name="exam_type" class="form-select" required>
                <option value="">-- Select Exam Type --</option>
                <option value="Unit Test">Unit Test</option>
                <option value="Half Yearly">Half Yearly</option>
                <option value="Final Exam">Final Exam</option>
                <option value="Monthly Test">Monthly Test</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">📅 Exam Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <button type="submit" name="submit_marks" class="btn btn-primary w-100">➕ Submit Marks</button>
    </form>
    <?php endif; ?>

    <!-- Back to Dashboard Button (always visible) -->
    <div style="text-align:center; margin-top: 20px;">
        <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

</body>
</html>
