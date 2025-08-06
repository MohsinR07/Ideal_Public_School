<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

include('../includes/config.php');

$classes = [];
$results = [];
$studentInfo = null;
$selectedDate = $_GET['date'] ?? '';

// Step 1: Fetch class list
$res = $conn->query("SELECT DISTINCT class FROM students ORDER BY class");
while ($row = $res->fetch_assoc()) {
    $classes[] = $row['class'];
}

if (isset($_GET['class']) && isset($_GET['search'])) {
    $class = $_GET['class'];
    $search = $_GET['search'];

    // Get student info
    $stmt = $conn->prepare("SELECT * FROM students WHERE class = ? AND (roll_no = ? OR name LIKE ?)");
    $likeSearch = "%$search%";
    $stmt->bind_param("sss", $class, $search, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $studentInfo = $result->fetch_assoc();
        $studentId = $studentInfo['id'];

        // Fetch results
        if (!empty($selectedDate)) {
            $stmt2 = $conn->prepare("SELECT * FROM results WHERE student_id = ? AND date = ?");
            $stmt2->bind_param("is", $studentId, $selectedDate);
        } else {
            $stmt2 = $conn->prepare("SELECT * FROM results WHERE student_id = ?");
            $stmt2->bind_param("i", $studentId);
        }

        $stmt2->execute();
        $resResult = $stmt2->get_result();
        while ($row = $resResult->fetch_assoc()) {
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; padding: 20px; background-color: #f4f9ff; }
        h2, h3 { text-align: center; }
        form { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        .actions { text-align: right; margin-top: 10px; }
        .btn-back {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
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

<h2>📊 View Student Result</h2>

<form method="GET">
    <label>Class:</label>
    <select name="class" required>
        <option value="">-- Select Class --</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c ?>" <?= (isset($_GET['class']) && $_GET['class'] == $c) ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?>
    </select>

    <label>Roll No / Name:</label>
    <input type="text" name="search" placeholder="Enter Roll No or Name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" required>

    <label>Exam Date:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>">

    <button type="submit">🔍 Search</button>
</form>

<?php if ($studentInfo): ?>
    <h3>Result for: <?= $studentInfo['name'] ?> (Roll: <?= $studentInfo['roll_no'] ?> | Class: <?= $studentInfo['class'] ?>)</h3>

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
            $total = 0;
            $obtained = 0;
            foreach ($results as $r):
                $total += $r['total_marks'];
                $obtained += $r['marks_obtained'];
            ?>
            <tr>
                <td><?= $r['subject'] ?></td>
                <td><?= $r['marks_obtained'] ?></td>
                <td><?= $r['total_marks'] ?></td>
                <td><?= $r['exam_type'] ?></td>
                <td><?= $r['date'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td>Total</td>
                <td><?= $obtained ?></td>
                <td><?= $total ?></td>
                <td colspan="2">Percentage: <?= round(($obtained / $total) * 100, 2) ?>%</td>
            </tr>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No results found for this student on selected date.</p>
    <?php endif; ?>

<?php elseif (isset($_GET['search'])): ?>
    <p style="text-align: center;">No student found with that class and name/roll number.</p>
<?php endif; ?>

<div style="text-align: center; margin-top: 30px;">
    <a href="dashboard.php" class="btn-back">🔙 Back to Dashboard</a>
</div>

<script>
function downloadPDF() {
    const content = document.getElementById('resultTable').outerHTML;
    const style = `<style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #000; text-align: center; }
    </style>`;
    const win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Download PDF</title>');
    win.document.write(style);
    win.document.write('</head><body>');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>

</body>
</html>
