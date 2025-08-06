<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>About Us - Ideal Public School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: url('https://images.pexels.com/photos/373488/pexels-photo-373488.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            max-width: 800px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        h2 {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .about-box {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="about-box">
        <h2>About Ideal Public School</h2>
        <p class="mt-3">
            Ideal Public School, Patranga was founded with the vision to deliver quality education in a nurturing environment.
            We aim to develop students academically, socially, and emotionally, preparing them for a better future.
        </p>
        <p>
            Our school offers education from LKG to Class 10, guided by experienced faculty and supported by modern teaching facilities.
            We believe every child has the potential to shine, and it’s our responsibility to bring out the best in them.
        </p>
        <p>
            <strong>Mission:</strong> To provide affordable, high-quality education that empowers students to become thoughtful, responsible citizens.
        </p>
        <p>
            <strong>Location:</strong> Patranga, Faizabad (Ayodhya), Uttar Pradesh
        </p>
        <a href="index.php" class="btn btn-primary mt-3">← Back to Home</a>
    </div>
</body>
</html>
