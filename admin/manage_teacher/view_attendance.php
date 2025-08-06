<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

include('../../includes/config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid teacher ID.");
}

$teacher_id = intval($_GET['id']);

// Get teacher info
$teacher_stmt = $conn->prepare("SELECT name FROM teachers WHERE id = ?");
$teacher_stmt->bind_param("i", $teacher_id);
$teacher_stmt->execute();
$teacher_stmt->bind_result($teacher_name);
$teacher_stmt->fetch();
$teacher_stmt->close();

if (!$teacher_name) {
    die("Teacher not found.");
}

// Get selected month or default to current month
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Validate format
if (!preg_match('/^\d{4}-\d{2}$/', $selected_month)) {
    $selected_month = date('Y-m');
}

// Fetch attendance records for selected month
$start_date = $selected_month . '-01';
$end_date = date('Y-m-t', strtotime($start_date));

$attendance_stmt = $conn->prepare("SELECT date, status FROM teacher_attendance WHERE teacher_id = ? AND date BETWEEN ? AND ? ORDER BY date DESC");
$attendance_stmt->bind_param("iss", $teacher_id, $start_date, $end_date);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
$attendance_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance - <?= htmlspecialchars($teacher_name) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ jsPDF & html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-print, #downloadPDF {
            float: right;
            margin-left: 10px;
        }

        .month-filter {
            max-width: 300px;
            margin-bottom: 20px;
        }

       @media (max-width: 767px) {
        h2 {
            font-size: 22px;
        }

       .btn-print {
            display: none !important; /* ✅ सिर्फ print बटन छिपेगा */
        }

        .table-responsive {
            font-size: 14px;
        }
}

    </style>
</head>
<body>
    <div class="container" id="attendance-container">
        <a href="index.php" class="btn btn-secondary mb-3">🔙 Back to Manage Teachers</a>

        <h2>
            📊 Attendance for: <?= htmlspecialchars($teacher_name) ?>
            <button onclick="window.print()" class="btn btn-success btn-sm btn-print">🖨️ Print</button>
            <button id="downloadPDF" class="btn btn-danger btn-sm">📄 Download PDF</button>
        </h2>

        <!-- Month filter -->
        <form method="get" class="month-filter">
            <input type="hidden" name="id" value="<?= $teacher_id ?>">
            <label for="month" class="form-label">📅 Select Month</label>
            <input type="month" id="month" name="month" value="<?= $selected_month ?>" class="form-control" onchange="this.form.submit()">
        </form>

        <?php if ($attendance_result && $attendance_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attendance_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td>
                                    <?php
                                        $status = strtolower(trim($row['status']));
                                        echo ($status === 'present') ? '✅ Present' : '❌ Absent';
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No attendance found for <?= date("F Y", strtotime($selected_month)) ?>.</div>
        <?php endif; ?>
    </div>

    <!-- ✅ JS PDF Export -->
    <script>
        document.getElementById('downloadPDF').addEventListener('click', function () {
            const container = document.getElementById('attendance-container');
            html2canvas(container, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jspdf.jsPDF('p', 'mm', 'a4');

                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save("Attendance-<?= htmlspecialchars($teacher_name) ?>.pdf");
            });
        });
    </script>
</body>
</html>
