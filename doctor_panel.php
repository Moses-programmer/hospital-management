<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper for collapse, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php
session_start();
include 'db.php';
include 'nav.php';

// Check doctor login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor's name
$stmt = $conn->prepare("SELECT name FROM doctors WHERE id=?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($doctor_name);
$stmt->fetch();
$stmt->close();

// Handle report upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_report'])) {
    $patient_id = $_POST['patient_id'];
    $report_name = $_POST['report_name'];

    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
        $target_dir = "reports/";
        $target_file = $target_dir . basename($_FILES["report_file"]["name"]);

        if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO reports (patient_id, report_name, report_file) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $patient_id, $report_name, $target_file);
            $stmt->execute();
            $stmt->close();
            $success = "Report uploaded successfully!";
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "No file selected.";
    }
}

// Fetch appointments with report status
$appointments = $conn->query("
    SELECT a.id AS appointment_id, a.patient_id, a.appointment_date, a.appointment_time, p.name AS patient_name,
           EXISTS(SELECT 1 FROM reports r WHERE r.patient_id = p.id) AS has_report
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.doctor_id = $doctor_id
    ORDER BY a.appointment_date DESC
");
?>

<div class="container py-5">
    <h2>Welcome, Dr. <?= htmlspecialchars($doctor_name) ?></h2>
    <p>Here you can upload patient reports, view appointments, and manage your patients.</p>

    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <h4 class="mt-4">Appointments</h4>
    <table class="table table-bordered">
        <tr>
            <th>Appointment ID</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Report Status</th>
            <th>Upload Report</th>
        </tr>
        <?php while ($row = $appointments->fetch_assoc()): ?>
        <tr class="<?= $row['has_report'] ? '' : 'table-warning' ?>">
            <td><?= $row['appointment_id'] ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= $row['appointment_date'] ?></td>
            <td><?= $row['appointment_time'] ?></td>
            <td>
                <?= $row['has_report'] ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-warning">Pending</span>' ?>
            </td>
            <td>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="patient_id" value="<?= $row['patient_id'] ?>">
                    <input type="text" name="report_name" placeholder="Report Name" class="form-control mb-1" required>
                    <input type="file" name="report_file" class="form-control mb-1" required>
                    <button type="submit" class="btn btn-success btn-sm" name="upload_report">Upload</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
