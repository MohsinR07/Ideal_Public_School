<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

include('../../includes/config.php');

// ✅ Error check if $conn failed
if (!isset($conn)) {
    die("❌ Database connection not established. Please check your config.php path.");
}

// Fetch all teachers
$result = $conn->query("SELECT * FROM teachers ORDER BY id DESC");
if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .btn-back {
            margin-bottom: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 20px;
                text-align: center;
            }

            .btn {
                font-size: 14px;
                margin-bottom: 5px;
            }

            .table th, .table td {
                font-size: 13px;
                padding: 8px;
            }

            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../dashboard.php" class="btn btn-secondary btn-back">🔙 Back to Dashboard</a>
        <h2>👨‍🏫 Manage Teachers</h2>
        <a href="add.php" class="btn btn-primary mb-3">➕ Add New Teacher</a>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success">✅ Teacher deleted successfully!</div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">✏️ Edit</a>
                                    <a href="mark_attendance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mb-1">🗓️ Mark</a>
                                    <a href="view_attendance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info mb-1">📊 View</a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure to delete this teacher?');">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No teachers found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
