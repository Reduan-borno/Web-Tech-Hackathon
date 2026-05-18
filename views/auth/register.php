<!DOCTYPE html>
<html>

<head>
    <title>Register Hotel Booking</title>
    <link rel="stylesheet" href="../../assets/register.css">
</head>

<body>

    <div class="container">
        <h2>Hotel Booking Portal Register</h2>
        <p id="createAccount">Create your guest account</p>

        <form id="registerForm" method="POST" action="../../controllers/registerController.php">
            <label for="name">Full Name</label><br>
            <input type="text" name="name" id="name" placeholder="Reduanul Islam" class="form-input">
            <span id="nameError" class="error-message"></span>

            <label for="email">Email Address</label>
            <input type="text" name="email" id="email" placeholder="name@example.com" class="form-input">
            <span id="emailError" class="error-message"></span>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="01700000000" class="form-input">
            <span id="phoneError" class="error-message"></span>

            <label for="nationality">Nationality</label>
            <select id="nationality" name="nationality" class="form-input">
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
            <span id="nationalityError" class="error-message"></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Min. 8 characters" class="form-input">
            <span id="passwordError" class="error-message"></span>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" class="form-input">
            <span id="confirmError" class="error-message"></span>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>

        <p>Already have an account? <a href="login.php">Sign in here</a></p>

        <script src="../../ajax/validateRegister.js"></script>
    </div>
</body>

</html>