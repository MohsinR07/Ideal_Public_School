<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

include('../includes/config.php');

$teacher_id = $_SESSION['teacher_id'];
$success = '';
$error = '';

// Fetch current hashed password from DB
$stmt = $conn->prepare("SELECT password FROM teachers WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$stmt->bind_result($currentHashedPassword);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$old_password || !$new_password || !$confirm_password) {
        $error = "❗ Please fill in all fields.";
    } elseif (!password_verify($old_password, $currentHashedPassword)) {
        $error = "❌ Incorrect old password.";
    } elseif ($new_password !== $confirm_password) {
        $error = "❌ New passwords do not match.";
    } else {
        $hashedNewPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE teachers SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashedNewPassword, $teacher_id);
        if ($update->execute()) {
            $success = "✅ Password changed successfully.";
        } else {
            $error = "❌ Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f8ff; padding: 20px; }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 576px) {
            .container { padding: 20px; }
            h3 { font-size: 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">🔐 Change Password</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Old Password</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">✔️ Change Password</button>
    </form>

    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

</body>
</html>
