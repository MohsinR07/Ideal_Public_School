<?php
session_start();
include("../includes/config.php");

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$attendance = [];
$present = 0;
$absent = 0;

$stmt = $conn->prepare("SELECT * FROM student_attendance WHERE student_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $attendance = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($attendance as $row) {
        if (strtolower($row['status']) === "present") {
            $present++;
        } else {
            $absent++;
        }
    }
}

$total_days = $present + $absent;
$percentage = ($total_days > 0) ? round(($present / $total_days) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Attendance - Ideal Public School</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1552318965-6e6be7484f43') no-repeat center center fixed;
      background-size: cover;
    }

    .attendance-container {
      max-width: 850px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    @media (max-width: 576px) {
      .attendance-container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="attendance-container">
    <h3 class="text-center mb-4">📅 Your Attendance Record</h3>

    <?php if (empty($attendance)): ?>
      <div class="alert alert-warning text-center">⚠️ Attendance record not found.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Class</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($attendance as $row): ?>
              <tr>
                <td><?php echo date("d M Y", strtotime($row['date'])); ?></td>
                <td><?php echo htmlspecialchars($row['class']); ?></td>
                <td>
                  <?php
                    if (strtolower($row['status']) === "present") {
                      echo "<span class='text-success fw-bold'>Present</span>";
                    } else {
                      echo "<span class='text-danger fw-bold'>Absent</span>";
                    }
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Summary -->
      <div class="mt-4">
        <h5><strong>Total Days:</strong> <?php echo $total_days; ?></h5>
        <h5><strong>Days Present:</strong> <?php echo $present; ?></h5>
        <h5><strong>Days Absent:</strong> <?php echo $absent; ?></h5>
        <h5><strong>Present Percentage:</strong> <?php echo $percentage; ?>%</h5>
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
