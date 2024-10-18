<?php
// This is a very simple PHP script that outputs the name of each bit of information in the browser window, and then sends it all to an email address you add to the script.
// Many thanks to Adam Eivy for his invaluable help with modifying the PHP.
// Credit to Drew King for updating this script to work with AJAX.

if (empty($_POST)) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Function to sanitize user input
function clear_user_input($value) {
    $value = str_replace("\n", '', trim($value));
    $value = str_replace("\r", '', $value);
    return $value;
}

if ($_POST['message'] == 'Please share any comments you have here') $_POST['message'] = '';

// Sanitize and set name and email
$name = isset($_POST['name']) ? clear_user_input($_POST['name']) : 'Anonymous';
$email = isset($_POST['email']) ? clear_user_input($_POST['email']) : 'no-reply@example.com';

// Validate email address
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit();
}

// Create body of message
$body = "First message:\n";

foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    $key = clear_user_input($key);
    $value = clear_user_input($value);
    $$key = $value;

    $body .= "$key: $value\n";
}

$from = 'From: newclients@therapywithdiana.com' . "\r\n" . 'Reply-To: ' . $email . "\r\n" . 'X-Mailer: PHP/' . phpversion();

// Log the email content for debugging
error_log("Attempting to send email with the following details:");
error_log("From: " . $from);
error_log("Body: " . $body);

// Send email
$success = mail('newclients@therapywithdiana.com', 'Inquiry about Therapy With Diana', $body, $from);
//$success = TRUE; // for testing

// Pass result of mail() function to JavaScript to display message in the browser.
header('Content-Type: application/json');
if ($success) {
    echo json_encode(["status" => "Success", "confirmation" => "Thank you, $name! Your message has been successfully sent from $email", "message" => "Your message: \"$message\""]);
} else {
    echo json_encode(["status" => "error", "message" => "Sorry, there was a problem sending your message. Please try again."]);
}
?>
