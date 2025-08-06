<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard - Ideal Public School</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .dashboard-container {
      max-width: 800px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    .dashboard-container h2 {
      font-weight: bold;
    }

    .info-box {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .btn-dashboard {
      margin-bottom: 15px;
    }

    @media (max-width: 576px) {
      .dashboard-container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="dashboard-container">

      <h2 class="text-center mb-4">🎓 Welcome, <?php echo $_SESSION['student_name']; ?></h2>

      <div class="info-box mb-4">
        <p><strong>Email:</strong> <?php echo $_SESSION['student_email']; ?></p>
        <p><strong>Class:</strong> <?php echo $_SESSION['student_class']; ?></p>
        <p><strong>Roll No:</strong> <?php echo $_SESSION['student_roll']; ?></p>
      </div>

      <div class="row text-center">
        <div class="col-md-6">
          <a href="view_attendance.php" class="btn btn-outline-primary w-100 btn-dashboard">📅 View Attendance</a>
        </div>
        <div class="col-md-6">
          <a href="view_result.php" class="btn btn-outline-success w-100 btn-dashboard">📊 View Result</a>
        </div>
        <div class="col-md-6">
          <a href="change_password.php" class="btn btn-outline-warning w-100 btn-dashboard">🔑 Change Password</a>
        </div>
        <div class="col-md-6">
          <a href="logout.php" class="btn btn-outline-danger w-100 btn-dashboard">🚪 Logout</a>
        </div>
      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
