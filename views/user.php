<!DOCTYPE html>
<html>

<head>
    <title>My Profile — Grand Palace</title>
    <link rel="stylesheet" href="../assets/user.css">
</head>

<body>


    <div class="page-wrapper">

       
        <div class="sidebar">
            <p class="panel-brand">Meridian Hotel</p>
            <h3 class="panel-heading">Guest Portal</h3>
            <nav class="sidebar-nav">
                <a href="user.php" class="nav-item active">My Profile</a>
                <a href="searchroom.php" class="nav-item">Search Rooms</a>
                <a href="mybooking.php" class="nav-item">My Bookings</a>
                <a href="../controllers/logoutController.php" class="nav-item logout">Logout</a>
            </nav>
        </div>
        <div class="main-content">

<div class="page-wrapper">

    
    <div class="sidebar">
        <p class="panel-brand">***GRAND PALACE</p>
        <h3 class="panel-heading">Guest<br>Portal</h3>
        <nav class="sidebar-nav">
          <a href="user.php" class="nav-item active">My Profile</a>
          <a href="searchroom.php" class="nav-item">Search Rooms</a>
           <a href="mybooking.php" class="nav-item">My Bookings</a>
            <a href="../controllers/logoutController.php" class="nav-item logout">Logout</a>
        </nav>
    </div>

  
    <div class="main-content">


            <h2>My Profile</h2>
            <p class="subtitle">Manage your account and preferences</p>


            <div class="booking-card">

                <p class="no-booking">No upcoming stays.</p>

            </div>

            <div class="form-grid">


                <div class="form-box">
                    <h3 class="form-box-title">Edit Profile</h3>
                    <form method="POST" action="../../controllers/profileController.php">

                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Reduanul Islam">
                        <span class="error-message" id="nameError"></span>

                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="name@example.com">
                        <span class="error-message" id="emailError"></span>

                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="01700000000">
                        <span class="error-message" id="phoneError"></span>

                        <label for="nationality">Nationality</label>
                        <select id="nationality" name="nationality">
                            <option value="">— Select nationality —</option>
                            <option value="Bangladeshi">Bangladeshi</option>
                            <option value="Indian">Indian</option>
                            <option value="Pakistani">Pakistani</option>
                            <option value="American">American</option>
                            <option value="British">British</option>
                            <option value="Canadian">Canadian</option>
                            <option value="Australian">Australian</option>
                            <option value="Other">Other</option>
                        </select>

                        <button type="submit" name="update_profile">Save Changes</button>
                    </form>
                </div>

            
                <div class="form-box">
                    <h3 class="form-box-title">Preferences</h3>
                    <form method="POST" action="../../controllers/profileController.php">

                        <label for="preferred_room_type_id">Preferred Room Type</label>
                        <select id="preferred_room_type_id" name="preferred_room_type_id">
                            <option value="">— Select room type —</option>
                            <option value="1">Standard</option>
                            <option value="2">Deluxe</option>
                            <option value="3">Suite</option>
                        </select>

                        <label for="special_requests">Special Requests</label>
                        <textarea id="special_requests" name="special_requests"
                            rows="5"
                            placeholder="e.g. High floor, extra pillows, early check-in..."></textarea>


                        <button type="submit" name="update_preferences">Save Preferences</button>
                    </form>
                </div>


            <div class="form-box">
                <h3 class="form-box-title">Preferences</h3>
                <form method="POST" action="../../controllers/profileController.php">

                    <label for="preferred_room_type_id">Preferred Room Type</label>
                    <select id="preferred_room_type_id" name="preferred_room_type_id">
                        <option value="">— Select room type —</option>
                        <option value="1">Standard</option>
                        <option value="2">Deluxe</option>
                        <option value="3">Suite</option>
                    </select>

                    <label for="special_requests">Special Requests</label>
                    <textarea id="special_requests" name="special_requests"
                        rows="5"
                        placeholder="e.g. High floor, extra pillows, early check-in..."></textarea>
                    <div class="checkbox-group">
                        <input type="checkbox" id="subscribe_offers" name="subscribe_offers" value="1">
                        <label for="subscribe_offers">Subscribe to offers deals</label>
                    </div>

                    <button type="submit" name="update_preferences">Save Preferences</button>
                </form>
            </div>
        </div>

    </div>

</body>

</html>