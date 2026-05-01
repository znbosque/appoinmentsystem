<?php
session_start();
include 'config.php';

$error = '';


if(isset($_GET['timeout']) && $_GET['timeout'] == '1'){
    $error = "Your session has expired. Please login again.";
}

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();

        if(password_verify($password, $row['password'])){
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            
            if($row['role'] == 'admin'){
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#4facfe,#00f2fe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-box {
            display: flex;
            width: 850px;
            max-width: 95%;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

       
        .left-panel {
            background: linear-gradient(135deg,#1e3c72,#2a5298);
            width: 40%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            padding: 30px;
        }

        .left-panel img {
            width: 170px;
            margin-bottom: 15px;
        }

        
        .right-panel {
            width: 60%;
            padding: 40px 30px;
            text-align: center;
        }

        .right-panel h3 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .btn {
            border-radius: 20px;
            padding: 12px;
            font-weight: 600;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #4facfe;
        }

        
        @media(max-width:768px){
            .container-box{
                flex-direction: column;
            }
            .left-panel, .right-panel{
                width:100%;
            }
        }
    </style>
</head>

<body>

<div class="container-box">

   
    <div class="left-panel">
        <img src="logosystem.png" alt="Logo">
        <h4>Welcome back!</h4>
        <p>Appointment System</p>
    </div>

    
    <div class="right-panel">

        <h3>Login</h3>

        <?php if($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>

            <button name="login" class="btn btn-primary w-100 mb-3">Login</button>
        </form>

        <small class="text-muted">
            New to our system? <a href="signup.php">Sign up here</a>
        </small>

    </div>

</div>

</body>
</html>