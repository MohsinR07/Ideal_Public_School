<?php
session_start();
include('../includes/config.php');

// Optional: Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $roll_no = trim($_POST['roll_no']);
    $class = $_POST['class'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for duplicate email
    $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "⚠️ Student with this email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, email, password, gender, dob, roll_no, class) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $password, $gender, $dob, $roll_no, $class);

        if ($stmt->execute()) {
            $success = "✅ Student added successfully!";
        } else {
            $error = "❌ Error adding student. Please try again.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">🔙 Back</a>
    <h3 class="mb-3">➕ Add New Student</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">-- Select Gender --</option>
                    <option value="Male">👦 Male</option>
                    <option value="Female">👧 Female</option>
                    <option value="Other">🧑 Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Roll Number</label>
                <input type="text" name="roll_no" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Class</label>
                <select name="class" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    <?php
                    $classes = ['LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
                    foreach ($classes as $cls) {
                        echo "<option value=\"$cls\">$cls</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 w-100">✅ Add Student</button>
    </form>
</div>
</body>
</html>
