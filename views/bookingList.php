<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Booking Dashboard</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="../assets/bookingList.css">
    
</head>
<body>


<nav class="sidebar">
    <div class="sidebar-logo">
        <span>Hotel<em>OS</em></span>
    </div>

    <a class="nav-item" href="/admin/dashboard.php">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
    </a>

    <a class="nav-item active" href="/views/bookingList.php">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
        Bookings
    </a>

    <a class="nav-item" href="/admin/rooms.php">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Rooms
    </a>

    <a class="nav-item" href="/admin/guests.php">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
        Guests
    </a>
</nav>

<main class="main">

    
    <div class="page-header">
        <h1 class="page-title">Booking Dashboard</h1>
        <p class="page-sub">Occupancy overview, revenue trends, and all reservations</p>
    </div>

    <div class="stat-grid" id="stat-grid">
        <div class="stat-card blue">
            <span class="stat-label">Total Bookings</span>
            <span class="stat-val" id="stat-total">—</span>
            <span class="stat-delta" id="stat-delta-total">Loading…</span>
        </div>
        <div class="stat-card green">
            <span class="stat-label">Confirmed</span>
            <span class="stat-val" id="stat-confirmed">—</span>
            <span class="stat-delta" id="stat-delta-confirmed">—</span>
        </div>
        <div class="stat-card amber">
            <span class="stat-label">Pending</span>
            <span class="stat-val" id="stat-pending">—</span>
            <span class="stat-delta" id="stat-delta-pending">—</span>
        </div>
        <div class="stat-card teal">
            <span class="stat-label">Total Revenue</span>
            <span class="stat-val" id="stat-revenue">—</span>
            <span class="stat-delta" id="stat-delta-revenue">all time</span>
        </div>
    </div>    
    