# Task 2 - Testing & Verification Guide

## Prerequisites
1. MySQL database created: `hotel_management`
2. Database schema imported from `schema.sql`
3. Admin user account created with `role = 'admin'`
4. XAMPP running with Apache and MySQL
5. Project accessible at: `http://localhost/Web-Tech-Hackathon`

## Quick Setup Steps

### 1. Import Database Schema
```bash
mysql -u root -p hotel_management < schema.sql
```

### 2. Create Test Admin User
```sql
USE hotel_management;
INSERT INTO users (name, email, password_hash, phone, nationality, role) 
VALUES ('Admin User', 'admin@test.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36gZvQm6', '+1234567890', 'Bangladesh', 'admin');
-- Password: 'admin123' (pre-hashed)
```

### 3. Verify Files Are Created
- [x] models/RoomTypeModel.php
- [x] models/RoomModel.php
- [x] controllers/roomtypeController.php
- [x] controllers/roomController.php
- [x] api/toggle-status.php
- [x] views/roomtype.php
- [x] views/room.php
- [x] views/admin.php
- [x] assets/admin.css
- [x] public/uploads/rooms/ (directory)

## Testing Workflow

### Test 1: Admin Login & Dashboard Access
1. Open `http://localhost/Web-Tech-Hackathon/views/auth/login.php`
2. Login with:
   - Email: `admin@test.com`
   - Password: `admin123`
3. You should be redirected to admin dashboard
4. Verify sidebar shows "Admin Panel"
5. Click "Room Types" - should load room type management

**Expected Result**: ✓ Admin dashboard accessible with navigation

---

### Test 2: Create Room Type
1. Click "Room Types" in sidebar
2. Click "+ Add Room Type" button
3. Fill form:
   - Name: "Deluxe Room"
   - Description: "Spacious deluxe room with premium amenities"
   - Price/Night: 150.00
   - Max Capacity: 2
   - Amenities: Check [WiFi] [AC] [TV] [Minibar] [Bathtub]
   - Upload: Select a JPEG/PNG image (< 2MB)
4. Click "Create Room Type"
5. Should redirect to room type listing with success message

**Expected Result**: ✓ Room type created, thumbnail uploaded, amenities saved as JSON

---

### Test 3: Verify Amenity Icons Display
1. On room type listing page
2. Look at "Amenities" column for the created room type
3. Should see icons: 📶 🍷 ❄️ 📺 🛁

**Expected Result**: ✓ JSON amenities decoded and displayed with icons

---

### Test 4: Create Second Room Type
1. Click "+ Add Room Type"
2. Fill with:
   - Name: "Standard Room"
   - Description: "Basic room with essential amenities"
   - Price/Night: 80.00
   - Max Capacity: 1
   - Amenities: Check [WiFi] [AC] [TV]
   - Upload: Another image
3. Create

**Expected Result**: ✓ Second room type created

---

### Test 5: Create Individual Room
1. Click "Rooms" in sidebar
2. Click "+ Add Room" button
3. Fill form:
   - Room Number: "101"
   - Floor: 1
   - Room Type: "Deluxe Room" (select from dropdown)
   - Status: "Available"
4. Click "Create Room"
5. Should show success message and room in listing

**Expected Result**: ✓ Room created with correct details

---

### Test 6: Verify Room Listing Display
1. On room listing page
2. Should see table with columns: Room #, Floor, Type, Status, Occupancy, Actions
3. Room 101 should show:
   - Status: "Available" (green badge)
   - Occupancy: "Available" (green badge)
   - Floor: 1

**Expected Result**: ✓ Room displays correctly with occupancy calculation

---

### Test 7: Create Multiple Rooms
1. Create more rooms:
   - Room 102, Floor 1, Type: Deluxe
   - Room 201, Floor 2, Type: Standard
   - Room 202, Floor 2, Type: Standard
   - Room 301, Floor 3, Type: Deluxe
2. All should appear in listing sorted by floor and room number

**Expected Result**: ✓ Multiple rooms created and sorted correctly

---

### Test 8: Test Room Number Validation
1. Try to create room with duplicate number "101"
2. Should show error: "Room number already exists"
3. Form should retain filled data

**Expected Result**: ✓ Validation prevents duplicate room numbers

---

### Test 9: Test AJAX Status Toggle
1. On room listing page
2. Find a room with "Available" status (green badge)
3. Click the status badge
4. Badge should immediately change to "Maintenance" (red)
5. Refresh page - room should still show "Maintenance"
6. Click badge again - should toggle back to "Available"

**Expected Result**: ✓ AJAX toggle works, database updated, no page reload

---

### Test 10: Test Occupancy Status with Booking
1. Create a test booking (requires Task 3 or manual DB insert):
   ```sql
   INSERT INTO bookings (user_id, room_id, checkin_date, checkout_date, total_price, status)
   VALUES (1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 300, 'Confirmed');
   ```
2. Go to room listing
3. Find room 101 - should show:
   - Status: "Available" (actual room status)
   - Occupancy: "Booked" (amber badge - because booking exists)

