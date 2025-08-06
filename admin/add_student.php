<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include('../includes/config.php');

$class = $_GET['class'] ?? '';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $roll_no = $_POST['roll_no'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $class = $_POST['class'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ✅ Hashed password

    // Check if roll number already exists in this class
    $stmt = $conn->prepare("SELECT id FROM students WHERE roll_no = ? AND class = ?");
    $stmt->bind_param("ss", $roll_no, $class);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Roll number already exists in this class.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, email, password, roll_no, class, gender, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $password, $roll_no, $class, $gender, $dob);
        if ($stmt->execute()) {
            header("Location: students.php?class=" . urlencode($class));
            exit;
        } else {
            $error = "Failed to add student.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <h2 class="text-center">➕ Add New Student - <?= htmlspecialchars($class) ?></h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="class" value="<?= htmlspecialchars($class) ?>">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label>Roll Number</label>
            <input type="text" name="roll_no" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-select">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Student</button>
        <a href="students.php?class=<?= urlencode($class) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
