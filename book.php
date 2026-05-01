<?php
session_start();
include 'config.php';

$service = $_GET['service'] ?? 'Service';
$error = [];

$staff_list = [
    'Barangay Service' => ['Juan Dela Cruz– Barangay Captain', 'Antonio Reyes– Barangay Kagawad', 'Maria Santos– Barangay Kagawad', 'Liza Villanueva– Barangay Treasurer', 'Rosa Mendoza– Barangay Kagawad', 'Pedro Bautista– Barangay Kagawad'],
    'Certificate Request' => ['Angelo Ramirez', 'Kristine Mendoza', 'Mark Villanueva', 'Sophia Santos', 'Jason Cruz'],
    'Medical Checkup' => ['Dr. Juan Santos', 'Dr. Maria Velasco', 'Dr. Carlos Mendoza', 'Dr. Leah Navarro', 'Dr. Roberto Cruz']
];
$all_staff = $staff_list[$service] ?? [];

$available_times = [];
for ($h = 6; $h <= 15; $h++) { 
    $available_times[] = sprintf("%02d:00:00", $h);
}

$appointments_res = $conn->query("
    SELECT appointment_date, appointment_time, assigned_staff 
    FROM appointments 
    WHERE status != 'Cancelled'
    AND service = '$service'
");

$appointments = [];
while ($row = $appointments_res->fetch_assoc()) {
    $appointments[$row['appointment_date']][$row['appointment_time']][] = $row['assigned_staff'];
}

$fully_booked_dates = [];
foreach ($appointments as $date => $times) {
    $all_full = true;
    foreach ($available_times as $t) {
        if (empty($appointments[$date][$t]) || count($appointments[$date][$t]) < count($all_staff)) {
            $all_full = false;
            break;
        }
    }
    if ($all_full) $fully_booked_dates[] = $date;
}

if (isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $schedule_date = $_POST['schedule_date'];
    $time = $_POST['time'];
    $assigned_staff = $_POST['assigned_staff'];
    $purpose = $_POST['purpose'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $booked_staff = $appointments[$schedule_date][$time] ?? [];

    if (in_array($assigned_staff, $booked_staff)) {
        $error[] = "Staff already booked at this time!";
    } else {

        $queue = $conn->query("
            SELECT COUNT(*) AS total 
            FROM appointments 
            WHERE appointment_date='$schedule_date'
            AND service='$service'
        ")->fetch_assoc()['total'] + 1;

        $conn->query("
            INSERT INTO appointments 
            (fullname,birthdate,gender,contact,email,address,service,appointment_date,appointment_time,assigned_staff,status,queue_number,purpose,notes)
            VALUES
            ('$name','$birthdate','$gender','$contact','$email','$address','$service','$schedule_date','$time','$assigned_staff','Pending','$queue','$purpose','$notes')
        ");

        header("Location: book.php?loading=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<style>
body { font-family: Arial; background: linear-gradient(135deg,#c3ecff,#ffe6f2); min-height:100vh; }
.card { margin-top:40px; padding:30px; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,.2); }
h4 { text-align:center; color:#0d6efd; margin-bottom:20px; }
.form-control, textarea { border-radius:12px; margin-bottom:12px; }
.btn { border-radius:20px; font-weight:600; }
.error-msg { color:red; text-align:center; font-weight:600; margin-bottom:10px; }
.booked-date { background-color:#ff4d4d !important; color:white !important; border-radius:50% !important; font-weight:bold; }
.back-btn { position:fixed; top:20px; left:20px; width:45px; height:45px; background:rgba(255,255,255,0.7); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#0d6efd; text-decoration:none; font-size:20px; box-shadow:0 8px 20px rgba(0,0,0,0.2); transition:0.3s; }
.back-btn:hover { background:#0d6efd; color:white; transform:scale(1.1); }
</style>
</head>

<body>

<a href="index.php" class="back-btn">&#8592;</a>

<!-- 🔥 ONLY ADDITION: FULL SCREEN LOADING -->
<div id="loadingScreen" style="
    display:<?php echo isset($_GET['loading']) ? 'flex' : 'none'; ?>;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:white;
    z-index:9999;
    justify-content:center;
    align-items:center;
    flex-direction:column;
">
    <div class="spinner-border text-primary" style="width:70px;height:70px;"></div>
    <h3 style="margin-top:15px;">Processing your appointment...</h3>
    <p>Please wait 5 seconds</p>
</div>

<div class="container col-md-6 card" style="display:<?php echo isset($_GET['loading']) ? 'none' : 'block'; ?>;">
<h4><?= htmlspecialchars($service) ?></h4>

<?php foreach ($error as $e) echo "<p class='error-msg'>$e</p>"; ?>

<form method="post">

<input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
<input type="text" id="birthdate" name="birthdate" class="form-control" placeholder="Select Birthdate" required>

<select name="gender" class="form-control" required>
<option value="">Select Gender</option>
<option>Male</option>
<option>Female</option>
</select>

<input type="text" name="contact" class="form-control" placeholder="Contact Number" required>
<input type="email" name="email" class="form-control" placeholder="Email" required>
<input type="text" name="address" class="form-control" placeholder="Address / Purok / Sitio" required>

<input type="text" id="schedule_date" name="schedule_date" class="form-control" placeholder="Select Schedule Date" required>

<select id="time_dropdown" name="time" class="form-control" required>
<option value="">Select Time</option>
</select>

<select id="staff_dropdown" name="assigned_staff" class="form-control" required>
<option value="">Select Staff</option>
</select>

<input type="text" name="purpose" class="form-control" placeholder="Purpose / Reason">
<textarea name="notes" class="form-control" placeholder="Additional Notes"></textarea>

<button name="submit" class="btn btn-primary w-100">Submit Appointment</button>

</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
let appointments = <?= json_encode($appointments) ?>;
let allTimes = <?= json_encode($available_times) ?>;
let allStaff = <?= json_encode($all_staff) ?>;
let fullyBookedDates = <?= json_encode($fully_booked_dates) ?>;

flatpickr("#schedule_date", {
    dateFormat: "Y-m-d",
    onDayCreate: function(dObj, dStr, fp, dayElem){
        let dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
        if(fullyBookedDates.includes(dateStr)) {
            dayElem.classList.add("booked-date"); 
        }
    },
    onChange: function(selectedDates, dateStr){
        let timeDropdown = document.getElementById("time_dropdown");
        let staffDropdown = document.getElementById("staff_dropdown");

        timeDropdown.innerHTML = '<option value="">Select Time</option>';
        staffDropdown.innerHTML = '<option value="">Select Staff</option>';

        if(dateStr){
            allTimes.forEach(t=>{
                let bookedStaff = appointments[dateStr]?.[t] || [];
                let endHour = parseInt(t.split(':')[0])+1;
                let display = new Date("1970-01-01T"+t).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',hour12:true})
                    + " - " + new Date("1970-01-01T"+String(endHour).padStart(2,'0')+":00").toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',hour12:true});

                let opt = document.createElement("option");
                opt.value = t;

                if(bookedStaff.length > 0){
                    opt.text = display + " (Unavailable)";
                    opt.disabled = true;
                    opt.style.color = "red";
                } else {
                    opt.text = display;
                }

                timeDropdown.add(opt);
            });
        }
    }
});

document.getElementById("time_dropdown").addEventListener("change", function(){
    let dateStr = document.getElementById("schedule_date").value;
    let timeStr = this.value;
    let staffDropdown = document.getElementById("staff_dropdown");

    staffDropdown.innerHTML = '<option value="">Select Staff</option>';

    if(dateStr && timeStr){
        let bookedStaff = appointments[dateStr]?.[timeStr] || [];

        allStaff.forEach(staff=>{
            let opt = document.createElement("option");
            opt.value = staff;
            if(bookedStaff.includes(staff)){
                opt.text = staff + " (Unavailable)";
                opt.disabled = true;
                opt.style.color = "red";
            } else {
                opt.text = staff;
            }
            staffDropdown.add(opt);
        });
    }
});

flatpickr("#birthdate", {
    dateFormat: "Y-m-d",
    maxDate: "today",
    yearSelectorType: "dropdown",
    allowInput: false
});

if (document.getElementById("loadingScreen").style.display === "flex") {
    setTimeout(() => {
        window.location = "index.php";
    }, 5000);
}
</script>

</body>
</html>