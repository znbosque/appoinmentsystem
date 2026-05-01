<?php
session_start();
include 'config.php';


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}


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


$available_times = [];
for($h=6; $h<=16; $h++){
    $available_times[] = sprintf("%02d:00:00", $h);
}


$appointments_res = $conn->query("
    SELECT appointment_date, appointment_time, service, assigned_staff 
    FROM appointments 
    WHERE status != 'Canceled'
");
$appointments = [];
while($row = $appointments_res->fetch_assoc()){
    $appointments[$row['service']][$row['appointment_date']][$row['appointment_time']][] = $row['assigned_staff'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Availability Checker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<style>
body { font-family:'Poppins',sans-serif; background:#f2f6f8; }
.container { margin-top:50px; }
.card { border-radius:20px; padding:25px; box-shadow:0 10px 35px rgba(0,0,0,0.15); }
.card h3 { margin-bottom:20px; }

.badge { font-size:1rem; margin:5px; padding:10px 15px; border-radius:15px; }
.badge.available { background:#28a745; color:white; }
.badge.busy { background:#dc3545; color:white; cursor:not-allowed; }

.form-select, .form-control { border-radius:12px; }
</style>
</head>
<body>

<div class="container">
    <div class="card text-center">
        <h3><i class="bi bi-person-lines-fill"></i> Staff Availability Checker</h3>

        <div class="row g-3 justify-content-center mb-4">
            <div class="col-md-3">
                <select id="service_select" class="form-select">
                    <option value="">Select Service</option>
                    <?php foreach($staff_list as $service => $staffs): ?>
                        <option value="<?= $service ?>"><?= $service ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="date_select" class="form-control" placeholder="Select Date">
            </div>
            <div class="col-md-3">
                <select id="time_select" class="form-select">
                    <option value="">Select Time</option>
                    <?php foreach($available_times as $t): 
                        $display = date("h:i A", strtotime($t));
                    ?>
                        <option value="<?= $t ?>"><?= $display ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-4" id="staff_list_area">
           
        </div>

        <a href="admin.php" class="btn btn-secondary mt-4"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
let appointments = <?= json_encode($appointments) ?>;
let allStaff = <?= json_encode($staff_list) ?>;

flatpickr("#date_select", { dateFormat: "Y-m-d" });

const serviceSelect = document.getElementById("service_select");
const dateSelect = document.getElementById("date_select");
const timeSelect = document.getElementById("time_select");
const staffArea = document.getElementById("staff_list_area");

function updateStaffAvailability(){
    staffArea.innerHTML = '';
    const service = serviceSelect.value;
    const date = dateSelect.value;
    const time = timeSelect.value;
    if(!service || !date || !time) return;

    const bookedStaff = appointments[service]?.[date]?.[time] || [];
    const staffArray = allStaff[service] || [];

    staffArray.forEach(staff => {
        const badge = document.createElement('span');
        badge.classList.add('badge');
        if(bookedStaff.includes(staff)){
            badge.classList.add('busy'); 
        } else {
            badge.classList.add('available'); 
        }
        badge.innerText = staff + (bookedStaff.includes(staff) ? ' (Busy)' : ' (Available)');
        staffArea.appendChild(badge);
    });
}

serviceSelect.addEventListener('change', updateStaffAvailability);
dateSelect.addEventListener('change', updateStaffAvailability);
timeSelect.addEventListener('change', updateStaffAvailability);
</script>

</body>
</html>