<?php
session_start();
include("../includes/config.php");

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();

        // ✅ Check hashed password using password_verify
        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            $_SESSION['student_class'] = $student['class'];
            $_SESSION['student_roll'] = $student['roll_no'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login - Ideal Public School</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
      max-width: 400px;
      margin: 100px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .login-card h3 {
      font-weight: bold;
    }

    @media (max-width: 576px) {
      .login-card {
        margin: 60px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="login-card">
    <!-- ✅ Back button inside the card -->
    <a href="../index.php" class="btn btn-sm btn-outline-secondary mb-3">🔙 Back to Home</a>

    <h3 class="text-center mb-4">🎓 Student Login</h3>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email ID</label>
        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
