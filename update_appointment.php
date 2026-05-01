<?php
session_start();
include 'config.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: login.php");
    exit;
}

if(isset($_POST['id'],$_POST['appointment_date'],$_POST['appointment_time'],$_POST['status'],$_POST['staff_assigned'])){
    $id=$_POST['id'];
    $date=$_POST['appointment_date'];
    $time=$_POST['appointment_time'];
    $status=$_POST['status'];
    $staff=$_POST['staff_assigned'];

    $stmt=$conn->prepare("UPDATE appointments SET appointment_date=?, appointment_time=?, status=?, staff_assigned=? WHERE id=?");
    $stmt->bind_param("ssssi",$date,$time,$status,$staff,$id);
    $stmt->execute();
}

header("Location: admin_appointments.php");
exit;
?>