<?php
// Security: Anti-spam Honeypot Check
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

// =========================================================================
// CONFIGURATION - Replace with your actual Keys & IDs
// =========================================================================
$discord_webhook_url = "https://discord.com/api/webhooks/1550105246263550033/NzEZwmiwri3JqOpwX4WhNJpgXOY1pMGSXFW3fIcQz76_9h3lzN5uLUMDv918n3QCrZLT";

$airtable_pat        = "patQjB3aNa9ikhYFp.984c0eae57c8f8895c8d8c03189c3110a8b8fa295743cafb0e0504c17c6cf999"; // e.g., patXXXXXXXXXXXXXX
$airtable_base_id    = "app6RQ1NDUMQojXJf";               // e.g., appXXXXXXXXXXXXXX
$airtable_table_name = "tblA9EkOaLup57xm9";                   // Exact name of table in Airtable

// =========================================================================
// 1. Send Instant Lead Card to Discord Webhook
// =========================================================================
if (!empty($discord_webhook_url) && $discord_webhook_url !== "YOUR_DISCORD_WEBHOOK_URL_HERE") {
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

    $ch_discord = curl_init($discord_webhook_url);
    curl_setopt($ch_discord, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch_discord, CURLOPT_POST, 1);
    curl_setopt($ch_discord, CURLOPT_POSTFIELDS, $discord_payload);
    curl_setopt($ch_discord, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch_discord);
    curl_close($ch_discord);
}

// =========================================================================
// 2. Save Lead Record to Airtable API
// =========================================================================
if (!empty($airtable_pat) && $airtable_pat !== "YOUR_AIRTABLE_PERSONAL_ACCESS_TOKEN") {
    $airtable_endpoint = "https://api.airtable.com/v0/" . $airtable_base_id . "/" . rawurlencode($airtable_table_name);

    $airtable_payload = json_encode([
        "records" => [
            [
                "fields" => [
                    "Name"    => $name,
                    "Email"   => $email,
                    "Phone"   => $phone,
                    "Message" => $message,
                    "Status"  => "New"
                ]
            ]
        ]
    ]);

    $ch_airtable = curl_init($airtable_endpoint);
    curl_setopt($ch_airtable, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $airtable_pat,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch_airtable, CURLOPT_POST, 1);
    curl_setopt($ch_airtable, CURLOPT_POSTFIELDS, $airtable_payload);
    curl_setopt($ch_airtable, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch_airtable);
    curl_close($ch_airtable);
}

// Redirect back with success status
header("Location: index.html?status=success#contact");
exit;
