<?php
session_start();
include 'config.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$max_per_slot = 1;


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


$appointments = $conn->query("SELECT * FROM appointments ORDER BY appointment_date, appointment_time");


$count_pending = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status='pending'")->fetch_assoc()['total'];
$count_completed = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status='completed'")->fetch_assoc()['total'];
$count_canceled = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status='canceled'")->fetch_assoc()['total'];
$count_rescheduled = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status='rescheduled'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>

body {
    background: #cce6ff; 
    font-family:'Segoe UI', sans-serif;
}

.status-pending { background:#fff3cd; }
.status-completed { background:#d4edda; }
.status-canceled { background:#f8d7da; }
.status-rescheduled { background:#d1ecf1; }

.table {
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    background:#e6f2ff; 
}

.modal-content {
    border-radius:20px;
    padding:0;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
    background:#e6f2ff; 
}

.modal-header {
    background: linear-gradient(135deg,#3399ff,#66b3ff);
    color:white;
    border-bottom:none;
    padding:1rem;
    font-weight:600;
}

.modal-body { padding:2rem; background:#f8f9fa; }
.modal-footer { border-top:none; padding:1rem; justify-content:flex-end; background:#f8f9fa; }

.form-control, .form-select { border-radius:12px; padding:.6rem 1rem; }
.btn-back { margin-top:20px; text-align:center; }


.card.bg-warning { background:#ffe599; color:#000; }
.card.bg-success { background:#b3e6b3; color:#000; }
.card.bg-danger { background:#f5b3b3; color:#000; }
.card.bg-info { background:#99ccff; color:#000; }

</style>
</head>
<body>

<div class="container mt-5">
<h2 class="mb-4 fw-bold">📋 Admin Dashboard - Appointments</h2>

<div class="row mb-4">
<div class="col-md-3"><div class="card shadow text-center p-3 bg-warning rounded-4"><h5> Pending</h5><h3><?= $count_pending ?></h3></div></div>
<div class="col-md-3"><div class="card shadow text-center p-3 bg-success rounded-4"><h5> Completed</h5><h3><?= $count_completed ?></h3></div></div>
<div class="col-md-3"><div class="card shadow text-center p-3 bg-danger rounded-4"><h5> Canceled</h5><h3><?= $count_canceled ?></h3></div></div>
<div class="col-md-3"><div class="card shadow text-center p-3 bg-info rounded-4"><h5> Rescheduled</h5><h3><?= $count_rescheduled ?></h3></div></div>
</div>

<table class="table table-bordered table-striped shadow">
<thead class="table-dark">
<tr>
<th>Queue</th><th>Name</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Purpose/Reason</th>
<th>Additional Notes</th>
<th>Staff Assigned</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php while($row=$appointments->fetch_assoc()):
$service=$row['service'];
$current_staff_list=$staff_list[$service]??[];
$assigned=trim($row['staff_assigned']);


if($assigned==''){
    foreach($current_staff_list as $staff_name){
        $stmt=$conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE appointment_date=? AND appointment_time=? AND staff_assigned=?");
        $stmt->bind_param("sss",$row['appointment_date'],$row['appointment_time'],$staff_name);
        $stmt->execute();
        $res=$stmt->get_result()->fetch_assoc();
        if($res['total']<$max_per_slot){
            $assigned=$staff_name;
            $update=$conn->prepare("UPDATE appointments SET staff_assigned=? WHERE id=?");
            $update->bind_param("si",$assigned,$row['id']);
            $update->execute();
            break;
        }
    }
}

$start=date("h:i A",strtotime($row['appointment_time']));
$end=date("h:i A",strtotime($row['appointment_time']." +1 hour"));
?>

<tr class="status-<?= strtolower($row['status']) ?>">
<td><?= $row['queue_number'] ?></td>
<td><?= htmlspecialchars($row['fullname']) ?></td>
<td><?= htmlspecialchars($row['service']) ?></td>
<td><?= $row['appointment_date'] ?></td>
<td><?= $start." - ".$end ?></td>
<td><?= ucfirst($row['status']) ?></td>
<td><?= htmlspecialchars($row['purpose'] ?? '—') ?></td>
<td><?= htmlspecialchars($row['notes'] ?? '—') ?></td>
<td><?= $assigned ? $assigned : '<span style="color:red;">No Staff</span>' ?></td>
<td>
<button class="btn btn-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#updateModal"
data-id="<?= $row['id'] ?>" data-name="<?= $row['fullname'] ?>" data-service="<?= $row['service'] ?>" data-date="<?= $row['appointment_date'] ?>" data-time="<?= $row['appointment_time'] ?>" data-status="<?= $row['status'] ?>" data-staff="<?= $assigned ?>">Update</button>

<form method="post" action="delete_appointment.php" class="mt-1">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button class="btn btn-danger btn-sm rounded-pill px-3">Delete</button>
</form>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

<div class="btn-back">
<a href="admin.php" class="btn btn-dark px-4 py-2 rounded-pill shadow">← Back to Dashboard</a>
</div>
</div>


<div class="modal fade" id="updateModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content shadow-lg border-0">
<form method="post" action="update_appointment.php">
<div class="modal-header">
<h5 class="modal-title">Update Appointment</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4">
<input type="hidden" name="id" id="id">

<div class="mb-3"><label>Name</label><input type="text" id="name" class="form-control rounded-3" readonly></div>
<div class="mb-3"><label>Service</label><input type="text" id="service" class="form-control rounded-3" readonly></div>
<div class="mb-3"><label>Time Slot</label>
<select name="appointment_time" id="time" class="form-select rounded-3" required></select></div>
<div class="mb-3"><label>Date</label><input type="date" name="appointment_date" id="date" class="form-control rounded-3" required></div>
<div class="mb-3"><label>Status</label>
<select name="status" id="status" class="form-select rounded-3">
<option value="pending">🟡 Pending</option>
<option value="completed">🟢 Completed</option>
<option value="canceled">🔴 Canceled</option>
<option value="rescheduled">🔵 Rescheduled</option>
</select>
</div>
<div class="mb-3"><label>Staff Assigned</label><select name="staff_assigned" id="staff" class="form-select rounded-3"></select></div>
</div>

<div class="modal-footer border-0 px-4 pb-4">
<button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-success rounded-pill px-4 shadow">✔ Save</button>
</div>
</form>
</div>
</div>
</div>

<script>
const staffData = {
"Barangay Service":["Juan Dela Cruz – Punong Barangay","Antonio Reyes – Barangay Secretary","Maria Santos – Barangay Clerk / Records Officer","Liza Villanueva – Barangay Treasurer","Rosa Mendoza – Barangay Health Worker","Pedro Bautista – Barangay Kagawad / Councilor"],
"Certificate Request":["Mr. Angelo Ramirez","Ms. Kristine Mendoza","Mr. Mark Villanueva","Ms. Sophia Santos","Mr. Jason Cruz"],
"Medical Checkup":["Dr. Juan Santos","Dr. Maria Velasco","Dr. Carlos Mendoza","Dr. Leah Navarro","Dr. Roberto Cruz"]
};

const timeSlots=[];
for(let h=6;h<=15;h++){
let start=new Date(0,0,0,h,0);
let end=new Date(0,0,0,h+1,0);
let options={hour:'2-digit',minute:'2-digit',hour12:true};
let slot=start.toLocaleTimeString([],options)+" - "+end.toLocaleTimeString([],options);
timeSlots.push({value:start.toTimeString().split(" ")[0],display:slot});
}

const modal=document.getElementById('updateModal');
modal.addEventListener('show.bs.modal',function(event){
const button=event.relatedTarget;
const service=button.getAttribute('data-service');
const selectedStaff=button.getAttribute('data-staff');
const startTime=button.getAttribute('data-time');

document.getElementById('id').value=button.getAttribute('data-id');
document.getElementById('name').value=button.getAttribute('data-name');
document.getElementById('service').value=service;
document.getElementById('date').value=button.getAttribute('data-date');
document.getElementById('status').value=button.getAttribute('data-status');


const staffSelect=document.getElementById('staff');
staffSelect.innerHTML='';
if(staffData[service]){
    staffData[service].forEach(staff=>{
        let option=document.createElement('option');
        option.value=staff;
        option.text=staff;
        if(staff===selectedStaff) option.selected=true;
        staffSelect.appendChild(option);
    });
}


const timeSelect=document.getElementById('time');
timeSelect.innerHTML='';
timeSlots.forEach(slot=>{
    let option=document.createElement('option');
    option.value=slot.value;
    option.text=slot.display;
    if(slot.value===startTime) option.selected=true;
    timeSelect.appendChild(option);
});
});
</script>
</body>
</html>