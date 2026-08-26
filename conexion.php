<?php
$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

function supabase_get($endpoint, $baseUrl, $apiKey) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL            => $baseUrl . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "apikey: {$apiKey}",
            "Authorization: Bearer {$apiKey}",
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo '<p style="color:red;">Error de cURL: ' . curl_error($ch) . '</p>';
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        return json_decode($response, true);
    }

    echo "<p style='color:red;'><strong>Error HTTP {$httpCode} desde Supabase:</strong></p>";
    echo "<pre style='background:#f4f4f4; padding:10px;'>" . htmlspecialchars($response) . "</pre>";
    return null;
}
?>