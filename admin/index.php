<?php
session_start();
include('../includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['admin'] = $email;
        header("Location: dashboard.php");
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url('https://images.pexels.com/photos/8872665/pexels-photo-8872665.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .btn-back {
            margin-top: 10px;
            display: block;
            width: 100%;
            text-align: center;
        }

        @media (max-width: 576px) {
            .login-box {
                padding: 30px 20px;
            }

            h2 {
                font-size: 22px;
            }

            input, label {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form method="post">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <input type="submit" name="login" value="Login" class="btn btn-primary w-100">
            <?php if (!empty($error)) echo "<p class='text-danger mt-2'>$error</p>"; ?>
        </form>

        <a href="../index.php" class="btn btn-secondary btn-back">🔙 Back to Home</a>
    </div>
</body>
</html>
