<?php

class BotGobot {
    private string $apiKey;
    private string $domain;
    private string $redirection;
    private static ?array $config = null;

    private static function loadConfig(): array {
        if (self::$config === null) {
            $configPath = FCPATH . 'admin_config.php';
            if (!file_exists($configPath)) {
                throw new RuntimeException('admin_config.php not found.');
            }
            self::$config = include $configPath;
        }
        return self::$config;
    }

    private static function getConfig(string $key, $default = null) {
        $config = self::loadConfig();
        return $config[$key] ?? $default;
    }

    public static function isProtectionEnabled(): bool {
        return (bool) self::getConfig('botProtection', false);
    }

    public function __construct() {
        $this->apiKey      = (string) self::getConfig('botGobotApiKey', '');
        $this->domain      = rtrim((string) self::getConfig('gobotDomain', ''), '/');
        $this->redirection = (string) self::getConfig('botRedirection', '');
    }

    private function getClientIP(): string {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? '';
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $remote  = $_SERVER['REMOTE_ADDR'] ?? '';

        return filter_var($client, FILTER_VALIDATE_IP) ? $client :
               (filter_var($forward, FILTER_VALIDATE_IP) ? $forward : ($remote ?: '0.0.0.0'));
    }

    public function showNotFoundPage(): void {
        header("HTTP/1.0 404 Not Found");
        include 'down.php';
        exit();
    }

    private function httpPost(string $url, array $postFields): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postFields),
        ]);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['response' => $res, 'http_code' => $code];
    }

    public function gobotBlocker(string $ip = null): array {
        if (empty($this->apiKey) || empty($this->domain)) {
            return ['success' => false, 'message' => 'Missing API key or domain in admin_config.php'];
        }

        $ip   = $ip ?? $this->getClientIP();
        $ua   = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $reff = $_SERVER['HTTP_REFERER'] ?? '';

        $result = $this->httpPost("https://gobot.cx/api/v1/blocker", [
            'ip'      => $ip,
            'apikey'  => $this->apiKey,
            'ua'      => $ua,
            'url'     => $this->domain,
            'reff'    => $reff
        ]);

        $res  = $result['response'];
        $code = $result['http_code'];

        if (!$res) return ['success' => false, 'message' => 'Server not responding'];
        $data = json_decode($res, true);
        if (!is_array($data)) return ['success' => false, 'message' => 'Invalid API response'];
        if ($code >= 400) return ['success' => false, 'message' => "HTTP Error $code"];

        $type   = strtolower($data['type'] ?? 'unknown');
        $reason = strtolower($data['reason'] ?? '');

        if (strpos($reason, 'no active package') !== false) return ['success' => false, 'message' => 'No Package'];
        if (strpos($reason, 'expired') !== false) return ['success' => false, 'message' => 'Package Expired'];
        if (strpos($reason, 'quota') !== false) return ['success' => false, 'message' => 'Quota Limit'];

        if ($type === 'bot') return ['success' => false, 'type' => 'bot', 'reason' => $reason ?: 'Detected as bot'];
        if ($type === 'human') return ['success' => true, 'type' => 'human', 'reason' => $reason];

        return ['success' => false, 'message' => 'Unknown response'];
    }

    public function handleBotDetected(): void {
        if (!empty($this->redirection)) {
        }
        $this->showNotFoundPage();
    }
} 