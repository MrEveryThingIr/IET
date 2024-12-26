<?php
if (isPostRequest()) {
    // Sanitize input
    $email = getPostData('email');

    // Validate the email
    if (empty($email)) {
        echo "<div class='alert alert-danger text-center'>Email is required!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger text-center'>Invalid email format!</div>";
    } else {
        // Initialize User class
        $user = new User();

        // Check if the email exists
        if ($user->emailExists($email)) {
            // Generate a unique token
            $resetToken = bin2hex(random_bytes(32));
            
            // Store the token in the database
            if ($user->storeResetToken($email, $resetToken)) {
                // Send the reset email
                $resetLink = base_url("reset-password.php?token=$resetToken");
                $subject = "Password Reset Request";
                $message = "Click the link below to reset your password:\n\n$resetLink";
                $headers = "From: noreply@" . $_SERVER['HTTP_HOST'];

                if (mail($email, $subject, $message, $headers)) {
                    echo "<div class='alert alert-success text-center'>A password reset link has been sent to your email.</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Failed to send reset email. Please try again later.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Failed to process your request. Please try again later.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>No account found with this email address.</div>";
        }
    }
}
?>

<!-- Main Content -->
<main class="container my-5">
    <h2 class="text-center mb-4">Forgot Password</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control" 
                        id="email" 
                        value="<?= htmlspecialchars($email ?? ''); ?>" 
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>
    </div>
</main>
