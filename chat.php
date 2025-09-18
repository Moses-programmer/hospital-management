<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper for collapse, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


<?php
include 'db.php';
include 'nav.php';

session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}
$patient_id = $_SESSION['patient_id'];


if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $message = $_POST['message'];
    $doctor_name = "Dr. Smith"; // example doctor
    $sender = "Patient";

    $stmt = $conn->prepare("INSERT INTO chat_messages (patient_id, doctor_name, message, sender) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $doctor_name, $message, $sender);
    $stmt->execute();
    $stmt->close();
}
?>

<section class="py-5">
    <div class="container">
        <h2>Chat with Doctors</h2>

        <!-- Chat messages -->
        <div class="border p-3 mb-3" style="height:300px; overflow-y:scroll;">

            <?php
            $result = $conn->query("SELECT * FROM chat_messages WHERE patient_id=$patient_id ORDER BY created_at ASC");
            while ($row = $result->fetch_assoc()) {
                echo "<p><strong>{$row['sender']}:</strong>{$row['message']}</p>";
            }
            ?>
        </div>

        <!-- Chat form -->
        <form method="POST">
            <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                <button class="btn btn-primary" type="submit">Send</button>
            </div>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>