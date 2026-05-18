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
    
     <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Weekly Revenue</div>
                <div class="chart-sub">Total revenue per week — past 8 weeks</div>
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="revenue-chart"></canvas>
        </div>
    </div>

     <div class="today-grid">

        <div class="today-panel arrivals">
            <div class="today-panel-head">
                <span class="dot"></span>
                <h3>Today's Arrivals</h3>
                <span class="badge-count" id="arrivals-count">0</span>
            </div>
            <div class="today-list" id="arrivals-list">
                <div class="today-empty">Loading…</div>
            </div>
        </div>

        <div class="today-panel departures">
            <div class="today-panel-head">
                <span class="dot"></span>
                <h3>Today's Departures</h3>
                <span class="badge-count" id="departures-count">0</span>
            </div>
            <div class="today-list" id="departures-list">
                <div class="today-empty">Loading…</div>
            </div>
        </div>

    </div>

    <div class="section-title">All Reservations</div>

    <div class="filter-bar" id="filter-bar">

        <div class="filter-group">
            <label class="filter-label" for="status-filter">Status (multi-select)</label>
            <select id="status-filter" name="status[]" multiple>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="checked_in">Checked In</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label" for="from-date">Check-in from</label>
            <input type="date" id="from-date" name="from">
        </div>

        <div class="filter-group">
            <label class="filter-label" for="to-date">Check-in to</label>
            <input type="date" id="to-date" name="to">
        </div>

        <div class="filter-actions">
            <button class="btn btn-primary" id="btn-apply">Apply Filters</button>
            <button class="btn btn-secondary" id="btn-reset">Reset</button>
        </div>
    </div>

   <div class="table-wrapper">
        <div class="table-loader" id="table-loader" hidden>
            <div class="spinner"></div>
            <span>Loading bookings…</span>
        </div>

        <div class="empty-state" id="empty-state" hidden>
            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="1"/>
            </svg>
            <p>No bookings match the selected filters.</p>
        </div>

        <table class="booking-table" id="booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room No.</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="booking-tbody">
                <!-- JS-injected rows -->
            </tbody>
        </table>
    </div>

    <p class="row-count" id="row-count"></p> 

</main>

<div class="toast" id="toast" role="alert" aria-live="polite"></div>

<script>
const API      = '/Web_Tech_Final/Assignmet copy/controllers/bookingListController.php';
const API_DASH = '/Web_Tech_Final/Assignmet copy/controllers/bookingListController.php?action=dashboard';
const API_REV  = '/Web_Tech_Final/Assignmet copy/controllers/bookingListController.php?action=revenue';    

const statusFilter  = document.getElementById('status-filter');
const fromDate      = document.getElementById('from-date');
const toDate        = document.getElementById('to-date');
const btnApply      = document.getElementById('btn-apply');
const btnReset      = document.getElementById('btn-reset');
const tbody         = document.getElementById('booking-tbody');
const loader        = document.getElementById('table-loader');
const emptyState    = document.getElementById('empty-state');
const rowCount      = document.getElementById('row-count');
const toast         = document.getElementById('toast');

const statTotal     = document.getElementById('stat-total');
const statConfirmed = document.getElementById('stat-confirmed');
const statPending   = document.getElementById('stat-pending');
const statRevenue   = document.getElementById('stat-revenue');

const arrivalsList    = document.getElementById('arrivals-list');
const departuresList  = document.getElementById('departures-list');
const arrivalsCount   = document.getElementById('arrivals-count');
const departuresCount = document.getElementById('departures-count');

const fmtDate  = d => d ? new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
const fmtMoney = n => n > 0 ? '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 0 }) : '—';

const BADGE_MAP = {
    'confirmed'  : 'badge-confirmed',
    'pending'    : 'badge-pending',
    'cancelled'  : 'badge-cancelled',
    'checked_in' : 'badge-checkedin',
    'completed'  : 'badge-checkedout',
};

function showToast(msg, type = 'info') {
    toast.textContent = msg;
    toast.className   = toast toast-${type} show;
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.remove('show'), 3200);
}

function setLoading(on) {
    loader.hidden = !on;
    document.getElementById('booking-table').style.opacity = on ? '0.35' : '1';
}


function buildUrl() {
    const url = new URL(API, location.origin);
    url.searchParams.set('action', 'list');
    Array.from(statusFilter.selectedOptions).map(o => o.value)
        .forEach(s => url.searchParams.append('status[]', s));
    if (fromDate.value) url.searchParams.set('from', fromDate.value);
    if (toDate.value)   url.searchParams.set('to',   toDate.value);
    return url;
}

