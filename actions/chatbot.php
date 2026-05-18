<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'reply' => 'Invalid request method.'
    ]);
    exit;
}

$user_message = trim($_POST['message'] ?? '');
$history_json = $_POST['history'] ?? '[]';
$history = json_decode($history_json, true);

if (!is_array($history)) {
    $history = [];
}

if ($user_message === '') {
    echo json_encode([
        'success' => false,
        'reply' => 'Please write a message.'
    ]);
    exit;
}

$products_context = "";

try {
    $stmt = $pdo->query("
        SELECT name, type, price, size
        FROM products
        ORDER BY id DESC
        LIMIT 20
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $products_context .= "- "
            . $product['name']
            . " | Category: " . $product['type']
            . " | Price: " . $product['price'] . " L"
            . " | Size: " . $product['size']
            . "\n";
    }
} catch (Exception $e) {
    $products_context = "Product database is currently unavailable.";
}

$system_prompt = "
You are Cleare AI Assistant, a helpful customer support chatbot for an online skincare and beauty e-commerce website called Cleare.

Rules:
- Help users with skincare products, product categories, cart, checkout, coupons, account, login, register, orders, and contact information.
- Keep answers short, clear, polite, and practical.
- Do not answer questions unrelated to the website, products, or services.
- Do not invent exact prices, stock, order status, delivery dates, or discounts.
- Do not give medical diagnosis or medical treatment.
- If the user asks for skin diagnosis, acne treatment, allergies, or serious skin problems, advise them to consult a dermatologist.
- If you do not know something, tell the user to check the website page or contact support.
";

$messages = [
    [
        'role' => 'system',
        'content' => $system_prompt
    ]
];

foreach ($history as $message) {
    if (
        isset($message['role'], $message['content']) &&
        in_array($message['role'], ['user', 'assistant'], true)
    ) {
        $messages[] = [
            'role' => $message['role'],
            'content' => substr($message['content'], 0, 1000)
        ];
    }
}

$data = [
    'model' => 'llama-3.1-8b-instant',
    'messages' => $messages,
    'temperature' => 0.6,
    'max_completion_tokens' => 300
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_TIMEOUT => 30,

    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        'success' => false,
        'reply' => 'AI service error. Please try again.'
    ]);
    curl_close($ch);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code < 200 || $http_code >= 300) {
    echo json_encode([
        'success' => false,
        'reply' => 'Groq request failed. Please check the API key or model.'
    ]);
    exit;
}

$reply = $result['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

echo json_encode([
    'success' => true,
    'reply' => $reply
]);