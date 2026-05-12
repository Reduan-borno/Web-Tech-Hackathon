<!DOCTYPE html>
<head>
    <title>Register Hotel Booking</title>
</head>
<body>
    <h2> Hotel Booking Portal — Register</h2>
    <p>Create your guest account</p>
    <hr>

    <form id = "registerForm">
        <label for="name">Full Name:</label><br>
        <input type="text" name="name" id="name" placeholder = "Reduanul Islam">
        <span id= "nameError" style="color:red"></span><br>

        <label for="email">Email Address:</label> <br>
        <input type="text" name="email" id="email"  placeholder="Name@example.com">   
        <span id="emailError" style="color:red"></span> <br>

        <label for="phone">Phone Number:</label><br>
        <input type="tel" id="phone" name="phone" placeholder="01700000000">
        <span id="phoneError" style="color:red;"></span><br>

        <label for="nationality">Nationality:</label><br>
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
        <span id="nationalityError" style="color:red;"></span><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Min. 8 characters">
        <span id="passwordError" style="color:red;"></span><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password"><br>
        <span id="confirmError" style="color:red;"></span><br>

        <button type="submit">Create Account</button>

    </form>
     <p>Already have an account? <a href="login.php">Sign in here</a></p>

    <script src="../../ajax/validateRegister.js"></script>
</body>
</html>