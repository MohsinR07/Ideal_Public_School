<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../includes/config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

$classes = ['LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
$results = [];
$studentInfo = null;
$filterDate = $_GET['date'] ?? '';

if (isset($_GET['class']) && isset($_GET['search'])) {
    $class = $_GET['class'];
    $keyword = $_GET['search'];

    $studentQuery = mysqli_query($conn, "SELECT * FROM students WHERE class='$class' AND (roll_no='$keyword' OR name LIKE '%$keyword%')");
    if (mysqli_num_rows($studentQuery) > 0) {
        $studentInfo = mysqli_fetch_assoc($studentQuery);
        $studentId = $studentInfo['id'];

        $query = "SELECT * FROM results WHERE student_id='$studentId'";
        if (!empty($filterDate)) {
            $query .= " AND date='$filterDate'";
        }

        $resultQuery = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($resultQuery)) {
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; padding: 20px; background-color: #f9f9f9; }
        h2, h3 { text-align: center; }
        form { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        .actions { text-align: right; margin-top: 10px; }
        .btn-back { padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; text-decoration: none; }
        .btn-back:hover { background-color: #5a6268; }

        @media (max-width: 768px) {
            .actions button.desktop { display: none; }
        }
        @media (min-width: 769px) {
            .actions button.mobile { display: none; }
        }
    </style>
</head>
<body>

<h2>🎓 View Student Result</h2>

<form method="get">
    <label>Class:</label>
    <select name="class" required>
        <option value="">-- Select Class --</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c ?>" <?= (isset($_GET['class']) && $_GET['class'] == $c) ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?>
    </select>

    <label>Roll No / Name:</label>
    <input type="text" name="search" placeholder="Enter roll no or name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" required>

    <label>📅 Date:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($filterDate) ?>">

    <button type="submit">🔍 Search</button>
</form>

<?php if ($studentInfo): ?>
    <h3>Result for: <?= $studentInfo['name'] ?> (<?= $studentInfo['roll_no'] ?>, <?= $studentInfo['class'] ?>)</h3>

    <?php if (count($results) > 0): ?>
        <div class="actions">
            <button onclick="window.print()" class="desktop">🖨️ Print</button>
            <button onclick="downloadPDF()" class="mobile">⬇️ Download PDF</button>
        </div>

        <table id="resultTable">
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Exam Type</th>
                <th>Date</th>
            </tr>
            <?php
            $totalMarks = 0;
            $totalObtained = 0;
            foreach ($results as $res):
                $totalMarks += $res['total_marks'];
                $totalObtained += $res['marks_obtained'];
            ?>
            <tr>
                <td><?= $res['subject'] ?></td>
                <td><?= $res['marks_obtained'] ?></td>
                <td><?= $res['total_marks'] ?></td>
                <td><?= $res['exam_type'] ?></td>
                <td><?= $res['date'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="font-weight:bold;">
                <td>Total</td>
                <td><?= $totalObtained ?></td>
                <td><?= $totalMarks ?></td>
                <td colspan="2">Percentage: <?= round(($totalObtained / $totalMarks) * 100, 2) ?>%</td>
            </tr>
        </table>
    <?php else: ?>
        <p>No result data found for this student<?= $filterDate ? " on $filterDate" : '' ?>.</p>
    <?php endif; ?>

<?php elseif (isset($_GET['search'])): ?>
    <p>No student found with that class & roll/name combination.</p>
<?php endif; ?>

<!-- Back Button -->
<div style="text-align:center; margin-top:30px;">
    <a href="dashboard.php" class="btn-back">🔙 Back to Dashboard</a>
</div>

<script>
function downloadPDF() {
    const printContents = document.getElementById('resultTable').outerHTML;
    const style = `<style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
    </style>`;
    const win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Download PDF</title>');
    win.document.write(style);
    win.document.write('</head><body>');
    win.document.write(printContents);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>

</body>
</html>
