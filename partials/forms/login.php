<?php
$usernameOrEmail = $remember = "";

if (isPostRequest()) {
    // Validate CSRF token
    if (!validateCsrfToken(sanitizeInput('csrf_token'))) {
        echo "<div class='alert alert-danger text-center'>Invalid CSRF token. Please refresh the page and try again.</div>";
        exit;
    }

    // Retrieve and sanitize form data
    $usernameOrEmail = sanitizeInput('usernameOrEmail');
    $password = sanitizeInput('password');
    $remember = isset($_POST['remember']);

    // Validate required fields
    if (empty($usernameOrEmail) || empty($password)) {
        echo "<div class='alert alert-danger text-center'>All fields are required!</div>";
    } else {
        // Initialize User class and attempt login
        $user = new User();
        $userData = $user->login($usernameOrEmail, $password);

        if (is_array($userData)) {
            // Login successful
            echo "<div class='alert alert-success text-center'>Login successful! Redirecting...</div>";

            // Set session keys for logged-in user
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            $_SESSION['logged_in'] = true;

            // Handle "Remember Me" functionality
            if ($remember) {
                $token = bin2hex(random_bytes(16)); // Generate secure token
                setcookie("auth_token", $token, time() + (30 * 24 * 60 * 60), "/", "", true, true); // 30 days, HttpOnly, Secure
                $user->storeRememberToken($usernameOrEmail, $token); // Store token in the database
            }

            redirect(base_url("index.php?current_page=admin"));
            exit;
        } else {
            echo "<div class='alert alert-danger text-center'>Login failed: Invalid username, email, or password.</div>";
        }
    }
}
?>

<!-- Main Content -->
<main class="container my-5">
    <h2 class="text-center mb-4">Login</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken(); ?>">
                <div class="mb-3">
                    <label for="usernameOrEmail" class="form-label">Username or Email *</label>
                    <input 
                        type="text" 
                        name="usernameOrEmail" 
                        class="form-control" 
                        id="usernameOrEmail" 
                        value="<?= htmlspecialchars($usernameOrEmail); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password *</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control" 
                        id="password" 
                        required
                    >
                </div>
                <div class="mb-3 form-check">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="form-check-input" 
                        id="remember"
                    >
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">
                Don't have an account? <a href="<?= base_url('index.php?current_page=register'); ?>">Register here</a>.
            </p>
        </div>
    </div>
</main>
