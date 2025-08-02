<?php
// This is a very simple PHP script that outputs the name of each bit of information in the browser window, and then sends it all to an email address you add to the script.
// Many thanks to Adam Eivy for his invaluable help with modifying the PHP.
// Credit to Drew King for updating this script to work with AJAX.

if (empty($_POST)) {
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}

// Function to sanitize user input for HTML
function clear_user_input($value)
{
    $value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
    $value = strip_tags($value, "<b><i><em><strong><p><br>");
    $value = stripslashes($value);
    $value = trim($value);
    return $value;
}

if ($_POST["message"] == "Please share any comments you have here") {
    $_POST["message"] = "";
}

// Sanitize and set name and email
$first_name = isset($_POST["first_name"])
    ? clear_user_input($_POST["first_name"])
    : "Anonymous";
$last_name = isset($_POST["last_name"])
    ? clear_user_input($_POST["last_name"])
    : "Doe";
$name = "{$first_name} {$last_name}";
$email = isset($_POST["email"])
    ? clear_user_input($_POST["email"])
    : "no-reply@example.com";
$tel = isset($_POST["phone"]) ? clear_user_input($_POST["phone"]) : "";

// Validate email address
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email address.",
    ]);
    exit();
}

// Validate phone number

$digits = preg_replace("/\D/", "", $tel);

if (strlen($digits) < 10 || strlen($digits) > 15) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid phone number.",
    ]);
    exit();
}

// Create body of message
$sanitized_message = clear_user_input($_POST["message"]);
$body = "From TherapyWithDiana contact form:\n\n{$sanitized_message}";

$body .= "\n\n{$name}";
$body .= "\n{$email}";
$body .= "\n{$digits}";

$from =
    "From: newclients@therapywithdiana.com" .
    "\r\n" .
    "Reply-To: " .
    $email .
    "\r\n" .
    "X-Mailer: PHP/" .
    phpversion();

// Log the email content for debugging
error_log("Attempting to send email with the following details:");
error_log("From: " . $from);
error_log("Body: " . $body);

// Send email
$success = mail(
    "newclients@therapywithdiana.com",
    "{$name} - Inquiry about Therapy With Diana",
    $body,
    $from,
);
//$success = TRUE; // for testing

// Pass result of mail() function to JavaScript to display message in the browser.
header("Content-Type: application/json");
if ($success) {
    echo json_encode([
        "status" => "Success",
        "confirmation" => "Thank you, {$name}! Your message has been successfully sent from {$email}.",
        "message" => nl2br("Your message:\n \"{$body}\""),
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" =>
            "Sorry, there was a problem sending your message. Please try again.",
    ]);
    error_log("Failed to send email with the following details:");
    error_log("From: " . $from);
    error_log("Body: " . $body);
    error_log("Error: " . error_get_last()["message"]);
}
?>
