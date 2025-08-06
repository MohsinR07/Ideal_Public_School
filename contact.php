<?php
session_start();
include('includes/config.php');

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "✅ Your message has been submitted successfully!";
        } else {
            $error = "❌ Failed to store the message. Please try again later.";
        }
    } else {
        $error = "❗ Please fill all the fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Ideal Public School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: url('https://images.pexels.com/photos/301926/pexels-photo-301926.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            max-width: 700px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .contact-box {
                padding: 25px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="contact-box">
        <h2>Contact Us</h2>

        <ul class="list-unstyled mb-4">
            <li><strong>School Name:</strong> Ideal Public School, Patranga</li>
            <li><strong>Email:</strong> idealpublicschool@gmail.com</li>
            <li><strong>Phone:</strong> +91 9876543210</li>
            <li><strong>Address:</strong> Patranga, Ayodhya (Faizabad), Uttar Pradesh</li>
        </ul>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
            <a href="index.php" class="btn btn-secondary ms-2">Back to Home</a>
        </form>
    </div>
</body>
</html>
