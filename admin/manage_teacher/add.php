<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

include('../../includes/config.php');

$name = $email = $phone = $subject = $joined_date = $password = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $joined_date = trim($_POST['joined_date']);
    $password = trim($_POST['password']);

    if ($name && $email && $phone && $subject && $joined_date && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO teachers (name, email, phone, subject, joined_date, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $joined_date, $hashedPassword);

        if ($stmt->execute()) {
            $success = "✅ Teacher added successfully!";
            $name = $email = $phone = $subject = $joined_date = $password = "";
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Teacher - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1600&q=80');
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
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .container { padding: 20px; }
            h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="btn btn-secondary mb-3">🔙 Back to Manage Teachers</a>
    <h2>➕ Add New Teacher</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">👨‍🏫 Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">📧 Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">📞 Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">📘 Subject</label>
                <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($subject) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">📅 Joining Date</label>
                <input type="date" name="joined_date" class="form-control" value="<?= htmlspecialchars($joined_date) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">🔑 Password</label>
                <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($password) ?>" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary w-100">✅ Add Teacher</button>
        </div>
    </form>
</div>
</body>
</html>
