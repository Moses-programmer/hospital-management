<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper for collapse, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php
include 'db.php';
include 'nav.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    if (!isset($_SESSION['patient_id'])) {
        header("Location: login.php");
        exit;
    }
    $patient_id = $_SESSION['patient_id'];
    
    $doctor_name = $_POST['doctor_name'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $doctor_name, $appointment_date, $appointment_time);
    $stmt->execute();
    $stmt->close();

    echo "<div class='alert alert-success mt-3'>Appointment Booked!</div>";
}
?>

<section class="py-5">
    <div class="container">
        <h2>Book Appointment</h2>
        <!-- Placeholder content -->
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label>Doctor Name</label>
                <input type="text" name="doctor_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Book</button>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>