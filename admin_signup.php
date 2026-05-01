<?php
session_start();
include 'config.php'; 
$error = '';

if(isset($_POST['signup'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = 'admin'; 

    $check = $conn->prepare("SELECT * FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $error = "Username already exists!";
    } else {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $hashed_pass, $role);
        $stmt->execute();

        echo "<script>alert('Admin account created!'); window.location='admin_login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Signup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family:'Poppins', sans-serif;
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.signup-card {
    background:white;
    border-radius:25px;
    padding:40px 30px;
    max-width:400px;
    width:100%;
    text-align:center;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
    transition:0.3s;
}

.signup-card:hover {
    transform: translateY(-5px);
    box-shadow:0 25px 50px rgba(0,0,0,0.3);
}

.signup-card h3 {
    font-weight:700;
    margin-bottom:25px;
    color:#333;
}

.signup-card .form-control {
    border-radius:15px;
    padding:12px 15px;
    margin-bottom:15px;
    box-shadow:none;
}

.signup-card .btn {
    border-radius:20px;
    padding:12px;
    font-weight:600;
    transition:0.3s;
}

.signup-card .btn:hover {
    transform: scale(1.05);
}

.signup-card a {
    text-decoration:none;
    color:#4facfe;
    font-weight:500;
}

.error-message {
    color:#d9534f;
    font-weight:500;
    margin-bottom:15px;
}

.signup-logo {
    width:70px;
    height:auto;
    margin-bottom:20px;
}
</style>
</head>
<body>

<div class="signup-card">
    <img src="logosystem.png" class="signup-logo" alt="Logo">
    <h3>Create Account</h3>

    <?php if($error): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>

        <button name="signup" class="btn btn-success w-100 mt-2">Sign Up</button>
    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="admin_login.php">Login here</a>
    </p>
</div>

</body>
</html>