<?php
include 'config.php';


if(!isset($_SESSION['admin'])){
    header("Location: admin.php");
    exit;
}


if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM appointments WHERE id=$id");
}


header("Location: admin_panel.php");
exit;
?>
