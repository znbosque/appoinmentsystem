<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$error = '';
$success = '';


$stmt = $conn->prepare("SELECT profile FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$current_profile = !empty($user['profile']) ? $user['profile'] : '';

if(isset($_POST['submit'])){
    if(isset($_FILES['profile']) && $_FILES['profile']['error'] == 0){
        $allowed_ext = ['jpg','jpeg','png','gif'];
        $file_name = $_FILES['profile']['name'];
        $file_tmp  = $_FILES['profile']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed_ext)){
            $new_name = $username.'_'.time().'.'.$ext;
            $upload_dir = 'uploads/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            if(move_uploaded_file($file_tmp, $upload_dir.$new_name)){
                
                $update = $conn->prepare("UPDATE users SET profile=? WHERE username=?");
                $update->bind_param("ss", $new_name, $username);
                $update->execute();

                $success = "Profile updated successfully!";
            } else {
                $error = "Failed to upload file!";
            }
        } else {
            $error = "Invalid file type! Only JPG, PNG, GIF allowed.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Profile Picture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {background:#f2f6f8; font-family:Arial; padding-top:50px;}
        .card {max-width:400px; margin:auto; padding:30px; border-radius:20px; box-shadow:0 10px 25px rgba(0,0,0,0.1);}
        .profile-img {width:120px; height:120px; object-fit:cover; border-radius:50%; margin:auto; display:block; border:4px solid #4facfe;}
        .btn {border-radius:20px; font-weight:600;}
        .msg {text-align:center; margin-bottom:15px;}
        .msg.success {color:green;}
        .msg.error {color:red;}
    </style>
</head>
<body>

<div class="card text-center">
    <h3>Change Profile Picture</h3>
    <?php if($success) echo "<p class='msg success'>$success</p>"; ?>
    <?php if($error) echo "<p class='msg error'>$error</p>"; ?>

    <img src="<?= !empty($current_profile) ? 'uploads/'.$current_profile : 'profile.png' ?>" class="profile-img" alt="Profile">

    <form method="post" enctype="multipart/form-data" class="mt-3">
        <input type="file" name="profile" class="form-control mb-3" accept="image/*" required>
        <button type="submit" name="submit" class="btn btn-primary w-100">Upload</button>
    </form>

    <a href="index.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
</div>

</body>
</html>