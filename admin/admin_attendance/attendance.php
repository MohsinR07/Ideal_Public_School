<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../../includes/config.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

$classes = ['LKG', 'UKG', 'Class 1','Class 2','Class 3','Class 4','Class 5','Class 6','Class 7','Class 8','Class 9','Class 10'];

$results = [];
$showTable = false;

if (isset($_GET['filter'])) {
    $month = $_GET['month'] ?? '';
    $type = $_GET['type'] ?? '';
    $class = $_GET['class'] ?? '';
    $keyword = $_GET['keyword'] ?? '';

    if ($month && $type) {
        $from = date('Y-m-01', strtotime($month));
        $to = date('Y-m-t', strtotime($month));

        if ($type === 'student') {
            $query = "SELECT a.*, s.name, s.roll_no, s.class 
                      FROM student_attendance a 
                      JOIN students s ON a.student_id = s.id 
                      WHERE a.date BETWEEN ? AND ?";
            $params = [$from, $to];
            $types = "ss";

            if (!empty($class)) {
                $query .= " AND s.class = ?";
                $params[] = $class;
                $types .= "s";
            }

            if (!empty($keyword)) {
                $query .= " AND (s.name LIKE ? OR s.roll_no LIKE ?)";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $types .= "ss";
            }

            $query .= " ORDER BY a.date DESC";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $results = mysqli_stmt_get_result($stmt);
        }

        if ($type === 'teacher') {
            $query = "SELECT a.*, t.name 
                      FROM teacher_attendance a 
                      JOIN teachers t ON a.teacher_id = t.id 
                      WHERE a.date BETWEEN ? AND ?";
            $params = [$from, $to];
            $types = "ss";

            if (!empty($keyword)) {
                $query .= " AND t.name LIKE ?";
                $params[] = "%$keyword%";
                $types .= "s";
            }

            $query .= " ORDER BY a.date DESC";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $results = mysqli_stmt_get_result($stmt);
        }

        $showTable = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Filter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media(max-width:768px){ #printBtn{display:none;} }
        @media(min-width:769px){ #pdfBtn{display:none;} }
        .table td, .table th { font-size: 14px; white-space: nowrap; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center mb-4">📋 Attendance Filter</h2>

    <form method="get" class="row g-3">
        <input type="hidden" name="filter" value="1">

        <div class="col-md-4">
            <label>Select Month:</label>
            <input type="month" name="month" class="form-control" required value="<?= $_GET['month'] ?? '' ?>">
        </div>

        <div class="col-md-4">
            <label>Choose Type:</label>
            <select name="type" id="typeSelect" class="form-control" required onchange="toggleFields()">
                <option value="">Select</option>
                <option value="student" <?= ($_GET['type'] ?? '') === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="teacher" <?= ($_GET['type'] ?? '') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
            </select>
        </div>

        <div class="col-md-4" id="classGroup" style="display:none;">
            <label>Select Class:</label>
            <select name="class" class="form-control">
                <option value="">All</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c ?>" <?= ($c == ($_GET['class'] ?? '')) ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4" id="keywordGroup" style="display:none;">
            <label id="keywordLabel">Enter:</label>
            <input type="text" name="keyword" class="form-control" value="<?= $_GET['keyword'] ?? '' ?>">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <hr>

    <?php if ($showTable): ?>
        <div class="d-flex justify-content-end mb-3">
            <button id="pdfBtn" class="btn btn-success me-2">📄 Download PDF</button>
            <button id="printBtn" class="btn btn-secondary" onclick="window.print()">🖨️ Print</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <?php if ($_GET['type'] === 'student'): ?>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Class</th>
                        <?php else: ?>
                            <th>Teacher Name</th>
                        <?php endif; ?>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($results) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($results)): ?>
                            <tr>
                                <td><?= $row['date'] ?></td>
                                <?php if ($_GET['type'] === 'student'): ?>
                                    <td><?= $row['roll_no'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['class'] ?></td>
                                <?php else: ?>
                                    <td><?= $row['name'] ?></td>
                                <?php endif; ?>
                                <td><?= $row['status'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-danger">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- 🔙 Back Button -->
    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-dark">🔙 Back to Dashboard</a>
    </div>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('typeSelect').value;
    const classGroup = document.getElementById('classGroup');
    const keywordGroup = document.getElementById('keywordGroup');
    const label = document.getElementById('keywordLabel');

    if (type === 'student') {
        classGroup.style.display = 'block';
        keywordGroup.style.display = 'block';
        label.textContent = 'Enter Name or Roll No';
    } else if (type === 'teacher') {
        classGroup.style.display = 'none';
        keywordGroup.style.display = 'block';
        label.textContent = 'Enter Teacher Name';
    } else {
        classGroup.style.display = 'none';
        keywordGroup.style.display = 'none';
    }
}
toggleFields();
</script>

<!-- jsPDF for PDF download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.getElementById("pdfBtn")?.addEventListener("click", function () {
    import('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js').then(() => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        html2canvas(document.body).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = doc.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            doc.addImage(imgData, 'PNG', 10, 20, pdfWidth - 20, pdfHeight);
            doc.save("attendance-report.pdf");
        });
    });
});
</script>
</body>
</html>
