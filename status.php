<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Appointment Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #c3ecff, #ffe6f2);
    font-family: Arial, sans-serif;
    min-height: 100vh;
}

.container {
    margin-top: 50px;
}

h3 {
    text-align: center;
    margin-bottom: 30px;
    color: #0d6efd;
    font-weight: 700;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

table {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    overflow: hidden;
}

th {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    color: #fff;
    text-align: center;
}

td {
    text-align: center;
    color: #555;
}

tr:hover {
    background: rgba(13,110,253,0.1);
    transform: scale(1.01);
    transition: 0.2s;
}

.btn-back {
    display:inline-block;
    margin-top:20px;
    width:70px;
    height:70px;
    background:linear-gradient(45deg,#0d6efd,#6610f2);
    color:#fff;
    border-radius:50%;
    text-align:center;
    line-height:70px;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="container">
    <h3>Appointment Status</h3>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Queue</th>
                <th>Name</th>
                <th>Service</th>
                <th>Staff</th>
                <th>Date</th>
                <th>Time</th>
                <th>Purpose/Reason</th> <!-- NEW -->
                <th>Additional Notes</th>   <!-- NEW -->
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $r = $conn->query("SELECT * FROM appointments");

        while($row = $r->fetch_assoc()){
            echo "<tr>
            <td>".htmlspecialchars($row['queue_number'])."</td>
            <td>".htmlspecialchars($row['fullname'])."</td>
            <td>".htmlspecialchars($row['service'])."</td>
            <td>".htmlspecialchars($row['assigned_staff'])."</td>
            <td>".htmlspecialchars($row['appointment_date'])."</td>";
        
            $time = strtotime($row['appointment_time']);
            $start = date("g:i", $time);
            $end = date("g:i", strtotime("+1 hour", $time));
        
        echo "<td>".$start." - ".$end."</td>
        
            <td>".htmlspecialchars($row['purpose'] ?? '—')."</td>
            <td>".htmlspecialchars($row['notes'] ?? '—')."</td>
            <td><b>".htmlspecialchars($row['status'])."</b></td>
        </tr>";
        }
        ?>

        </tbody>
    </table>

    <a href="index.php" class="btn-back">&#8592;</a>

</div>

</body>
</html>