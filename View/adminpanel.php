<?php
session_start();
if (!isset($_SESSION['UserType']) || $_SESSION['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>
<h2>Admin Panel</h2>
<ul>
    <li><a href="RoomTypeList.php">Manage Room Types</a></li>
    <li><a href="RoomList.php">Manage Rooms</a></li>
</ul>
