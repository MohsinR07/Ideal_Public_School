<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include('../includes/config.php');

$id = $_GET['id'] ?? null;
$class_name = $_GET['class'] ?? '';
$error = "";

if (!$id) {
    header("Location: students.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $roll_number = $_POST['roll_number'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("SELECT id FROM students WHERE roll_number = ? AND class_name = ? AND id != ?");
    $stmt->bind_param("ssi", $roll_number, $class_name, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Another student with same roll number exists in this class.";
    } else {
        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, roll_number = ?, gender = ?, dob = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $email, $roll_number, $gender, $dob, $id);
        if ($stmt->execute()) {
            header("Location: students.php?class=" . urlencode($class_name));
            exit;
        } else {
            $error = "Failed to update student.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            margin: 50px auto;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        h2 {
            font-weight: bold;
            margin-bottom: 25px;
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 6px 10px;
            }

            input, select {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">✏️ Edit Student - <?= htmlspecialchars($class_name) ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label>Roll Number</label>
                <input type="text" name="roll_number" value="<?= htmlspecialchars($student['roll_number']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?= htmlspecialchars($student['dob']) ?>" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">✅ Update Student</button>
            <a href="students.php?class=<?= urlencode($class_name) ?>" class="btn btn-secondary">🔙 Cancel</a>
        </form>
    </div>
</body>
</html>
