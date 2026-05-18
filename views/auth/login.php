<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login — Hotel Booking</title>
    <link rel="stylesheet" href="../../assets/login.css">
</head>

<body>

    <div class="container">
        <h2>Sign in</h2>
        <p>Access your admin account class</p>

        <form id="loginForm" method="POST" action="../../controllers/loginController.php">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" placeholder="name@example.com" required>
            <span id="emailError"></span>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" required>
            <span id="passError"></span>

            <div class="checkbox-group">
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit">Login →</button>
        </form>

        <p id="registerLink">No account? <a href="register.php">Register here</a></p>
    </div>

</body>

</html>