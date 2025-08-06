<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ideal Public School Aliyabad | Best School in Ayodhya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO + Social Meta Tags -->
    <meta name="description" content="Ideal Public School Patranga is a top school in Ayodhya district offering quality education from LKG to Class 10. Join us for a bright future.">
    <meta name="keywords" content="Ideal Public School, Patranga School, Ayodhya Best School, LKG to Class 10 School, School Admission in Patranga, Ideal School UP, School near Patranga, UP Board School">
    <meta name="author" content="Ideal Public School">
    <meta property="og:title" content="Ideal Public School Aliyabad">
    <meta property="og:description" content="Top English-medium school in Aliyabad, Ayodhya from LKG to Class 10.">
    <meta property="og:image" content="images/ideal_logo.jpg">
    <meta property="og:url" content="https://idealpublicschoolpatranga.wuaze.com">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/ideal_logo.jpg">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('https://images.pexels.com/photos/296302/pexels-photo-296302.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .nav-link {
            margin: 4px 6px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #343a40 !important;
            color: #fff !important;
        }
        .logo {
            height: 40px;
            margin-right: 10px;
        }

        .overlay {
            background: rgba(255, 255, 255, 0.96);
            padding: 40px 20px;
            border-radius: 15px;
            max-width: 700px;
            margin: 60px auto;
            text-align: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .typewriter {
            overflow: hidden;
            white-space: nowrap;
            animation: typing 4s steps(30, end) infinite, blink-caret .75s step-end infinite;
            font-size: 22px;
            font-weight: bold;
            border-right: .15em solid orange;
            letter-spacing: .15em;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: orange; }
        }

        footer {
            background-color: rgba(255,255,255,0.9);
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #555;
            margin-top: auto;
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .typewriter {
                font-size: 18px;
            }

            .overlay {
                margin: 30px 10px;
                padding: 25px 15px;
            }

            .navbar-collapse {
                background-color: rgba(255, 255, 255, 0.95);
                padding: 10px;
                border-radius: 10px;
            }

            .nav-item {
                margin-bottom: 6px;
            }

            .nav-link {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg px-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="images/ideal_logo.jpg" class="logo" alt="School Logo">
            <span class="fw-bold">Ideal Public School</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link bg-warning text-dark" href="index.php">Home</a></li>
                <li class="nav-item">
                <a class="nav-link" href="about.php" style="background-color: #90EE90; color: #000;">
                About Us
                </a>
                </li>
                <li class="nav-item"><a class="nav-link bg-danger-subtle text-dark" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link bg-primary-subtle text-dark" href="admin/index.php">Admin</a></li>
                <li class="nav-item"><a class="nav-link bg-success-subtle text-dark" href="teacher/login.php">Teacher</a></li>
                <li class="nav-item"><a class="nav-link bg-info-subtle text-dark" href="student/index.php">Student</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Center Welcome Box -->
<div class="overlay">
    <h1 class="typewriter">Welcome to Ideal Public School Aliyabad</h1>
    <p class="mt-3">Please choose your portal from the menu above to proceed.</p>
</div>

<!-- Footer -->
<footer>
    © <?= date('Y') ?> Ideal Public School Aliyabad. All rights reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
