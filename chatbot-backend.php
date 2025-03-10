<?php
// Define the path to the .env file
$envFilePath = __DIR__ . '/api-keys/.env';

// Load environment variables from .env file
if (file_exists($envFilePath)) {
    $envVars = parse_ini_file($envFilePath);
    $API_KEY = $envVars['DEEPSEEK_API_KEY'] ?? '';
} else {
    die("Missing .env file. Please create 'api-keys/.env' with your API key.");
}

// Company Contact Information
$contactEmail = "aman.r65@gmail.com";
$contactPhone = "+918434478298";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userMessage = trim($_POST["message"]);

    if (empty($userMessage)) {
        echo "Please enter a message.";
        exit;
    }

    // DeepSeek API endpoint
    $api_url = "https://api.deepseek.com/v1/chat/completions";

    // Prepare request payload
    $postData = json_encode([
        "model" => "deepseek-chat",
        "messages" => [
            ["role" => "system", "content" => "You are Dukkan Online AI Customer Support. Help users with their shopping queries. If you don't know an answer, provide the contact email ($contactEmail) and phone number ($contactPhone) for customer support."],
            ["role" => "user", "content" => $userMessage]
        ],
        "temperature" => 0.7,
        "max_tokens" => 200
    ]);

    // Initialize cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $API_KEY",
        "Content-Type: application/json"
    ]);

    // Execute the request and get response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Handle API response
    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        $botReply = $responseData["choices"][0]["message"]["content"] ?? "I'm not sure how to respond.";

        // If the bot reply is generic, add contact details
        if (stripos($botReply, "I don't know") !== false || stripos($botReply, "not sure") !== false) {
            $botReply .= "\n\nFor further assistance, please contact us at:\n📧 Email: $contactEmail\n📞 Phone: $contactPhone";
        }

        echo nl2br($botReply);
    } else {
        echo "Error contacting AI service. For support, email us at $contactEmail or call $contactPhone.";
    }
}
?>