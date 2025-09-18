<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
include 'nav.php';

// Admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ---------- Handle adding a new doctor ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO doctors (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    $stmt->close();
    $success = "Doctor added successfully!";
}

// ---------- Handle Freeze / Unfreeze ----------
if (isset($_GET['freeze'])) {
    $id = intval($_GET['freeze']);
    $conn->query("UPDATE doctors SET status='frozen' WHERE id=$id");
    $success = "Doctor frozen!";
    header("Refresh:0; url=admin_panel.php");
    exit;
}
if (isset($_GET['unfreeze'])) {
    $id = intval($_GET['unfreeze']);
    $conn->query("UPDATE doctors SET status='active' WHERE id=$id");
    $success = "Doctor unfrozen!";
    header("Refresh:0; url=admin_panel.php");
    exit;
}

// ---------- Handle reassigning patient ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reassign_patient'])) {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $stmt = $conn->prepare("UPDATE patients SET doctor_id=? WHERE id=?");
    $stmt->bind_param("ii", $doctor_id, $patient_id);
    $stmt->execute();
    $stmt->close();
    $success = "Patient reassigned successfully!";
}

// ---------- Fetch doctors and patients ----------
$doctors = $conn->query("SELECT id, name, email, status FROM doctors");
$patients = $conn->query("SELECT p.*, d.name AS doctor_name FROM patients p LEFT JOIN doctors d ON p.doctor_id=d.id");
?>

<div class="container py-5">

    <h2>Admin Panel</h2>

    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Add Doctor Form -->
    <div class="card mb-4 p-3">
        <h4>Add New Doctor</h4>
        <form method="POST" class="row g-3">
            <input type="hidden" name="add_doctor" value="1">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Doctor Name" required>
            </div>
            <div class="col-md-4">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Add Doctor</button>
            </div>
        </form>
    </div>

    <!-- Doctors List -->
    <h4>Doctors List</h4>
    <table class="table table-bordered mb-5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($doc = $doctors->fetch_assoc()): ?>
        <tr>
            <td><?= $doc['id'] ?></td>
            <td><?= htmlspecialchars($doc['name']) ?></td>
            <td><?= htmlspecialchars($doc['email']) ?></td>
            <td>
                <?php if($doc['status']=='active'): ?>
                    <span class="badge bg-success">Active</span>
                <?php else: ?>
                    <span class="badge bg-danger">Frozen</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if($doc['status']=='active'): ?>
                    <a href="?freeze=<?= $doc['id'] ?>" class="btn btn-warning btn-sm">Freeze</a>
                <?php else: ?>
                    <a href="?unfreeze=<?= $doc['id'] ?>" class="btn btn-success btn-sm">Unfreeze</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Patients List -->
    <h4>Patients List</h4>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Assigned Doctor</th>
            <th>Reassign Doctor</th>
        </tr>
        <?php while($pat = $patients->fetch_assoc()): ?>
        <tr>
            <td><?= $pat['id'] ?></td>
            <td><?= htmlspecialchars($pat['name']) ?></td>
            <td><?= htmlspecialchars($pat['email']) ?></td>
            <td><?= htmlspecialchars($pat['doctor_name'] ?? 'Unassigned') ?></td>
            <td>
                <form method="POST" class="d-flex">
                    <input type="hidden" name="patient_id" value="<?= $pat['id'] ?>">
                    <select name="doctor_id" class="form-control me-2" required>
                        <option value="">Select Doctor</option>
                        <?php
                        $dlist = $conn->query("SELECT id, name FROM doctors WHERE status='active'");
                        while($d = $dlist->fetch_assoc()):
                        ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button class="btn btn-primary btn-sm" name="reassign_patient">Reassign</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<?php include 'footer.php'; ?>
