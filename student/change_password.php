<?php
session_start();
include("../includes/config.php");

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        $message = "<div class='alert alert-danger'>❌ New password and confirm password do not match.</div>";
    } else {
        // Get current hashed password
        $stmt = $conn->prepare("SELECT password FROM students WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if (password_verify($old, $data['password'])) {
            $hashed_new = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE students SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed_new, $student_id);
            $update->execute();

            $message = "<div class='alert alert-success'>✅ Password changed successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Incorrect old password.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password - Ideal Public School</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1584697964154-6f4e7b4f81f3') no-repeat center center fixed;
      background-size: cover;
    }

    .password-container {
      max-width: 500px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    @media (max-width: 576px) {
      .password-container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="password-container">
    <h3 class="text-center mb-4">🔐 Change Password</h3>

    <?php echo $message; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Old Password</label>
        <input type="password" name="old_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Update Password</button>
    </form>

    <div class="text-center mt-3">
      <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
