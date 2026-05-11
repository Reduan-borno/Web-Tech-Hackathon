<!DOCTYPE html>
<head>
</head>
<body>
    <h2>Hotel Booking Portal Register</h2>
    <p>Create your guest account</p>

    <form id = "registerForm" onsubmit="return validateRegister()">
        <label for="name">Full Name : </label><br>
        <input type="text" name="name" id="name" placeholder = "Enter your Name">
        <span id= "nameError" style="color:red"></span><br>

        <label for="email">Email : </label> <br>
        <input type="email" name="email" id="email" placeholder="Enter Your Email">      
        <span id="emailError" style="color:red"></span> <br>

        <label for="phone">Phone Number:</label><br>
        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number">
        <span id="phoneError" style="color:red;"></span><br>

        <label for="nationality">Nationality:</label><br>
        <select id="nationality" name="nationality">
            <option value="">Select nationality</option>
            <option value="Bangladeshi">Bangladeshi</option>
            <option value="Indian">Indian</option>
            <option value="Pakistani">Pakistani</option>
            <option value="American">American</option>
            <option value="British">British</option>
            <option value="Canadian">Canadian</option>
            <option value="Australian">Australian</option>
            <option value="Others">Others</option>
        </select>
        <span id="nationalityError" style="color:red;"></span><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="atleast 8 character">
        <span id="passwordError" style="color:red;"></span><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password">
        <span id="confirmError" style="color:red;"></span><br>
        <br>
        <button type="submit">Create Account</button>

    </form>
      <p>Already have account? <a href="login.php">Sign in here</a></p>

    <script src="../ajax/validateRegister.js"></script>

</body>
</html>

