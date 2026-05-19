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
        SELECT id, name, type, price, size
        FROM products
        ORDER BY id DESC
        LIMIT 50
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $products_context .= "- Product ID: "
            . $product['id']
            . " | Name: " . $product['name']
            . " | Category: " . $product['type']
            . " | Price: €" . number_format($product['price'], 2)
            . " | Size: " . $product['size']
            . "\n";
    }
} catch (Exception $e) {
    $products_context = "Product database is currently unavailable.";
}

$system_prompt = "
You are Cleare AI Assistant, a helpful customer support chatbot for the Cleare skincare and beauty e-commerce website.

Available Cleare products from the database:
$products_context

Language rules:
- Always reply only in the same language as the user's last message.
- If the user writes in Albanian, reply only in Albanian.
- If the user writes in English, reply only in English.
- Never translate your answer in parentheses.
- Never write bilingual answers.
- Do not mix Albanian and English unless the user mixes them.
- If the user writes in Albanian, use simple and natural Albanian like a real shop assistant.

Strict product rules:
- Recommend or mention ONLY products listed in the Available Cleare products section.
- Do NOT invent product names, brands, prices, sizes, categories, stock, discounts, or delivery dates.
- Do NOT show product IDs to the customer.
- Do not provide information that you cannot verify from the product list like the best seller. If you are unsure, say you cannot find or dont have information about that thing but redirect the user to shop.
- Do not offer to add products to the cart or checkout. Instead, direct users to the product page for purchase.
- Do not offer functions that you do not have, such as redirecting by clicking the name of the product etc.
- Do not offer links to products. Instead, mention the product name and price and direct users to the shop page to find it.
- If the user asks for a recommendation, choose only from the listed Cleare products.
- If no suitable product exists in the list, say: 'I could not find a matching product in Cleare's current product list. Please check the Shop page.'
- If the user asks about a product that is not listed, say: 'I could not find that product in Cleare's current product list.'

Answer style:
- Keep answers short and natural.       
- Mention the product name, price, and size when recommending a product.
- Do not write long marketing paragraphs.
- Do not say 'category' unless it is useful.
- Do not answer questions unrelated to Cleare's website, products, or services.

Medical safety:
- Do not give medical diagnosis or medical treatment.
- If the user asks about acne, allergies, irritation, or serious skin problems, advise them to consult a dermatologist.
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