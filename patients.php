<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper for collapse, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Welcome, Patient!</h2>
        <p>Here you can book appointments, chat with doctors, and view your reports.</p>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
            <div class="col">
                <div class="card p-4 shadow-sm text-center h-100">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <h5>Book Appointment</h5>
                    <a href="book_appointment.php" class="btn btn-primary mt-3">Go</a>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 shadow-sm text-center h-100">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <h5>Chat with Doctors</h5>
                    <a href="chat.php" class="btn btn-primary mt-3">Go</a>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 shadow-sm text-center h-100">
                    <i class="fas fa-file-medical fa-3x mb-3"></i>
                    <h5>View Reports</h5>
                    <a href="reports.php" class="btn btn-primary mt-3">Go</a>
                </div>
            </div>
        </div>
    </div>
</section>
