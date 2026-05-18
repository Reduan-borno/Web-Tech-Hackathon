# Task 2 — Room Type & Room Management (Admin) - Implementation Guide

## Overview
This implementation provides the complete admin interface for managing room types and individual rooms with AJAX functionality for status toggling and occupancy tracking.

## Database Setup

### 1. Create the Database
```sql
CREATE DATABASE IF NOT EXISTS hotel_management;
USE hotel_management;
```

### 2. Import the Schema
Copy and execute the SQL commands from `schema.sql` to create all necessary tables with proper relationships and indexes.

## Files Created/Modified

### Models
- **models/RoomTypeModel.php** - Functions for room type CRUD operations
  - `createRoomType()` - Create room type with JSON amenities
  - `getAllRoomTypes()` - Fetch all room types
  - `getRoomTypeById()` - Fetch specific room type
  - `updateRoomType()` - Update room type details
  - `deleteRoomType()` - Delete room type (with safety check)
  - `decodeAmenities()` - Parse amenities JSON

- **models/RoomModel.php** - Functions for room management
  - `createRoom()` - Create individual room
  - `getAllRoomsWithOccupancy()` - Fetch all rooms with current occupancy status
  - `getRoomById()` - Get specific room details
  - `updateRoom()` - Update room attributes
  - `deleteRoom()` - Delete room (blocked if future bookings exist)
  - `toggleRoomStatus()` - Toggle between 'available' and 'maintenance'
  - `roomNumberExists()` - Check for duplicate room numbers
  - `getAvailableRoomOfType()` - Find available room for booking (used by Task 3)

### Controllers
- **controllers/roomtypeController.php**
  - Handles room type CRUD operations
  - Server-side validation for all inputs
  - File upload handling (JPEG/PNG, ≤2MB)
  - Amenities array JSON encoding/decoding
  - Admin-only authentication check

- **controllers/roomController.php**
  - Handles room CRUD operations
  - Validates room number uniqueness
  - Prevents deletion of rooms with future bookings
  - Loads occupancy status for each room
  - Admin-only access control

### API Endpoints
- **api/toggle-status.php**
  - POST endpoint for AJAX status toggling
  - Accepts JSON: `{"room_id": <id>}`
  - Returns JSON with new status and badge styling
  - Admin authentication required

### Views
- **views/roomtype.php**
  - Listing page showing all room types with amenity icons
  - Create form with:
    - Name, description, price per night
    - Max capacity input
    - Thumbnail upload (with preview)
    - Amenities checkbox grid (WiFi, AC, TV, Minibar, Safe, Bathtub, Balcony)
  - Edit form for modifying existing types
  - Delete functionality with safety prompts
  - Icons for amenities display: 📶 WiFi, ❄️ AC, 📺 TV, 🍷 Minibar, 🔒 Safe, 🛁 Bathtub, 🪟 Balcony

- **views/room.php**
  - Listing page with room inventory table
  - Displays: Room #, Floor, Type, Status, Occupancy, Actions
  - Occupancy badges (green=Available, amber=Booked, red=Maintenance)
  - Status badge (clickable for AJAX toggle)
  - Create form with:
    - Room number (validated for uniqueness)
    - Floor number
    - Room type dropdown
    - Initial status (available/maintenance)
  - Edit form for room modifications
  - Delete button (blocked if future bookings exist)

### Styling
- **assets/admin.css** - Complete admin dashboard styling
  - Sidebar navigation with gradient
  - Responsive grid layouts
  - Form styling with validation
  - Table styling with hover effects
  - Badge styling for status indicators

## Features Implemented

### 1. Room Type Management ✓
- ✅ Create room types with all required fields
- ✅ Upload thumbnail images (JPEG/PNG validation, 2MB limit)
- ✅ Select amenities from predefined list
- ✅ Save amenities as JSON array
- ✅ Display amenity icons in listing
- ✅ Edit existing room types
- ✅ Delete room types (with dependency check)
- ✅ Server-side validation for all fields

### 2. Individual Room Management ✓
- ✅ Create rooms with unique room numbers
- ✅ Assign room types
- ✅ Set floor level
- ✅ Set initial status
- ✅ Edit room details
- ✅ Delete rooms (blocked if future bookings)
- ✅ Server-side validation
- ✅ Duplicate room number detection

### 3. Room Occupancy Status ✓
- ✅ "Available" - Room is available and not in maintenance
- ✅ "Booked" - Room has a future Confirmed/Checked-In booking
- ✅ "Maintenance" - Room is marked for maintenance
- ✅ LEFT JOIN on bookings table for occupancy calculation
- ✅ Color-coded badges (green/amber/red)

### 4. AJAX Maintenance Toggle ✓
- ✅ Click status badge to toggle (available ↔ maintenance)
- ✅ POST to api/toggle-status.php
- ✅ JSON request/response format
- ✅ Badge updates in-place without page reload
- ✅ Loading state prevents double-clicks
- ✅ Error handling with user feedback
- ✅ Admin authentication check

## Testing Checklist

### 1. Authentication
- [ ] Redirect non-admin users to login
- [ ] Login as admin user and access room management
- [ ] Check session variables are set correctly