function renderRows(bookings) {
    tbody.innerHTML = '';
    if (!bookings.length) {
        emptyState.hidden = false;
        rowCount.textContent = '';
        return;
    }
    emptyState.hidden = true;
    rowCount.textContent = Showing ${bookings.length} booking${bookings.length !== 1 ? 's' : ''};

    bookings.forEach(b => {
        const tr  = document.createElement('tr');
        tr.dataset.id = b.booking_id;
        const cls = BADGE_MAP[b.status] ?? 'badge-default';
        const lbl = (b.status ?? '').replace('_', ' ');

        tr.innerHTML = `
            <td><a class="booking-link" href="/admin/bookingDetail.php?id=${b.booking_id}" onclick="event.stopPropagation()">#${b.booking_id}</a></td>
            <td>${b.guest_name}</td>
            <td><span style="font-family:var(--mono);font-size:.8rem">${b.room_number}</span></td>
            <td>${b.room_type}</td>
            <td>${fmtDate(b.checkin_date)}</td>
            <td>${fmtDate(b.checkout_date)}</td>
            <td style="font-weight:600">${fmtMoney(b.total_price)}</td>
            <td><span class="badge ${cls}">${lbl}</span></td>
        `;
        tr.addEventListener('click', () => {
            window.location.href = /admin/bookingDetail.php?id=${b.booking_id};
        });
        tbody.appendChild(tr);
    });
}

function renderStats(s) {
    statTotal.textContent     = s.total     ?? '—';
    statConfirmed.textContent = s.confirmed ?? '—';
    statPending.textContent   = s.pending   ?? '—';
    statRevenue.textContent   = fmtMoney(s.revenue ?? 0);
}

function renderTodayPanel(container, countEl, items, emptyMsg) {
    countEl.textContent = items.length;
    if (!items.length) {
        container.innerHTML = <div class="today-empty">${emptyMsg}</div>;
        return;
    }
    container.innerHTML = items.map(b => `
        <div class="today-item">
            <span class="room-pill">${b.room_number}</span>
            <span class="guest">${b.guest_name}</span>
            <span class="bid">#${b.booking_id}</span>
        </div>
    `).join('');
}

let revenueChart = null;

function renderRevenueChart(labels, data) {
    const ctx = document.getElementById('revenue-chart').getContext('2d');

    if (revenueChart) revenueChart.destroy();

    const gradient = ctx.createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0,   'rgba(79,124,255,0.55)');
    gradient.addColorStop(1,   'rgba(79,124,255,0.0)');

    revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue ($)',
                data,
                backgroundColor: gradient,
                borderColor: '#4f7cff',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2436',
                    borderColor: '#2a3147',
                    borderWidth: 1,
                    titleColor: '#94a3b8',
                    bodyColor: '#e2e8f0',
                    callbacks: {
                        label: ctx => ' $' + Number(ctx.parsed.y).toLocaleString()
                    }
                }
            },
            scales: {
                x: {
                    grid:  { color: '#2a3147' },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                y: {
                    grid:  { color: '#2a3147' },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11 },
                        callback: v => '$' + Number(v).toLocaleString()
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

async function fetchBookings() {
    setLoading(true);
    try {
        const res  = await fetch(buildUrl(), { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) { showToast(json.message ?? 'Error loading bookings.', 'error'); return; }
        renderRows(json.data.bookings);
        renderStats(json.data.stats);
    } catch (e) {
        console.error(e);
        showToast('Network error — could not load bookings.', 'error');
    } finally {
        setLoading(false);
    }
}

async function fetchDashboard() {
    try {
        const res  = await fetch(API_DASH, { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) return;
        const { arrivals, departures } = json.data;
        renderTodayPanel(arrivalsList,   arrivalsCount,   arrivals,   'No arrivals today.');
        renderTodayPanel(departuresList, departuresCount, departures, 'No departures today.');
    } catch (e) { console.error('Dashboard fetch failed', e); }
}

async function fetchRevenue() {
    try {
        const res  = await fetch(API_REV, { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) return;
        const { labels, values } = json.data;
        renderRevenueChart(labels, values);
    } catch (e) { console.error('Revenue fetch failed', e); }
}

btnApply.addEventListener('click', fetchBookings);

btnReset.addEventListener('click', () => {
    Array.from(statusFilter.options).forEach(o => o.selected = false);
    fromDate.value = '';
    toDate.value   = '';
    fetchBookings();
});

[fromDate, toDate].forEach(inp =>
    inp.addEventListener('keydown', e => { if (e.key === 'Enter') fetchBookings(); })
);

fetchBookings();
fetchDashboard();
fetchRevenue();
</script>
</body>
</html>