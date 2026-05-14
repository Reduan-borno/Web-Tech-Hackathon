<!DOCTYPE html>
<html>
<head>
    <title>Login — Hotel Booking</title>
    <link rel="stylesheet" href="../../assets/login.css">
</head>
<body>

<div class="container">

    <div class="left-panel">
        <p class="panel-brand">***** GRAND PALACE</p>
        <h3 class="panel-heading">Experience<br>true luxury</h3>
        <p class="panel-sub">5-star experience<br>awaits you</p>
    </div>

    <div class="login-container">
        <h2>Sign in</h2>
        <p>Access your elite account</p>

        <form id="loginForm" method="POST" action="../../controllers/loginController.php">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" placeholder="name@example.com">
            <span id="emailError"></span>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••">
            <span id="passError"></span>

            <div class="checkbox-group">
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit">Login →</button>
        </form>

        <p id="registerLink">No account? <a href="register.php">Register here</a></p>
    </div>

</div>

</body>
</html>