<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacherName = $_SESSION['teacher_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            border-bottom: 1px solid #495057;
        }

        .sidebar a:hover {
            background-color: #495057;
            padding-left: 10px;
            transition: 0.2s ease-in-out;
        }

        .card-title {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            .card-title {
                font-size: 1rem;
            }
            h2 {
                font-size: 1.25rem;
            }
            .card-text {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar: full width on mobile, 3 cols on desktop -->
        <div class="col-12 col-md-3 sidebar">
            <h4 class="text-center mb-4">📚 Teacher Panel</h4>
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="mark_student_attendance.php">📝 Mark Student Attendance</a>
            <a href="add_marks.php">📚 Add Student Marks</a>
            <a href="mark_my_attendance.php">🕒 Mark My Attendance</a>
            <a href="view_result.php">📊 View Results</a>
            <a href="view_attendance.php">📅 View Attendance</a>
            <a href="add_student.php">➕ Add Student</a> <!-- ✅ NEW -->
            <a href="change_password.php">🔐 Change Password</a>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>
        </div>

        <!-- Content: full width on mobile, 9 cols on desktop -->
        <div class="col-12 col-md-9 p-4">
            <h2 class="mb-3">Welcome, <?= htmlspecialchars($teacherName) ?> 👋</h2>
            <p>This is your dashboard. Use the menu to manage students, attendance, results, and your profile.</p>

            <!-- Cards Grid -->
            <div class="row g-3 mt-3">

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">📝 Mark Student Attendance</h5>
                            <p class="card-text">Record attendance for students in your class.</p>
                            <a href="mark_student_attendance.php" class="btn btn-primary btn-sm w-100">Mark Attendance</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">📚 Add Student Marks</h5>
                            <p class="card-text">Enter marks for students in your subject.</p>
                            <a href="add_marks.php" class="btn btn-success btn-sm w-100">Add Marks</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">📊 View Results</h5>
                            <p class="card-text">Check student performance and result history.</p>
                            <a href="view_result.php" class="btn btn-info btn-sm w-100">View Results</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">📅 View Attendance</h5>
                            <p class="card-text">Review and print student attendance reports.</p>
                            <a href="view_attendance.php" class="btn btn-warning btn-sm w-100">View Attendance</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">🕒 Mark Your Own Attendance</h5>
                            <p class="card-text">Submit your own attendance (Only for today).</p>
                            <a href="mark_my_attendance.php" class="btn btn-secondary btn-sm w-100">Mark My Attendance</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">🔐 Change Password</h5>
                            <p class="card-text">Update your login password securely.</p>
                            <a href="change_password.php" class="btn btn-dark btn-sm w-100">Change Password</a>
                        </div>
                    </div>
                </div>

                <!-- ✅ Add Student Card -->
                <div class="col-12 col-sm-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">➕ Add Student</h5>
                            <p class="card-text">Register a new student to your class records.</p>
                            <a href="add_student.php" class="btn btn-outline-primary btn-sm w-100">Add Student</a>
                        </div>
                    </div>
                </div>

            </div> <!-- row -->
        </div> <!-- content -->
    </div> <!-- row -->
</div> <!-- container -->

</body>
</html>
