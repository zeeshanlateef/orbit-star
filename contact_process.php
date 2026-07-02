<?php
header('Content-Type: application/json');

// Function to sanitize input data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify reCAPTCHA
    $recaptchaSecretKey = '6Ld8bz0tAAAAAJnPsQKrbcCD77m3ItqYpRuPTfnh';
    $recaptchaResponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    if (empty($recaptchaResponse)) {
        echo json_encode(['status' => 'error', 'message' => 'Please complete the reCAPTCHA.']);
        exit;
    }

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($recaptchaData),
        ],
    ];

    $context = stream_context_create($options);
    $recaptchaResult = json_decode(file_get_contents($recaptchaUrl, false, $context));

    if (!$recaptchaResult->success) {
        echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed.']);
        exit;
    }

    // Sanitize and Validate Inputs
    $name = isset($_POST["name"]) ? test_input($_POST["name"]) : "";
    $email = isset($_POST["email"]) ? test_input($_POST["email"]) : "";
    $phone = isset($_POST["phone"]) ? test_input($_POST["phone"]) : "";
    $subject = isset($_POST["subject"]) ? test_input($_POST["subject"]) : "New Inquiry from Orbit Star Services Contact Form";
    $message_content = isset($_POST["message"]) ? test_input($_POST["message"]) : "";

    if (empty($name) || empty($phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Name and Phone are required.']);
        exit;
    }

    // Send Email
    $to1 = 'office@ajinfotek.in, junaid@ajinfotek.in';
    $email_subject = "New Inquiry: $subject";

    // HTML Email Template
    $email_body = "
    <html>
    <head>
        <style>
            .email-container {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                max-width: 600px;
                margin: 0 auto;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
                color: #333;
            }
            .header {
                background-color: #000056;
                color: #ffffff;
                padding: 25px;
                text-align: center;
            }
            .header h2 {
                margin: 0;
                font-size: 24px;
                color: #ffffff;
            }
            .content {
                padding: 30px;
            }
            .content p {
                font-size: 16px;
                line-height: 1.6;
                margin-top: 0;
            }
            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .info-table th {
                text-align: left;
                padding: 12px;
                background-color: #f8f9fa;
                border-bottom: 2px solid #d8aa53;
                width: 30%;
                font-weight: 600;
            }
            .info-table td {
                padding: 12px;
                border-bottom: 1px solid #eee;
            }
            .message-box {
                margin-top: 30px;
                padding: 20px;
                background-color: #f1f7fa;
                border-left: 4px solid #000056;
                font-style: italic;
            }
            .footer {
                background-color: #f8f9fa;
                padding: 15px;
                text-align: center;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h2>Orbit Star Services</h2>
                <p style='margin: 5px 0 0; opacity: 0.9; color: #d8aa53;'>New Website Inquiry</p>
            </div>
            <div class='content'>
                <p>Hello Team,</p>
                <p>You have received a new inquiry through the contact form on your website. Here are the details:</p>
                
                <table class='info-table'>
                    <tr>
                        <th>Name</th>
                        <td>$name</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>" . ($email ? $email : "Not Provided") . "</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>$phone</td>
                    </tr>
                    <tr>
                        <th>Service Interested</th>
                        <td>$subject</td>
                    </tr>
                </table>

                <div class='message-box'>
                    <strong>Message:</strong><br>
                    " . nl2br($message_content) . "
                </div>
            </div>
            <div class='footer'>
                This inquiry was sent from the contact form on orbitstarservices.com<br>
                &copy; " . date('Y') . " Orbit Star Services. All Rights Reserved.
            </div>
        </div>
    </body>
    </html>
    ";

    // Headers for HTML email
    $from_email = "noreply@orbitstarservices.com";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Orbit Star Website <$from_email>\r\n";
    if (!empty($email)) {
        $headers .= "Reply-To: $email\r\n";
    }
    $headers .= "Return-Path: $from_email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    $mail_sent = mail($to1, $email_subject, $email_body, $headers, "-f$from_email");

    // Database Insertion
    $db_success = false;
    try {
        require_once 'config/config.php';
        $db = new dbClass();
        $db_success = $db->executeStatement(
            "INSERT INTO contact_us (name, email, phone, subject, message) VALUES (:name, :email, :phone, :subject, :message)",
            [
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':subject' => $subject,
                ':message' => $message_content
            ]
        );
    } catch (Exception $e) {
        // Log error but don't fail if mail was sent
    }

    if ($mail_sent || $db_success) {
        echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Submission failed. Please try again.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>