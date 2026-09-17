<?php
// Security: Honeypot anti-spam check
if (!empty($_POST['website_hp'])) {
    die("Spam detected.");
}

// Sanitize inputs
$name    = htmlspecialchars(trim($_POST['name'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(trim($_POST['phone'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (empty($name) || empty($email)) {
    header("Location: index.html?status=error");
    exit;
}

// 1. Send Instant Card to Discord Webhook
$discord_webhook_url = "https://discord.com/api/webhooks/1550105246263550033/NzEZwmiwri3JqOpwX4WhNJpgXOY1pMGSXFW3fIcQz76_9h3lzN5uLUMDv918n3QCrZLT";

$discord_payload = json_encode([
    "username" => "CSR Lead Bot",
    "embeds" => [
        [
            "title" => "🔔 New Website Lead Received!",
            "color" => 3447003, // Blue
            "fields" => [
                ["name" => "Name", "value" => $name, "inline" => true],
                ["name" => "Email", "value" => $email, "inline" => true],
                ["name" => "Phone", "value" => $phone ?: 'Not provided', "inline" => true],
                ["name" => "Message", "value" => $message ?: 'No message', "inline" => false]
            ],
            "footer" => ["text" => "CSR Website Lead System"]
        ]
    ]
]);

$ch = curl_init($discord_webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $discord_payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

// Redirect back with success status
header("Location: index.html?status=success#contact");
exit;
