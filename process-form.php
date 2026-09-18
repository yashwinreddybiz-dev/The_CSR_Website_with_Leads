<?php
// =========================================================================
// DEBUG MODE: Set to true during troubleshooting to see exact error messages on screen.
// Set to false once verified in production.
// =========================================================================
$debug_mode = false;

if ($debug_mode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Security: Anti-spam Honeypot Check
if (!empty($_POST['website'])) {
    die("Spam detected.");
}

// Sanitize and strip tags from inputs
function clean($value) {
    return strip_tags(htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8'));
}

$name          = clean($_POST['name'] ?? '');
$whatsapp_phone = clean($_POST['whatsapp_phone'] ?? '');
$locality      = clean($_POST['locality'] ?? '');
$package       = clean($_POST['package'] ?? '');

if (empty($name) || empty($whatsapp_phone) || empty($locality) || empty($package)) {
    die("Error: Full Name, WhatsApp/Phone, Locality, and Package are required.");
}

// =========================================================================
// CONFIGURATION - Replace with your actual Keys & IDs
// =========================================================================
$discord_webhook_url = "https://discord.com/api/webhooks/1550105246263550033/NzEZwmiwri3JqOpwX4WhNJpgXOY1pMGSXFW3fIcQz76_9h3lzN5uLUMDv918n3QCrZLT";

$airtable_pat        = "patDZITDnsh51Oq3b.68b288c5c362b01f0fb1cf8fcdda3fe08ae0598c4c0b29b61d6603a5a673c4de"; // e.g., patXXXXXXXXXXXXXX
$airtable_base_id    = "app6RQ1NDUMQojXJf";                   // Your Base ID
$airtable_table_id   = "tblA9EkOaLup57xm9";                   // Your Table ID (Recommended) or Table Name

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
                    ["name" => "WhatsApp/Phone", "value" => $whatsapp_phone, "inline" => true],
                    ["name" => "Locality", "value" => $locality, "inline" => true],
                    ["name" => "Package Interest", "value" => $package ?: 'No package selected', "inline" => false]
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
    curl_setopt($ch_discord, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch_discord, CURLOPT_CONNECTTIMEOUT, 5);
    curl_exec($ch_discord);
    curl_close($ch_discord);
}

// =========================================================================
// 2. Save Lead Record to Airtable API with Error Diagnostics
// =========================================================================
if (!empty($airtable_pat) && $airtable_pat !== "YOUR_AIRTABLE_PERSONAL_ACCESS_TOKEN") {
    $airtable_endpoint = "https://api.airtable.com/v0/" . $airtable_base_id . "/" . rawurlencode($airtable_table_id);

    $airtable_payload = json_encode([
        "records" => [
            [
                "fields" => [
                    "Name"            => $name,
                    "WhatsApp/Phone"  => $whatsapp_phone,
                    "Locality"        => $locality,
                    "Package"         => $package,
                    "Status"          => "New"
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
    curl_setopt($ch_airtable, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch_airtable, CURLOPT_CONNECTTIMEOUT, 5);

    $response  = curl_exec($ch_airtable);
    $http_code = curl_getinfo($ch_airtable, CURLINFO_HTTP_CODE);
    $curl_err  = curl_error($ch_airtable);
    curl_close($ch_airtable);

    // If Airtable rejected the request, display the exact error message.
    if ($http_code < 200 || $http_code >= 300) {
        if ($debug_mode) {
            echo "<h2>Airtable API Connection Failed (HTTP " . $http_code . ")</h2>";
            echo "<p><b>Response from Airtable:</b></p>";
            echo "<pre style='background:#f4f4f4; padding:1rem; border:1px solid #ccc;'>" . htmlspecialchars($response) . "</pre>";
            if ($curl_err) {
                echo "<p><b>cURL Error:</b> " . htmlspecialchars($curl_err) . "</p>";
            }
            echo "<hr><p><b>Common Causes & Fixes:</b></p>";
            echo "<ul>";
            echo "<li><b>HTTP 401 (UNAUTHORIZED):</b> Invalid or expired Personal Access Token (PAT). Check token permissions in Airtable.</li>";
            echo "<li><b>HTTP 404 (NOT FOUND):</b> Base ID or Table ID is wrong.</li>";
            echo "<li><b>HTTP 422 (UNKNOWN_FIELD_NAME):</b> Column names in Airtable don't match exactly ('Name', 'WhatsApp/Phone', 'Locality', 'Package', 'Status').</li>";
            echo "<li><b>HTTP 422 (INVALID_MULTIPLE_CHOICE_OPTIONS):</b> 'Status' column is a Single Select field and 'New' option doesn't exist yet in Airtable.</li>";
            echo "</ul>";
            exit;
        }
    }
}

// Success response
if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}

// Success redirect
header("Location: contact.html?status=success#contact");
exit;