**Expected Result**: ✓ Occupancy correctly shows "Booked" for active bookings

---

### Test 11: Test Edit Room
1. On room listing, click "Edit" on a room
2. Change:
   - Room Number: 105
   - Floor: 2
3. Click "Update Room"
4. Should redirect to listing showing updated room

**Expected Result**: ✓ Room details updated correctly

---

### Test 12: Test Edit Room Type
1. Click "Room Types"
2. Click "Edit" on a room type
3. Change:
   - Description: "Updated description"
   - Price: 175.00
   - Amenities: Uncheck one, check another
4. Click "Update Room Type"
5. Listing should show updated values and icons

**Expected Result**: ✓ Room type updated, JSON amenities refreshed

---

### Test 13: Test Delete Protection
1. Create booking for room 101
2. Try to delete room 101
3. Should show error: "Cannot delete this room (future bookings may exist)"

**Expected Result**: ✓ Deletion blocked when future bookings exist

---

### Test 14: Test Safe Deletion
1. Create empty room (no bookings)
2. Click "Delete" on that room
3. Confirm deletion
4. Room should be removed from listing

**Expected Result**: ✓ Room deleted when no bookings exist

---

### Test 15: Server-Side Validation
Test various invalid inputs:

**Test 15a**: Empty room type name
- Leave "Name" field empty
- Submit
- Error: "Room type name is required"

**Test 15b**: Negative price
- Price: -50
- Submit
- Error: "Price must be a positive number"

**Test 15c**: Invalid file type
- Upload .txt or .pdf file
- Error: "Only JPEG and PNG files are allowed"

**Test 15d**: File > 2MB
- Upload image > 2MB
- Error: "File size must not exceed 2 MB"

**Expected Result**: ✓ All validations work on server-side

---

### Test 16: Test Non-Admin Access
1. Login as guest user (not admin)
2. Try to access `http://localhost/Web-Tech-Hackathon/controllers/roomController.php`
3. Should be redirected to login page

**Expected Result**: ✓ Non-admin users cannot access admin pages

---

### Test 17: Session Persistence
1. Login as admin
2. Navigate between pages (Room Types → Rooms → Room Types)
3. Session should remain active
4. Sidebar should show "Admin Panel"

**Expected Result**: ✓ Session persists across pages

---

### Test 18: Check Database Integrity
Run queries to verify data integrity:

```sql
-- Check room types with JSON amenities
SELECT id, name, amenities FROM room_types;

-- Check rooms with room type relationship
SELECT r.id, r.room_number, r.floor, r.status, rt.name 
FROM rooms r 
LEFT JOIN room_types rt ON r.room_type_id = rt.id;

-- Check occupancy calculation
SELECT r.room_number, 
       COUNT(b.id) as active_bookings,
       b.status
FROM rooms r
LEFT JOIN bookings b ON r.id = b.room_id 
  AND b.status IN ('Confirmed', 'Checked-In')
  AND b.checkin_date <= CURDATE()
  AND b.checkout_date >= CURDATE()
GROUP BY r.id;
```

**Expected Result**: ✓ All relationships intact, JSON properly formatted

---

## Submission Checklist

- [x] All pages load without PHP errors
- [x] Room type CRUD operations work (Create, Read, Update, Delete)
- [x] Individual room CRUD operations work
- [x] Amenities saved as JSON and decoded correctly
- [x] Room number uniqueness enforced
- [x] Occupancy status calculated from bookings
- [x] AJAX toggle-status works without page reload
- [x] Color-coded occupancy badges display correctly
- [x] Thumbnail upload validates MIME type and size
- [x] Admin-only access enforced
- [x] Server-side validation on all forms
- [x] Prepared statements prevent SQL injection
- [x] Deletion blocked for rooms with future bookings
- [x] JSON amenities encode/decode correctly

## Troubleshooting

### Issue: "Cannot open file" error when creating room type
**Solution**: Ensure `public/uploads/rooms/` directory exists and is writable
```bash
mkdir -p public/uploads/rooms
chmod 755 public/uploads/rooms
```

### Issue: Toggle-status not working
**Solution**: Check browser console for errors. Ensure:
- Session is active and user is admin
- JSON response is valid
- Check `api/toggle-status.php` is accessible

### Issue: Amenities not saving
**Solution**: Verify json_encode is working. Check that at least one amenity checkbox is selected.

### Issue: Room type not deleting
**Solution**: Verify no rooms are assigned to that type. Query:
```sql
SELECT COUNT(*) FROM rooms WHERE room_type_id = <type_id>;
```

## Performance Notes

- Indexes created on frequently queried columns
- LEFT JOIN for occupancy avoids multiple queries
- JSON storage allows flexible amenities without schema changes
- Prepared statements optimize repeated queries

## Next Steps for Other Tasks

- **Task 3** can use `RoomTypeModel::getAllRoomTypes()` and `RoomModel::getAvailableRoomOfType()`
- **Task 4** can query occupancy using `RoomModel::getAllRoomsWithOccupancy()` and bookings table
