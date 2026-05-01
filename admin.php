<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #cce6ff;
    margin:0;
}


.hero {
    height: 250px;
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                url('bgystem.jpg') no-repeat center center;
    background-size: cover;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    text-align:center;
    border-bottom-left-radius:50px;
    border-bottom-right-radius:50px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    position: relative;
}
.hero h1 {
    font-weight:700;
    font-size:2.5rem;
}
.hero p {
    font-size:1.2rem;
    opacity:0.9;
    margin:0;
}
.hero-logo {
    position:absolute;
    top:15px;
    left:15px;
    width:60px;
    height:auto;
    object-fit:contain;
    border-radius:10px;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}


.admin-card {
    margin-top:-50px;
    padding:30px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
    background:#e6f2ff; 
    text-align:center;
    transition:0.3s;
}
.admin-card:hover {
    transform: translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,0.25);
}
.admin-card h2 {
    font-weight:700;
    margin-bottom:25px;
    color:#004080; 
}


.btn-dashboard {
    border-radius:20px;
    font-weight:600;
    padding:12px 25px;
    margin:10px;
    transition:0.3s;
}
.btn-dashboard:hover {
    transform:scale(1.05);
    opacity:0.9;
}


.btn-primary { background:#3399ff; border:none; }
.btn-success { background:#66b3ff; border:none; color:#000; }
.btn-danger { background:#ff6666; border:none; }


footer {
    text-align:center;
    margin-top:50px;
    color:#555;
    font-size:0.9rem;
}
</style>
</head>

<body>

<div class="hero">
    <img src="logosystem.png" class="hero-logo" alt="Logo">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <b><?= htmlspecialchars($username) ?></b></p>
</div>

<div class="container mt-5">
    <div class="admin-card">
        <h2>Quick Actions</h2>

        <a href="staff_availability.php" class="btn btn-primary btn-dashboard">
            <i class="bi bi-person-lines-fill"></i> Check Staff Availability
        </a>

        <a href="admin_panel.php" class="btn btn-success btn-dashboard">
            <i class="bi bi-check2-square"></i> Open Appointment Approval
        </a>

        <br>

        <a href="admin_logout.php" class="btn btn-danger btn-dashboard">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<footer class="mt-5">
    &copy; <?= date('Y') ?> Barangay Appointment System. All Rights Reserved.
</footer>

</body>
</html>