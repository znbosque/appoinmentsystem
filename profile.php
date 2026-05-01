<?php
session_start();

// Redirect to login if not logged in
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

// Dummy user data
$user = [
    'name' => 'Kaycee Madayag',
    'email' => 'kayceemadayag@gmail.com',
    'contact' => '09123456789',
    'address' => 'Zone 3 Balza, Malinao, Albay',
    'birthdate' => '2004-12-18',
    'profile_image' => '803ed23e-7c13-47de-aaa9-3a2098cdd3c8.jpg'
];

// Handle logout
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #c3ecff, #ffe6f2);
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-card {
            background: #fff;
            border-radius: 25px;
            padding: 40px 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2), 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3), 0 10px 20px rgba(0,0,0,0.2);
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #0d6efd;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            margin-bottom: 25px;
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: rotateY(15deg) rotateX(5deg) scale(1.05);
        }

        h4 {
            font-weight: 700;
            margin-bottom: 15px;
            color: #0d6efd;
        }

        p {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0d6efd, #6610f2);
            border: none;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #ff073a);
            border: none;
            transition: transform 0.2s;
        }

        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="profile-card text-center">

    <!-- Profile Image -->
    <img src="<?= $user['profile_image'] ?>" alt="Profile" class="profile-img">

    <!-- User Details -->
    <h4><?= $user['name'] ?></h4>
    <p><strong>Email:</strong> <?= $user['email'] ?></p>
    <p><strong>Contact:</strong> <?= $user['contact'] ?></p>
    <p><strong>Address:</strong> <?= $user['address'] ?></p>
    <p><strong>Birthdate:</strong> <?= $user['birthdate'] ?></p>

    <div class="d-flex justify-content-between mt-4">
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
        <a href="profile.php?logout=1" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>