### 2. Room Type Creation
- [ ] Create room type with all required fields
- [ ] Verify JSON amenities are saved correctly
- [ ] Upload thumbnail image and verify it's stored in public/uploads/rooms/
- [ ] Try uploading non-image file (should fail)
- [ ] Try uploading file > 2MB (should fail)
- [ ] Verify price must be positive
- [ ] Verify amenities must have at least one selected
- [ ] Check amenity icons display in listing

### 3. Room Type Editing
- [ ] Edit existing room type
- [ ] Change amenities selection
- [ ] Update thumbnail image
- [ ] Verify changes persist in database

### 4. Room Type Deletion
- [ ] Delete room type with no rooms (should succeed)
- [ ] Attempt to delete room type with rooms assigned (should fail gracefully)

### 5. Room Creation
- [ ] Create room with unique room number
- [ ] Assign to room type
- [ ] Set floor level
- [ ] Verify in listing with correct occupancy status

### 6. Room Number Validation
- [ ] Try creating room with duplicate number (should fail)
- [ ] Edit room and keep same number (should succeed)
- [ ] Change to different room number (should succeed)

### 7. Room Occupancy Calculation
- [ ] Create test booking (or use Task 3)
- [ ] Verify room shows "Booked" status for active bookings
- [ ] Verify room shows "Available" when no active bookings
- [ ] Verify maintenance rooms show "Maintenance" status regardless of bookings

### 8. AJAX Status Toggle
- [ ] Click "Available" badge to toggle to "Maintenance"
- [ ] Verify badge changes color and text in-place
- [ ] Verify database is updated (no page reload)
- [ ] Click "Maintenance" badge to toggle back to "Available"
- [ ] Test rapid clicking (should not allow multiple concurrent requests)

### 9. Room Editing
- [ ] Edit room number (check uniqueness validation)
- [ ] Edit floor
- [ ] Change room type
- [ ] Edit status
- [ ] Verify changes persist

### 10. Room Deletion
- [ ] Delete room with no bookings (should succeed)
- [ ] Create test booking
- [ ] Attempt to delete room with Confirmed booking (should fail)
- [ ] Mark booking as Checked-Out and delete room (should succeed)

### 11. Server-Side Validation
- [ ] Empty room number (should show error)
- [ ] Negative floor (should show error)
- [ ] No room type selected (should show error)
- [ ] Invalid room type ID (should show error)
- [ ] Invalid status value (should show error)

### 12. Error Messages
- [ ] Check error messages display clearly
- [ ] Verify form data persists after validation error
- [ ] Check success messages appear after operations

### 13. Admin CSS & Layout
- [ ] Sidebar navigation displays correctly
- [ ] Tables are responsive
- [ ] Form layouts work on different screen sizes
- [ ] Color coding for occupancy badges is clear

## Database Structure

```
room_types
├── id (PK)
├── name (VARCHAR)
├── description (TEXT)
├── price_per_night (DECIMAL)
├── max_capacity (INT)
├── thumbnail_path (VARCHAR)
├── amenities (JSON)

rooms
├── id (PK)
├── room_type_id (FK → room_types)
├── room_number (VARCHAR UNIQUE)
├── floor (INT)
├── status (ENUM: available, maintenance)

bookings (from Task 3 & 4)
├── id (PK)
├── room_id (FK → rooms)
├── checkin_date (DATE)
├── checkout_date (DATE)
├── status (ENUM: Pending, Confirmed, Checked-In, Checked-Out, Cancelled)
```

## Security Measures

- ✅ `session_start()` on every protected page
- ✅ Admin-only role check on all admin pages
- ✅ MySQLi prepared statements for all queries
- ✅ File upload MIME type validation
- ✅ File upload size limit enforcement
- ✅ Input sanitization with htmlspecialchars()
- ✅ JSON encoding for complex data
- ✅ Foreign key constraints in database

## JSON Amenities Format

Amenities are stored as JSON array in `room_types.amenities`:
```json
["WiFi", "AC", "TV", "Minibar", "Safe", "Bathtub", "Balcony"]
```

Example query to filter rooms by amenity (for Task 3):
```php
$amenities = json_decode($roomType['amenities'], true);
```

## Integration Notes

### For Task 3 (Student 3 - Room Search & Booking):
- Use `getRoomTypeById()` and `getAllRoomTypes()` to populate dropdowns
- Use `getAvailableRoomOfType()` to find available rooms for date range
- Amenities are decoded JSON arrays in `room_types.amenities`

### For Task 4 (Student 4 - Booking Management):
- Room occupancy status is calculated in `getAllRoomsWithOccupancy()`
- Use bookings table with room_id and date ranges to show occupancy

## Quick Start

1. **Set up database**: Run schema.sql in MySQL
2. **Create admin user**: Register or manually insert with role='admin'
3. **Access admin panel**: Login as admin → click "Room Types" or "Rooms" in sidebar
4. **Create room type**: Click "Add Room Type", fill form, select amenities
5. **Upload thumbnail**: Select JPEG/PNG image ≤ 2MB
6. **Create room**: Click "Add Room", enter number and assign to room type
7. **Toggle status**: Click status badge to toggle maintenance

## Notes

- All forms include client-side validation for UX, but server-side validation is enforced
- Thumbnails are stored in `public/uploads/rooms/` with timestamp prefix for uniqueness
- Amenities JSON allows flexible expansion without schema changes
- Occupancy status is calculated in real-time from bookings table
- AJAX toggle-status provides seamless UX for room maintenance marking
- All queries use prepared statements to prevent SQL injection
