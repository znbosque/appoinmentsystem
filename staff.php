<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];


$staff_list = [
    'Barangay Service' => [
        'Juan Dela Cruz – Punong Barangay',
        'Antonio Reyes – Barangay Secretary',
        'Maria Santos – Barangay Clerk / Records Officer',
        'Liza Villanueva – Barangay Treasurer',
        'Rosa Mendoza – Barangay Health Worker',
        'Pedro Bautista – Barangay Kagawad / Councilor'
    ],
    'Certificate Request' => [
        'Mr. Angelo Ramirez',
        'Ms. Kristine Mendoza',
        'Mr. Mark Villanueva',
        'Ms. Sophia Santos',
        'Mr. Jason Cruz'
    ],
    'Medical Checkup' => [
        'Dr. Juan Santos',
        'Dr. Maria Velasco',
        'Dr. Carlos Mendoza',
        'Dr. Leah Navarro',
        'Dr. Roberto Cruz'
    ]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            margin:0;
            font-family: 'Arial', sans-serif;
            background:#f0f2f5;
        }

        
        .sidebar {
            width:240px;
            height:100vh;
            background:#A9BEB4;
            position:fixed;
            top:0;
            left:0;
            padding:20px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
        }
        .logo-side { text-align:center; margin-bottom:40px; }
        .logo-side img { width:110px; display:block; margin:0 auto; }
        .sidebar ul { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:15px; flex-grow:1; justify-content:center; }
        .sidebar ul li a {
            display:flex;
            align-items:center;
            gap:10px;
            padding:12px;
            border-radius:12px;
            text-decoration:none;
            color:#333;
            background:#F2F1E9;
            transition:0.3s;
        }
        .sidebar ul li a:hover { background:#ddd; }
        .sidebar ul li.active a { background:#777; color:#fff; }

        .logout-side a {
            display:block;
            background:#F2F1E9;
            padding:12px;
            border-radius:12px;
            text-align:center;
            text-decoration:none;
            color:#333;
        }

        
        .main-content { margin-left:260px; padding:30px; }

        h2 { margin-bottom:30px; text-align:center; color:#0d6efd; font-weight:700; }

        .service-card {
            background:#fff;
            padding:25px 20px;
            border-radius:20px;
            box-shadow:0 8px 20px rgba(0,0,0,0.1);
            margin-bottom:25px;
            transition:0.3s;
        }
        .service-card:hover { transform: translateY(-5px); }

        .service-card h4 {
            margin-bottom:15px;
            font-weight:600;
            color:#198754; 
            border-bottom:2px solid #e0e0e0;
            padding-bottom:5px;
        }

        .service-card ul {
            padding-left:20px;
        }

        .service-card ul li {
            margin-bottom:8px;
            font-size:0.95rem;
            color:#555;
            position: relative;
            padding-left:12px;
        }

        .service-card ul li::before {
            content:'\2022';
            color:#0d6efd; 
            position:absolute;
            left:0;
            font-weight:bold;
        }
    </style>
</head>

<body>


<div class="sidebar">
    <div class="logo-side">
        <img src="logosystem.png" alt="Logo">
    </div>

    <ul>
        <li><a href="index.php"><i class="bi bi-calendar"></i> Appointment</a></li>
        <li class="active"><a href="staff.php"><i class="bi bi-person-badge"></i> Staff</a></li>
        <li><a href="status.php"><i class="bi bi-search"></i> Check Status</a></li>
    </ul>

    <div class="logout-side">
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>


<div class="main-content">
    <h2>Staff List</h2>

    <?php foreach($staff_list as $service => $staffs): ?>
        <div class="service-card">
            <h4><?php echo $service; ?></h4>
            <ul>
                <?php foreach($staffs as $staff): ?>
                    <li><?php echo $staff; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>