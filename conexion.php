<?php
$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS"; 
$url = "https://{$project_ref}.supabase.co/rest/v1/";

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "apikey: {$apiKey}",
        "Authorization: Bearer {$apiKey}"
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


if ($httpCode === 200 || $httpCode === 401) {
    echo "<h1>Conexión exitosa</h1>";
} else {
    echo "<h1>Error en la conexión</h1>";
}
?>

