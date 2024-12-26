<?php

/**
 * Generate the base URL for the application.
 *
 * @param string $path Optional path to append to the base URL.
 * @return string Full URL.
 */
function base_url($path = '') {
    $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https://" : "http://";
    $host = $_SERVER["HTTP_HOST"];
    $base_url = rtrim($protocol . $host . '/' . trim(PROJECT_DIR, '/'), '/') . '/';
    return rtrim($base_url, '/') . '/' . ltrim($path, '/');
}

/**
 * Generate the base file system path for the application.
 *
 * @param string $path Optional path to append to the base path.
 * @return string Full file system path.
 */
function base_path($path = '') {
    $rootpath = rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim(PROJECT_DIR, DIRECTORY_SEPARATOR);
    return rtrim($rootpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
}

/**
 * Get the full file system path for uploads.
 *
 * @param string $filename Optional filename to append to the uploads path.
 * @return string Full uploads path.
 */
function uploads_path($filename = '') {
    return base_path('uploads') . DIRECTORY_SEPARATOR . ltrim($filename, DIRECTORY_SEPARATOR);
}

/**
 * Get the full URL for uploads.
 *
 * @param string $filename Optional filename to append to the uploads URL.
 * @return string Full uploads URL.
 */
function uploads_url($filename = '') {
    return base_url('uploads/' . ltrim($filename, '/'));
}

/**
 * Get the full URL for assets (e.g., CSS, JS).
 *
 * @param string $path Optional path to append to the assets URL.
 * @return string Full assets URL.
 */
function asset_url($path = '') {
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect to a specified URL.
 *
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit; // Stop further script execution
    } else {
        echo "<p class='text-danger'>Unable to redirect. Headers already sent.</p>";
    }
}

/**
 * Check if the current request is a POST request.
 *
 * @return bool True if POST request, false otherwise.
 */
function isPostRequest() {
    return $_SERVER["REQUEST_METHOD"] === "POST";
}

/**
 * Retrieve sanitized POST data.
 *
 * @param string $field The name of the POST field to retrieve.
 * @param string $default Default value if the field is not set.
 * @return string Sanitized POST data or the default value.
 */
function getPostData($field, $default = "") {
    return isset($_POST[$field]) ? htmlspecialchars(trim($_POST[$field]), ENT_QUOTES, 'UTF-8') : $default;
}



/**
 * Handle file uploads and return the uploaded file name or false on failure.
 *
 * @param string $upload_category Directory category for the file.
 * @param string $input_name Name of the file input field.
 * @return string|false Uploaded file name on success, false on failure.
 */
function handleFileUpload($upload_category, $input_name) {
    $uploadDir = base_path("uploads/$upload_category");

    // Ensure the upload directory exists
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo "<p class='text-danger'>Failed to create upload directory.</p>";
        return false;
    }

    // Check if the file was uploaded successfully
    if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
        $originalFileName = basename($_FILES[$input_name]['name']);
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $originalFileName); // Sanitize file name
        $targetFilePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $targetFilePath)) {
            return $fileName; // Return the uploaded file name
        } else {
            echo "<p class='text-danger'>Failed to move uploaded file.</p>";
        }
    } else {
        $error = $_FILES[$input_name]['error'] ?? 'Unknown error';
        echo "<p class='text-danger'>File upload error: $error</p>";
    }

    return false; // Return false on failure
}

// --------CSRF
/**
 * Generate a CSRF token and store it in the session.
 *
 * @return string The generated CSRF token.
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token from the form against the session.
 *
 * @param string $token The token to validate.
 * @return bool True if valid, false otherwise.
 */
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// ---------------sanitization
/**
 * Sanitize input data from GET or POST requests.
 *
 * @param string $field The input field name.
 * @param string $default Default value if the field is not set.
 * @return string The sanitized value.
 */
function sanitizeInput($field, $default = '') {
    return filter_input(INPUT_POST, $field, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        ?? filter_input(INPUT_GET, $field, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        ?? $default;
}

?>
