// public/js/roomStatus.js

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.badge').forEach(badge => {
        badge.addEventListener('click', function() {
            const roomId = this.dataset.roomId;
            fetch('../controllers/ToggleRoomStatusController.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'room_id=' + roomId
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    this.className = 'badge ' + data.new_status;
                } else {
                    alert(data.error);
                }
            });
        });
    });
});
