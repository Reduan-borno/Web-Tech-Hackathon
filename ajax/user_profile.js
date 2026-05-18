document.querySelector('form[action="../controllers/profileController.php"]')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    document.getElementById('nameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('nationalityError').textContent = '';

    const response = await fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    });

    const data = await response.json();

    if (data.success) {

        alert('Profile updated successfully!');

    } else {

        if (data.errors.name)
            document.getElementById('nameError').textContent = data.errors.name;

        if (data.errors.email)
            document.getElementById('emailError').textContent = data.errors.email;

        if (data.errors.phone)
            document.getElementById('phoneError').textContent = data.errors.phone;

        if (data.errors.nationality)
            document.getElementById('nationalityError').textContent = data.errors.nationality;
    }
});

document.querySelector('form[action="../controllers/preferenceController.php"]')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    document.getElementById('roomTypeError').textContent = '';

    const checked = document.getElementById('subscribe_offers').checked;

    document.cookie = "subscribe_offers=" + (checked ? '1' : '0');

    const response = await fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    });

    const data = await response.json();

    if (data.success) {

        alert('Preferences saved successfully!');

    } else {

        if (data.errors.preferred_room_type_id)
            document.getElementById('roomTypeError').textContent =
                data.errors.preferred_room_type_id;
    }
});