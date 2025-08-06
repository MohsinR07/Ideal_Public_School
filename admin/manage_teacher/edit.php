<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit;
}

include('../../includes/config.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Invalid teacher ID.");
}

$id = intval($_GET['id']);
$success = $error = "";

$stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();

if (!$teacher) {
    die("❌ Teacher not found.");
}

// Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);

    if ($name && $email && $subject) {
        $update = $conn->prepare("UPDATE teachers SET name = ?, email = ?, subject = ? WHERE id = ?");
        $update->bind_param("sssi", $name, $email, $subject, $id);
        if ($update->execute()) {
            $success = "✅ Teacher updated successfully!";
            $teacher['name'] = $name;
            $teacher['email'] = $email;
            $teacher['subject'] = $subject;
        } else {
            $error = "❌ Update failed: " . $update->error;
        }
        $update->close();
    } else {
        $error = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher - Admin Panel</title>
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
            background-color: rgba(255,255,255,0.95);
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">🔙 Back to Manage Teachers</a>
        <h2 class="mb-4">✏️ Edit Teacher Info</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">👨‍🏫 Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">📧 Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($teacher['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">📘 Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($teacher['subject']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
        </form>
    </div>
</body>
</html>
