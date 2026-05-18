
  <?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit;
}

include '../models/db.php';
include '../models/BookingManagementModel.php';

$database   = new db();
$connection = $database->connection();

$bookingId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$bookingId) {
    header('Location: task4_dashboard.php');
    exit;
}

$booking = getBookingById($connection, $bookingId);

if (!$booking) {
    header('Location: task4_dashboard.php');
    exit;
}

// Badge class helper
$badgeClass = match($booking['status']) {
    'Pending'     => 'badge-pending',
    'Confirmed'   => 'badge-confirmed',
    'Checked-In'  => 'badge-checkedin',
    'Checked-Out' => 'badge-checkedout',
    'Cancelled'   => 'badge-cancelled',
    default       => ''
};

// Show Check In only if Confirmed AND checkin_date = today
$showCheckIn  = ($booking['status'] === 'Confirmed' && $booking['checkin_date'] === date('Y-m-d'));
$showCheckOut = ($booking['status'] === 'Checked-In');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Detail — Grand Palace</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <link rel="stylesheet" href="../assets/task4.css">
</head>
<body>

<div class="page-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <p class="panel-brand">***** GRAND PALACE</p>
        <h3 class="panel-heading">Admin<br>Panel</h3>
        <nav class="sidebar-nav">
            <a href="../views/admin.php" class="nav-item">Room Management</a>
            <a href="task4_dashboard.php" class="nav-item">Dashboard</a>
            <a href="task4_dashboard.php#bookings" class="nav-item active">All Bookings</a>
            <a href="../controllers/logoutController.php" class="nav-item logout">Logout</a>
        </nav>
    </div>

    <!-- MAIN -->
    <div class="main-content">

        <h2>Booking Detail</h2>
        <p class="subtitle">Full information for this reservation</p>

        <a href="task4_dashboard.php#bookings" class="back-link">&larr; Back to All Bookings</a>

        <div class="two-col">

            <!-- Reservation Info -->
            <div class="form-box">
                <h3 class="form-box-title">Reservation Info</h3>

                <div class="detail-row">
                    <span class="detail-label">Booking #</span>
                    <span class="detail-value"><?= $booking['id'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="badge <?= $badgeClass ?>"><?= $booking['status'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Room Number</span>
                    <span class="detail-value"><?= htmlspecialchars($booking['room_number']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Room Type</span>
                    <span class="detail-value"><?= htmlspecialchars($booking['room_type']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-in</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($booking['checkin_date'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-out</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($booking['checkout_date'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Price</span>
                    <span class="detail-value detail-price">৳ <?= number_format($booking['total_price']) ?></span>
                </div>
                <?php if ($booking['actual_checkin']): ?>
                <div class="detail-row">
                    <span class="detail-label">Actual Check-in</span>
                    <span class="detail-value"><?= date('M d, Y H:i', strtotime($booking['actual_checkin'])) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">Booked On</span>
                    <span class="detail-value"><?= date('M d, Y', strtotime($booking['created_at'])) ?></span>
                </div>
            </div>

            <div>
                <!-- Guest Info -->
                <div class="form-box">
                    <h3 class="form-box-title">Guest Info</h3>

                    <div class="detail-row">
                        <span class="detail-label">Name</span>
                        <span class="detail-value"><?= htmlspecialchars($booking['guest_name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?= htmlspecialchars($booking['email']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value"><?= htmlspecialchars($booking['phone']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nationality</span>
                        <span class="detail-value"><?= htmlspecialchars($booking['nationality']) ?></span>
                    </div>
                </div>

                <!-- Action Box -->
                <?php if ($showCheckIn || $showCheckOut): ?>
                <div class="form-box action-box">
                    <h3 class="form-box-title">Action</h3>

                    <?php if ($showCheckIn): ?>
                        <p class="action-note">Today is the check-in date. Click to check in the guest.</p>
                        <button class="action-btn checkin-btn full-btn" id="actionBtn"
                            onclick="checkIn(<?= $booking['id'] ?>, this)">Check In</button>

                    <?php elseif ($showCheckOut): ?>
                        <p class="action-note">Guest is currently checked in. Click to check out.</p>
                        <button class="action-btn checkout-btn full-btn" id="actionBtn"
                            onclick="checkOut(<?= $booking['id'] ?>, this)">Check Out</button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- end two-col -->

    </div><!-- end main-content -->

</div><!-- end page-wrapper -->

<script>
// AJAX: Check In
function checkIn(bookingId, btn) {
    fetch('../ajax/checkin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            btn.textContent = 'Check Out';
            btn.className = 'action-btn checkout-btn full-btn';
            btn.setAttribute('onclick', 'checkOut(' + bookingId + ', this)');
            document.querySelector('.badge').textContent = 'Checked-In';
            document.querySelector('.badge').className = 'badge badge-checkedin';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(function() { alert('Something went wrong.'); });
}

// AJAX: Check Out
function checkOut(bookingId, btn) {
    fetch('../ajax/checkout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            btn.textContent = 'Done';
            btn.disabled = true;
            btn.classList.add('btn-disabled');
            document.querySelector('.badge').textContent = 'Checked-Out';
            document.querySelector('.badge').className = 'badge badge-checkedout';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(function() { alert('Something went wrong.'); });
}
</script>

</body>
</html>
