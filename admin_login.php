<?php
session_start();
include 'config.php';
$error = '';

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role='admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            header("Location: admin.php");
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login -Appointment System</title>
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


.login-card {
    display:flex;
    background:white;
    border-radius:25px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
    overflow:hidden;
    max-width:800px;
    width:100%;
}


.login-logo {
    flex:1;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:40px;
    color:white;
}

.login-logo img {
    width:150px;
    height:auto;
    margin-bottom:15px;
}

.login-logo .welcome-text {
    font-size:1.2rem;
    font-weight:600;
    text-align:center;
}


.login-form {
    flex:2;
    padding:40px 30px;
}

.login-form h2 {
    font-weight:700;
    margin-bottom:25px;
    color:#333;
    text-align:center;
}

.login-form .form-control {
    border-radius:15px;
    padding:12px 15px;
    margin-bottom:15px;
}

.login-form .btn {
    border-radius:20px;
    padding:12px;
    font-weight:600;
    transition:0.3s;
}

.login-form .btn:hover {
    transform: scale(1.05);
}

.error-message {
    color:#d9534f;
    font-weight:500;
    margin-bottom:15px;
}

.login-form a {
    text-decoration:none;
    color:#4facfe;
    font-weight:500;
}
</style>
</head>
<body>


<div class="login-card">
    
    <div class="login-logo">
        <img src="logosystem.png" alt="Logo">
        <div class="welcome-text">Welcome back!</div>
    </div>

    
    <div class="login-form">
        <h2>Login</h2> 
        
        <?php if($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button name="login" class="btn btn-primary w-100 mt-2">Login</button>
        </form>

        <p class="mt-3 text-center">
            Don't have an admin account? <a href="admin_signup.php">Sign up</a>
        </p>
    </div>
</div>

</body>
</html>