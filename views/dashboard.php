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

// Occupancy summary
$summary = getOccupancySummary($connection);

// Today's arrivals and departures
$arrivals   = getTodayArrivals($connection);
$departures = getTodayDepartures($connection);

// Bookings list with filters (GET params)
$filterStatus   = $_GET['status']    ?? '';
$filterDateFrom = $_GET['date_from'] ?? '';
$filterDateTo   = $_GET['date_to']   ?? '';

$bookings = getAllBookings($connection, $filterStatus, $filterDateFrom, $filterDateTo);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Management — Grand Palace</title>
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
            <a href="#dashboard" class="nav-item active" onclick="showSection('dashboard', this)">Dashboard</a>
            <a href="#bookings" class="nav-item" onclick="showSection('bookings', this)">All Bookings</a>
            <a href="../controllers/logoutController.php" class="nav-item logout">Logout</a>
        </nav>
    </div>

    <!-- MAIN -->
    <div class="main-content">

        <!-- SECTION 1: OCCUPANCY DASHBOARD -->
        <div id="section-dashboard" class="section">

            <h2>Occupancy Dashboard</h2>
            <p class="subtitle">Live overview of today's hotel status</p>

            <!-- Summary Cards -->
            <div class="summary-grid">
                <div class="summary-card">
                    <p class="summary-label">Total Rooms</p>
                    <p class="summary-number"><?= $summary['total'] ?></p>
                </div>
                <div class="summary-card">
                    <p class="summary-label">Occupied</p>
                    <p class="summary-number number-blue"><?= $summary['occupied'] ?></p>
                </div>
                <div class="summary-card">
                    <p class="summary-label">Available</p>
                    <p class="summary-number number-green"><?= $summary['available'] ?></p>
                </div>
                <div class="summary-card">
                    <p class="summary-label">Maintenance</p>
                    <p class="summary-number number-red"><?= $summary['maintenance'] ?></p>
                </div>
            </div>

            <div class="two-col">

                <!-- Today's Arrivals -->
                <div class="form-box">
                    <h3 class="form-box-title">Today's Arrivals</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($arrivals)): ?>
                                <tr><td colspan="4" class="no-data">No arrivals today.</td></tr>
                            <?php else: ?>
                                <?php foreach ($arrivals as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['guest_name']) ?></td>
                                        <td><?= htmlspecialchars($a['room_number']) ?></td>
                                        <td><?= htmlspecialchars($a['room_type']) ?></td>
                                        <td>
                                            <button class="action-btn checkin-btn"
                                                onclick="checkIn(<?= $a['id'] ?>, this)">Check In</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Today's Departures -->
                <div class="form-box">
                    <h3 class="form-box-title">Today's Departures</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($departures)): ?>
                                <tr><td colspan="4" class="no-data">No departures today.</td></tr>
                            <?php else: ?>
                                <?php foreach ($departures as $d): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['guest_name']) ?></td>
                                        <td><?= htmlspecialchars($d['room_number']) ?></td>
                                        <td><?= htmlspecialchars($d['room_type']) ?></td>
                                        <td>
                                            <button class="action-btn checkout-btn"
                                                onclick="checkOut(<?= $d['id'] ?>, this)">Check Out</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Revenue Chart -->
            <div class="form-box chart-box">
                <h3 class="form-box-title">Revenue — Last 8 Weeks</h3>
                <canvas id="revenueChart" height="100"></canvas>
            </div>

        </div><!-- end dashboard -->


        <!-- SECTION 2: ALL BOOKINGS -->
        <div id="section-bookings" class="section hidden">

            <h2>All Bookings</h2>
            <p class="subtitle">Filter and manage all reservations</p>

            <!-- Filter Form -->
            <div class="form-box">
                <h3 class="form-box-title">Filter Bookings</h3>
                <form method="GET" action="">
                    <div class="three-col">
                        <div>
                            <label for="filter_status">Status</label>
                            <select id="filter_status" name="status">
                                <option value="">All Statuses</option>
                                <option value="Pending"     <?= $filterStatus === 'Pending'     ? 'selected' : '' ?>>Pending</option>
                                <option value="Confirmed"   <?= $filterStatus === 'Confirmed'   ? 'selected' : '' ?>>Confirmed</option>
                                <option value="Checked-In"  <?= $filterStatus === 'Checked-In'  ? 'selected' : '' ?>>Checked-In</option>
                                <option value="Checked-Out" <?= $filterStatus === 'Checked-Out' ? 'selected' : '' ?>>Checked-Out</option>
                                <option value="Cancelled"   <?= $filterStatus === 'Cancelled'   ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from">From Date</label>
                            <input type="date" id="date_from" name="date_from"
                                value="<?= htmlspecialchars($filterDateFrom) ?>">
                        </div>
                        <div>
                            <label for="date_to">To Date</label>
                            <input type="date" id="date_to" name="date_to"
                                value="<?= htmlspecialchars($filterDateTo) ?>">
                        </div>
                    </div>
                    <button type="submit" class="filter-btn">Apply Filter</button>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="form-box table-box">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="9" class="no-data">No bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $b): ?>
                                <?php
                                    $badgeClass = match($b['status']) {
                                        'Pending'     => 'badge-pending',
                                        'Confirmed'   => 'badge-confirmed',
                                        'Checked-In'  => 'badge-checkedin',
                                        'Checked-Out' => 'badge-checkedout',
                                        'Cancelled'   => 'badge-cancelled',
                                        default       => ''
                                    };
                                ?>
                                <tr>
                                    <td><?= $b['id'] ?></td>
                                    <td><?= htmlspecialchars($b['guest_name']) ?></td>
                                    <td><?= htmlspecialchars($b['room_number']) ?></td>
                                    <td><?= htmlspecialchars($b['room_type']) ?></td>
                                    <td><?= date('M d, Y', strtotime($b['checkin_date'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($b['checkout_date'])) ?></td>
                                    <td>৳ <?= number_format($b['total_price']) ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $b['status'] ?></span></td>
                                    <td>
                                        <a href="booking_detail.php?id=<?= $b['id'] ?>" class="detail-link">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div><!-- end bookings -->

    </div><!-- end main-content -->

</div><!-- end page-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

// Open correct section if URL has hash
(function () {
    const hash = window.location.hash.replace('#', '');
    const valid = ['dashboard', 'bookings'];
    if (hash && valid.includes(hash)) {
        const link = document.querySelector('.nav-item[href="#' + hash + '"]');
        if (link) showSection(hash, link);
    }
})();

// Section switcher
function showSection(name, clickedLink) {
    document.querySelectorAll('.section').forEach(function(s) {
        s.classList.add('hidden');
    });
    document.querySelectorAll('.nav-item').forEach(function(n) {
        n.classList.remove('active');
    });
    document.getElementById('section-' + name).classList.remove('hidden');
    clickedLink.classList.add('active');
    return false;
}

// Revenue Chart — fetch from AJAX endpoint
fetch('../ajax/revenue.php')
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (!data.success) return;
        var ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Revenue (৳)',
                    data: data.data,
                    backgroundColor: '#1a3a6b',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });

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
            btn.className = 'action-btn checkout-btn';
            btn.setAttribute('onclick', 'checkOut(' + bookingId + ', this)');
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
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(function() { alert('Something went wrong.'); });
}

</script>

</body>
</html>
