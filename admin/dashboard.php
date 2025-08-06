<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// 👇 Cache control headers to prevent back-button access after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ JavaScript to prevent mobile browser back-cache -->
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && performance.getEntriesByType("navigation")[0].type === "back_forward")) {
                window.location.reload();
            }
        });
    </script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url('https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .dashboard-box h2 {
            margin-bottom: 25px;
            font-weight: bold;
        }

        .dashboard-box a {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            .dashboard-box {
                padding: 30px 15px;
            }

            .dashboard-box h2 {
                font-size: 22px;
            }

            .dashboard-box a {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-box">
        <h2>Welcome, Admin</h2>
        <a href="students.php" class="btn btn-primary">👩‍🎓 Manage Students</a>
        <a href="manage_teacher/index.php" class="btn btn-success">👨‍🏫 Manage Teachers</a>
        <a href="admin_attendance/attendance.php" class="btn btn-warning">🗓️ Attendance</a>
        <a href="view_result.php" class="btn btn-info">📊 View Results</a>
        <a href="messages.php" class="btn btn-secondary">📩 View Messages</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
