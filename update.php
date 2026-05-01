<?php
include 'config.php';
$id=$_POST['id'];
$status=$_POST['status'];
$d=$_POST['date'];
$t=$_POST['time'];

$conn->query("UPDATE appointments SET
status='$status',
appointment_date=IF('$d'='',appointment_date,'$d'),
appointment_time=IF('$t'='',appointment_time,'$t')
WHERE id=$id");

header("Location: admin_panel.php");
