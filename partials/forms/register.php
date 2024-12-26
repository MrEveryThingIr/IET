<?php
// Initialize variables
$firstname = $lastname = $username = $password = $email = $phone = $confirm_password = $birthdate = $gender = "";

// Check if form is submitted
if (isPostRequest()) {
    // Retrieve and sanitize form data using helpers
    $firstname = getPostData("firstname");
    $lastname = getPostData("lastname");
    $username = getPostData("username");
    $password = getPostData("password");
    $confirm_password = getPostData("confirm_password");
    $email = getPostData("email");
    $phone = getPostData("phone");
    $birthdate = getPostData("birthdate");
    $gender = getPostData("gender");

    // Validate required fields
    if (
        empty($firstname) || empty($lastname) || empty($username) || 
        empty($password) || empty($confirm_password) || empty($email) || 
        empty($phone) || empty($birthdate) || empty($gender)
    ) {
        echo "<div class='alert alert-danger text-center'>All fields are required!</div>";
    } elseif ($password !== $confirm_password) {
        // Validate password match
        echo "<div class='alert alert-danger text-center'>Passwords do not match!</div>";
    } else {
        // Assign default values for role and status
        $role = 'user'; // Default role
        $status = 'active'; // Default status

        // Attempt registration with User class
        $user = new User();
        $registered = $user->register($username, $password, $email, $phone, $firstname, $lastname, $birthdate, $gender, $role, $status);

        if ($registered) {
            redirect(base_url("index.php?current_page=login"));
            exit;
        } else {
            echo "<div class='alert alert-danger text-center'>Registration failed. Please try again!</div>";
        }
    }
}
?>

<!-- Main Content -->
<main class="container my-5">
    <h2 class="text-center mb-4">Register</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name *</label>
                    <input 
                        type="text" 
                        name="firstname" 
                        class="form-control" 
                        id="firstname" 
                        value="<?= htmlspecialchars($firstname); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name *</label>
                    <input 
                        type="text" 
                        name="lastname" 
                        class="form-control" 
                        id="lastname" 
                        value="<?= htmlspecialchars($lastname); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone *</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-control" 
                        id="phone" 
                        value="<?= htmlspecialchars($phone); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username *</label>
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        id="username" 
                        value="<?= htmlspecialchars($username); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control" 
                        id="email" 
                        value="<?= htmlspecialchars($email); ?>" 
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
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        class="form-control" 
                        id="confirm_password" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="birthdate" class="form-label">Birthdate *</label>
                    <input 
                        type="date" 
                        name="birthdate" 
                        class="form-control" 
                        id="birthdate" 
                        value="<?= htmlspecialchars($birthdate); ?>" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender *</label>
                    <select 
                        name="gender" 
                        id="gender" 
                        class="form-select" 
                        required
                    >
                        <option value="" disabled <?= empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                        <option value="male" <?= $gender === "male" ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?= $gender === "female" ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?= $gender === "other" ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="mt-3 text-center">
                Already have an account? <a href="<?= base_url('index.php?current_page=login'); ?>">Login here</a>.
            </p>
        </div>
    </div>
</main>

<?php include "partials/footer.php"; ?>
