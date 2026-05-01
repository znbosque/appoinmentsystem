<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT id, username, profile, email, contact FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$profile_img = !empty($user['profile']) ? 'uploads/'.$user['profile'] : 'profile.png';
?>

<!DOCTYPE html>
<html>
<head>
<title>Appointment System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f8f9fa;
}


.sidebar {
    width: 240px;
    height: 100vh;
    background: #A9BEB4;
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 999;
}

.logo-side {
    text-align: center;
    margin-bottom: 20px; 
}
.logo-side img {
    width: 110px;
    display: block;
    margin: 0 auto;
}


.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 40px;           
    justify-content:center; 
    flex-grow: 1;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 12px;
    text-decoration: none;
    color: #333;
    background: #F2F1E9;
    transition: 0.3s;
}
.sidebar ul li a:hover {
    background: #ddd;
}
.sidebar ul li.active a {
    background: #777;
    color: #fff;
}

.logout-side a {
    display: block;
    background: #F2F1E9;
    padding: 12px;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: #333;
}


.main-content {
    margin-left: 260px;
    padding: 20px 30px;
}


.header {
    background: linear-gradient(rgba(13,110,253,0.6), rgba(235,238,241,0.6)), 
                url('bgystem.jpg') no-repeat center center;
    background-size: cover;
    color: #000;
    padding: 60px 20px 100px 20px;
    text-align: center;
    position: relative;
    border-bottom-left-radius: 25px;
    border-bottom-right-radius: 25px;
}

.header h1 {
    font-weight: 700;
    font-size: 2.5rem;
}

.profile-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
}


.services-section {
    background: #fff;
    padding: 60px 20px;
    margin-top: -50px;
    border-radius: 25px 25px 0 0;
}

.card {
    border-radius: 20px;
    padding: 25px 15px;
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-8px);
}

.service-icon {
    font-size: 45px;
    color: #0d6efd;
    margin-bottom: 15px;
}

.btn-custom {
    border-radius: 20px;
    font-weight: 600;
}
</style>
</head>
<body>


<div class="sidebar">
    <div class="logo-side">
        <img src="logosystem.png" alt="Logo">
    </div>

    <ul>
        <li class="active"><a href="index.php"><i class="bi bi-calendar"></i> Appointment</a></li>
        <li><a href="staff.php"><i class="bi bi-person-badge"></i> Staff</a></li>
        <li><a href="status.php"><i class="bi bi-search"></i> Check Status</a></li>
    </ul>

    <div class="logout-side">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>


<div class="main-content">


<div class="header">
    <h1>Welcome to Appointment System</h1>
    <p>Hello, <b><?php echo htmlspecialchars($username); ?></b>!</p>
    <p>"Maayos na Schedule, Maayos na Serbisyo."</p>
    <img src="<?php echo $profile_img; ?>" class="profile-img" data-bs-toggle="modal" data-bs-target="#profileModal">
</div>


<div class="modal fade" id="profileModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3">
        <img src="<?php echo $profile_img; ?>" class="rounded-circle mb-3" style="width:100px;">
        <h5><?php echo htmlspecialchars($user['username']); ?></h5>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
        <p><?php echo htmlspecialchars($user['contact']); ?></p>
        <a href="profile_upload.php" class="btn btn-primary">Change Profile</a>
    </div>
  </div>
</div>


<div class="services-section container">
    <h2 class="text-center mb-4">Our Services</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card">
                <i class="bi bi-building service-icon"></i>
                <h5>Barangay Service</h5>
                <a href="book.php?service=Barangay Service" class="btn btn-primary btn-custom">Book</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <i class="bi bi-file-earmark-text service-icon"></i>
                <h5>Certificate Request</h5>
                <a href="book.php?service=Certificate Request" class="btn btn-success btn-custom">Book</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <i class="bi bi-heart-pulse service-icon"></i>
                <h5>Medical Checkup</h5>
                <a href="book.php?service=Medical Checkup" class="btn btn-danger btn-custom">Book</a>
            </div>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>