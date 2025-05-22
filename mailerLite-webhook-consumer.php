<?php
header('Content-Type: application/json');

// --- Subscriber class for "subscriber.created" event ---
class Subscriber {
    public $id;
    public $email;
    public $status;
    public $source;
    public $sent;
    public $opens_count;
    public $clicks_count;
    public $open_rate;
    public $click_rate;
    public $ip_address;
    public $subscribed_at;
    public $unsubscribed_at;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $forget_at;
    public $fields;
    public $opted_in_at;
    public $optin_ip;
    public $event;
    public $account_id;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->source = $data['source'] ?? null;
        $this->sent = $data['sent'] ?? null;
        $this->opens_count = $data['opens_count'] ?? null;
        $this->clicks_count = $data['clicks_count'] ?? null;
        $this->open_rate = $data['open_rate'] ?? null;
        $this->click_rate = $data['click_rate'] ?? null;
        $this->ip_address = $data['ip_address'] ?? null;
        $this->subscribed_at = $data['subscribed_at'] ?? null;
        $this->unsubscribed_at = $data['unsubscribed_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        $this->deleted_at = $data['deleted_at'] ?? null;
        $this->forget_at = $data['forget_at'] ?? null;
        $this->fields = $data['fields'] ?? [];
        $this->opted_in_at = $data['opted_in_at'] ?? null;
        $this->optin_ip = $data['optin_ip'] ?? null;
        $this->event = $data['event'] ?? null;
        $this->account_id = $data['account_id'] ?? null;
    }
}

// --- Utility: get all headers ---
function getAllHeadersLowercase() {
    $headers = [];
    foreach (getallheaders() as $name => $value) {
        $headers[strtolower($name)] = $value;
    }
    return $headers;
}

// --- Main logic ---
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

$eventType = $data['event'] ?? 'unknown';

// --- Event-specific processing ---
if ($eventType === 'subscriber.created') {
    // Create a Subscriber object for further processing
    $subscriber = new Subscriber($data);

    // Example: You can now use $subscriber in your business logic
    // For demo, we'll just log the object to a file
    $webhookDir = __DIR__ . '/webhook_data';
    if (!is_dir($webhookDir)) {
        mkdir($webhookDir, 0777, true);
    }
    $timestamp = date('Ymd_His');
    $subFile = "{$webhookDir}/subscriber_created_{$timestamp}_" . uniqid() . '.json';
    file_put_contents($subFile, json_encode($subscriber, JSON_PRETTY_PRINT));
}

// --- Persist all webhook data as before ---
$webhookDir = __DIR__ . '/webhook_data';
if (!is_dir($webhookDir)) {
    mkdir($webhookDir, 0777, true);
}
$timestamp = date('Ymd_His');
$filename = "{$webhookDir}/webhook_{$eventType}_{$timestamp}_" . uniqid() . '.json';

file_put_contents($filename, json_encode([
    'received_at' => date('c'),
    'headers'     => getAllHeadersLowercase(),
    'payload'     => $data
], JSON_PRETTY_PRINT));

// Log to a simple text file
$logFile = $webhookDir . '/webhook.log';
file_put_contents($logFile, "[".date('c')."] Received event: {$eventType}\n", FILE_APPEND);

// Respond to MailerLite
echo json_encode(['status' => 'ok']);
?>
