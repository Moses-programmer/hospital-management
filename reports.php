<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper for collapse, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php
session_start();
include 'db.php';
include 'nav.php';

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: signin.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

// Fetch all reports for this patient
$result = $conn->query("SELECT * FROM reports WHERE patient_id = $patient_id ORDER BY created_at DESC");
?>

<div class="container py-5">
    <h2>Your Reports</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Report Name</th>
                    <th>Uploaded On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['report_name']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><a href="<?= $row['report_file'] ?>" target="_blank" class="btn btn-primary btn-sm">View / Download</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info mt-3">No reports uploaded yet.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
