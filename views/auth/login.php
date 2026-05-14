<body>
    <div class="login-container">
        <h2>Hotel Booking Portal — Login</h2>
     
        <p>Sign in to your guest account</p>
        <hr>

        <form id="loginForm" method="POST">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email">
            <span id="emailError"></span>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span id="passError"></span>

            <div class="checkbox-group">
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me">Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>