<?php
session_start();
include("../includes/config.php");

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$results = [];
$total_obtained = 0;
$total_marks = 0;
$error = "";

if (isset($_POST['view'])) {
    $selected_date = $_POST['result_date'];

    $stmt = $conn->prepare("SELECT * FROM results WHERE student_id = ? AND date = ?");
    $stmt->bind_param("is", $student_id, $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $results = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($results as $row) {
            $total_obtained += $row['marks_obtained'];
            $total_marks += $row['total_marks'];
        }

    } else {
        $error = "⚠️ Koi result nahi mila iss date ke liye.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Result - Ideal Public School</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1552318965-6e6be7484f43') no-repeat center center fixed;
      background-size: cover;
    }

    .result-container {
      max-width: 850px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    @media (max-width: 576px) {
      .result-container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="result-container">
    <h3 class="text-center mb-4">📊 View Your Result</h3>

    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-9 col-12">
        <input type="date" name="result_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="col-md-3 col-12">
        <button type="submit" name="view" class="btn btn-primary w-100">View Result</button>
      </div>
    </form>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-warning text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($results)) : ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>Subject</th>
              <th>Marks Obtained</th>
              <th>Total Marks</th>
              <th>Exam Type</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($results as $res): ?>
              <tr>
                <td><?php echo $res['subject']; ?></td>
                <td><?php echo $res['marks_obtained']; ?></td>
                <td><?php echo $res['total_marks']; ?></td>
                <td><?php echo $res['exam_type']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Totals -->
      <div class="mt-3">
        <h5><strong>Total Marks Obtained:</strong> <?php echo $total_obtained; ?> / <?php echo $total_marks; ?></h5>
        <h5><strong>Percentage:</strong> <?php echo round(($total_obtained / $total_marks) * 100, 2); ?>%</h5>
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
