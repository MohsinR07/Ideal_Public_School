<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include('../includes/config.php');

// Class List
$classes = ['LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];

// Get selected class
$selected_class = $_GET['class'] ?? $classes[0];

// Fetch students
$stmt = $conn->prepare("SELECT * FROM students WHERE class = ? ORDER BY roll_no ASC");
$stmt->bind_param("s", $selected_class);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .table-responsive {
            margin-top: 20px;
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 6px 10px;
            }

            select.form-select {
                font-size: 14px;
            }

            .table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Manage Students - <?= htmlspecialchars($selected_class) ?></h2>

        <form method="get" class="mb-3">
            <label for="class">Select Class:</label>
            <select name="class" id="class" onchange="this.form.submit()" class="form-select" style="max-width: 300px;">
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class ?>" <?= $selected_class === $class ? 'selected' : '' ?>><?= $class ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="mb-3">
            <a href="add_student.php?class=<?= urlencode($selected_class) ?>" class="btn btn-primary">➕ Add Student</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll No.</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['roll_no']) ?></td>
                            <td><?= htmlspecialchars($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['dob']) ?></td>
                            <td>
                                <a href="edit_student.php?id=<?= $row['id'] ?>&class=<?= urlencode($selected_class) ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                <a href="delete_student.php?id=<?= $row['id'] ?>&class=<?= urlencode($selected_class) ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-center">
            <a href="dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
