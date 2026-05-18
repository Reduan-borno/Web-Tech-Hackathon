<!DOCTYPE html>
<html>

<head>
    <title>Register Hotel Booking</title>
    <link rel="stylesheet" href="../../assets/register.css">
</head>

<body>

    <div class="container">

        <div class="left-panel">
            <h3 class="panel-heading">Create Account</h3>
        </div>

        <div class="right-panel">
            <h2> Hotel Booking Portal Register</h2>
            <p id="createAccount">Create your guest account</p>

            <form id="registerForm">
                <label for="name">Full Name</label><br>
                <input type="text" name="name" id="name" placeholder="Reduanul Islam" class="form-input">
                <span id="nameError" class="error-message"></span><br>

                <label for="email">Email Address</label> <br>
                <input type="text" name="email" id="email" placeholder="Name@example.com" class="form-input">
                <span id="emailError" class="error-message"></span> <br>

                <label for="phone">Phone Number</label><br>
                <input type="tel" id="phone" name="phone" placeholder="01700000000" class="form-input">
                <span id="phoneError" class="error-message"></span><br>

                <label for="nationality" class=>Nationality</label><br>
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
                <span id="nationalityError" class="error-message"></span><br>

                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" placeholder="Min. 8 characters" class="form-input"><br>
                <span id="passwordError" class="error-message"></span><br>

                <label for="confirm_password">Confirm Password</label><br>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" class="form-input"><br>
                <span id="confirmError" style="color:red;"></span><br>

                <button type="submit" class="submit-btn">Create Account</button>

            </form>
            <p>Already have an account? <a href="login.php">Sign in here</a></p>

            <script src="../../ajax/validateRegister.js"></script>

        </div>
    </div>
</body>

</html>