<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

include('../includes/config.php');
$teacher_id = $_SESSION['teacher_id'];

$my_attendance = [];
$student_attendance = [];
$classList = [];

$month = $_GET['month'] ?? date('Y-m');
$class = $_GET['class'] ?? '';
$keyword = $_GET['search'] ?? '';

// Get class list
$result = $conn->query("SELECT DISTINCT class FROM students ORDER BY class");
while ($row = $result->fetch_assoc()) {
    $classList[] = $row['class'];
}

// Fetch teacher's own attendance
$stmt = $conn->prepare("SELECT date, status FROM teacher_attendance WHERE teacher_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
$stmt->bind_param("is", $teacher_id, $month);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $my_attendance[] = $row;
}

// Fetch student attendance (only if class and search given)
if ($class && $keyword) {
    $stmt = $conn->prepare("SELECT id, name, roll_no FROM students WHERE class = ? AND (roll_no = ? OR name LIKE ?)");
    $likeKeyword = "%$keyword%";
    $stmt->bind_param("sss", $class, $keyword, $likeKeyword);
    $stmt->execute();
    $studentResult = $stmt->get_result();

    if ($studentRow = $studentResult->fetch_assoc()) {
        $student_id = $studentRow['id'];
        $stmt = $conn->prepare("SELECT date, status FROM student_attendance WHERE student_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
        $stmt->bind_param("is", $student_id, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $student_attendance[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>📅 View Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
        h2, h3 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        form { text-align: center; margin-bottom: 20px; }
        select, input[type="month"], input[type="text"], button {
            padding: 8px; font-size: 16px; margin: 5px;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 30px;
        }
        .btn-back:hover { background-color: #5a6268; }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            th, td {
                text-align: left;
                padding: 8px;
                border: none;
                border-bottom: 1px solid #ccc;
            }
            tr { margin-bottom: 15px; }
        }
    </style>
</head>
<body>

<h2>👨‍🏫 My Attendance (<?= htmlspecialchars($month) ?>)</h2>
<table>
    <tr>
        <th>Date</th>
        <th>Status</th>
    </tr>
    <?php foreach ($my_attendance as $row): ?>
        <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($my_attendance)): ?>
        <tr><td colspan="2">No attendance found.</td></tr>
    <?php endif; ?>
</table>

<hr style="margin: 40px 0;">

<h2>👩‍🎓 View Student Attendance</h2>

<form method="GET">
    <label>Select Month:</label>
    <input type="month" name="month" value="<?= htmlspecialchars($month) ?>" required>

    <label>Select Class:</label>
    <select name="class" required>
        <option value="">-- Class --</option>
        <?php foreach ($classList as $c): ?>
            <option value="<?= $c ?>" <?= $c == $class ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?>
    </select>

    <label>Name / Roll:</label>
    <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" required>

    <button type="submit">🔍 Search</button>
</form>

<?php if ($class && $keyword): ?>
    <?php if (!empty($student_attendance)): ?>
        <h3>📘 Attendance for <?= htmlspecialchars($keyword) ?> (<?= htmlspecialchars($class) ?>)</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($student_attendance as $row): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No student attendance found for this class and keyword.</p>
    <?php endif; ?>
<?php endif; ?>

<div style="text-align:center;">
    <a href="dashboard.php" class="btn-back">🔙 Back to Dashboard</a>
</div>

</body>
</html>
