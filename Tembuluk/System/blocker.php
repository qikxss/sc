<?php
if (!defined('ALLOW')) { http_response_code(403); exit; }

function get_block_rules(): array {
    $blockFile = FCPATH . 'data/block_rules.json';
    
    if (!is_file($blockFile)) {
        return [
            'ips' => [],
            'isps' => [],
            'user_agents' => [],
            'bot_names' => []
        ];
    }
    
    $json = file_get_contents($blockFile);
    $data = json_decode($json, true);
    
    if (!is_array($data)) {
        return [
            'ips' => [],
            'isps' => [],
            'user_agents' => [],
            'bot_names' => []
        ];
    }
    
    return $data;
}

function save_block_rules(array $rules): bool {
    $blockFile = FCPATH . 'data/block_rules.json';
    $blockDir = dirname($blockFile);
    
    if (!is_dir($blockDir)) {
        @mkdir($blockDir, 0755, true);
    }
    
    $json = json_encode($rules, JSON_PRETTY_PRINT);
    return file_put_contents($blockFile, $json) !== false;
}

function check_blacklist_local(string $ip): array {
    // Hardcoded blacklist for testing - 85.208.98.29
    $hardcodedBlacklist = [
        '85.208.98.29' => [
            'blacklisted' => true,
            'details' => 'SemrushBot Crawler - Website Extractor on 09 September 2025',
            'source' => 'hardcoded',
            'timestamp' => time()
        ]
    ];
    
    // Check hardcoded blacklist first and ensure it's cached
    if (isset($hardcodedBlacklist[$ip])) {
        $entry = $hardcodedBlacklist[$ip];
        
        // Save hardcoded entry to cache if not already there
        $blacklistFile = FCPATH . 'data/blacklist_cache.json';
        $data = [];
        if (is_file($blacklistFile)) {
            $json = file_get_contents($blacklistFile);
            $data = json_decode($json, true) ?: [];
        }
        
        // Only save if not already in cache or if it's old
        if (!isset($data[$ip]) || !isset($data[$ip]['source']) || $data[$ip]['source'] !== 'hardcoded') {
            save_blacklist_cache($ip, $entry);
            
            // Also log the hardcoded detection
            if ($entry['blacklisted']) {
                log_blacklist_detection($ip, $entry);
            }
        }
        
        return [
            'found' => $entry['blacklisted'],
            'cached' => true,
            'details' => $entry['details']
        ];
    }
    
    $blacklistFile = FCPATH . 'data/blacklist_cache.json';
    
    if (!is_file($blacklistFile)) {
        return ['found' => false, 'cached' => false];
    }
    
    $json = file_get_contents($blacklistFile);
    $data = json_decode($json, true);
    
    if (!is_array($data)) {
        return ['found' => false, 'cached' => false];
    }
    
    // Check if IP exists in local cache
    if (isset($data[$ip])) {
        $entry = $data[$ip];
        // Check if cache is not expired (24 hours)
        if (isset($entry['timestamp']) && (time() - $entry['timestamp']) < 86400) {
            return [
                'found' => $entry['blacklisted'],
                'cached' => true,
                'details' => $entry['details'] ?? ''
            ];
        }
    }
    
    return ['found' => false, 'cached' => false];
}

function check_blacklist_remote(string $ip): array {
    $url = "https://blacklist.myip.ms/{$ip}";
    
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $ctx);
    
    if ($response === false) {
        return ['found' => false, 'error' => 'API request failed'];
    }
    
    // Parse the HTML response
    if (strpos($response, 'Listed in Myip.ms Blacklist') !== false) {
        // Extract details from the response
        preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $response, $matches);
        $details = isset($matches[1]) ? strip_tags($matches[1]) : 'Listed in blacklist';
        
        // Extract more specific information if available
        if (preg_match('/\((.*?)\)/', $response, $detailMatches)) {
            $details = trim($detailMatches[1]);
        }
        
        return [
            'found' => true,
            'blacklisted' => true,
            'details' => $details,
            'source' => 'myip.ms'
        ];
    } elseif (strpos($response, 'Not Listed in Blacklist') !== false) {
        return [
            'found' => true,
            'blacklisted' => false,
            'details' => 'Not listed in blacklist',
            'source' => 'myip.ms'
        ];
    }
    
    return ['found' => false, 'error' => 'Unable to parse response'];
}

function save_blacklist_cache(string $ip, array $result): void {
    $blacklistFile = FCPATH . 'data/blacklist_cache.json';
    $blacklistDir = dirname($blacklistFile);
    
    if (!is_dir($blacklistDir)) {
        @mkdir($blacklistDir, 0755, true);
    }
    
    $data = [];
    if (is_file($blacklistFile)) {
        $json = file_get_contents($blacklistFile);
        $data = json_decode($json, true) ?: [];
    }
    
    $data[$ip] = [
        'blacklisted' => $result['blacklisted'] ?? false,
        'details' => $result['details'] ?? '',
        'source' => $result['source'] ?? 'unknown',
        'timestamp' => time(),
        'checked_at' => date('Y-m-d H:i:s')
    ];
    
    // Keep only last 1000 entries to prevent file from growing too large
    if (count($data) > 1000) {
        $data = array_slice($data, -1000, null, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT);
    @file_put_contents($blacklistFile, $json);
}

function log_blacklist_detection(string $ip, array $result): void {
    $logFile = FCPATH . 'data/blacklist_detections.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    
    $logEntry = [
        'timestamp' => $timestamp,
        'ip' => $ip,
        'blacklisted' => $result['blacklisted'],
        'details' => $result['details'] ?? '',
        'source' => $result['source'] ?? 'unknown',
        'user_agent' => $userAgent,
        'request_uri' => $requestUri,
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown'
    ];
    
    $logLine = json_encode($logEntry) . "\n";
    @file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

function get_blacklist_stats(): array {
    $cacheFile = FCPATH . 'data/blacklist_cache.json';
    $logFile = FCPATH . 'data/blacklist_detections.log';
    
    $stats = [
        'total_cached' => 0,
        'blacklisted_count' => 0,
        'clean_count' => 0,
        'recent_detections' => [],
        'cache_size' => 0
    ];
    
    // Check cache file
    if (is_file($cacheFile)) {
        $stats['cache_size'] = filesize($cacheFile);
        $json = file_get_contents($cacheFile);
        $data = json_decode($json, true);
        
        if (is_array($data)) {
            $stats['total_cached'] = count($data);
            foreach ($data as $entry) {
                if ($entry['blacklisted'] ?? false) {
                    $stats['blacklisted_count']++;
                } else {
                    $stats['clean_count']++;
                }
            }
        }
    }
    
    // Check recent detections (last 10)
    if (is_file($logFile)) {
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $recentLines = array_slice($lines, -10);
        
        foreach ($recentLines as $line) {
            $entry = json_decode($line, true);
            if ($entry && ($entry['blacklisted'] ?? false)) {
                $stats['recent_detections'][] = $entry;
            }
        }
    }
    
    return $stats;
}

function blackbox(string $ip): bool {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://blackbox.ipinfo.app/lookup/" . $ip);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($resp === false || $httpCode !== 200) {
        return false; // API failed, assume not proxy
    }
    
    $result = trim($resp);
    return ($result === "Y");
}

function check_proxy_local(string $ip): array {
    $proxyFile = FCPATH . 'data/proxy_cache.json';
    
    if (!is_file($proxyFile)) {
        return ['found' => false, 'cached' => false];
    }
    
    $json = file_get_contents($proxyFile);
    $data = json_decode($json, true);
    
    if (!is_array($data)) {
        return ['found' => false, 'cached' => false];
    }
    
    // Check if IP exists in local cache
    if (isset($data[$ip])) {
        $entry = $data[$ip];
        // Check if cache is not expired (24 hours)
        if (isset($entry['timestamp']) && (time() - $entry['timestamp']) < 86400) {
            return [
                'found' => $entry['is_proxy'],
                'cached' => true,
                'details' => $entry['details'] ?? ''
            ];
        }
    }
    
    return ['found' => false, 'cached' => false];
}

function save_proxy_cache(string $ip, array $result): void {
    $proxyFile = FCPATH . 'data/proxy_cache.json';
    $proxyDir = dirname($proxyFile);
    
    if (!is_dir($proxyDir)) {
        @mkdir($proxyDir, 0755, true);
    }
    
    $data = [];
    if (is_file($proxyFile)) {
        $json = file_get_contents($proxyFile);
        $data = json_decode($json, true) ?: [];
    }
    
    $data[$ip] = [
        'is_proxy' => $result['is_proxy'] ?? false,
        'details' => $result['details'] ?? '',
        'source' => $result['source'] ?? 'blackbox',
        'timestamp' => time(),
        'checked_at' => date('Y-m-d H:i:s')
    ];
    
    // Keep only last 1000 entries to prevent file from growing too large
    if (count($data) > 1000) {
        $data = array_slice($data, -1000, null, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT);
    @file_put_contents($proxyFile, $json);
}

function check_ip_proxy(string $ip): array {
    // First check local cache
    $localResult = check_proxy_local($ip);
    
    if ($localResult['cached']) {
        return [
            'is_proxy' => $localResult['found'],
            'details' => $localResult['details'] ?? '',
            'source' => 'cache'
        ];
    }
    
    // If not in cache or expired, check remote API
    $isProxy = blackbox($ip);
    
    $result = [
        'is_proxy' => $isProxy,
        'details' => $isProxy ? 'Proxy/VPN detected by blackbox.ipinfo.app' : 'Not a proxy/VPN',
        'source' => 'blackbox'
    ];
    
    // Save to cache
    save_proxy_cache($ip, $result);
    
    // Log proxy detection if it's a proxy
    if ($isProxy) {
        log_proxy_detection($ip, $result);
    }
    
    return $result;
}

function log_proxy_detection(string $ip, array $result): void {
    $logFile = FCPATH . 'data/proxy_detections.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    
    $logEntry = [
        'timestamp' => $timestamp,
        'ip' => $ip,
        'is_proxy' => $result['is_proxy'],
        'details' => $result['details'] ?? '',
        'source' => $result['source'] ?? 'unknown',
        'user_agent' => $userAgent,
        'request_uri' => $requestUri,
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown'
    ];
    
    $logLine = json_encode($logEntry) . "\n";
    @file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

function check_ip_blacklist(string $ip): array {
    // First check local cache
    $localResult = check_blacklist_local($ip);
    
    if ($localResult['cached']) {
        return [
            'blacklisted' => $localResult['found'],
            'details' => $localResult['details'] ?? '',
            'source' => 'cache'
        ];
    }
    
    // If not in cache or expired, check remote API
    $remoteResult = check_blacklist_remote($ip);
    
    if ($remoteResult['found']) {
        // Always save to local cache when we get a result from remote API
        save_blacklist_cache($ip, $remoteResult);
        
        // Log the blacklist detection
        if ($remoteResult['blacklisted']) {
            log_blacklist_detection($ip, $remoteResult);
        }
        
        return [
            'blacklisted' => $remoteResult['blacklisted'],
            'details' => $remoteResult['details'],
            'source' => $remoteResult['source']
        ];
    }
    
    // If remote check failed, save negative result to cache to avoid repeated API calls
    $negativeResult = [
        'blacklisted' => false,
        'details' => 'API check failed - not blacklisted by default',
        'source' => 'api_error'
    ];
    save_blacklist_cache($ip, $negativeResult);
    
    return [
        'blacklisted' => false,
        'details' => 'Unable to verify blacklist status',
        'source' => 'error'
    ];
}

function is_blocked(): array {
    $rules = get_block_rules();
    $ip = get_client_ip();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $geoData = get_geo_info($ip);
    $isp = $geoData['isp'] ?? '';
    
    // Load config to check if proxy detection is enabled
    $config = include(FCPATH . 'admin_config.php');
    $proxyDetectionEnabled = $config['proxy_detection_enabled'] ?? true;
    
    // Check proxy/VPN first if enabled
    if ($proxyDetectionEnabled) {
        $proxyResult = check_ip_proxy($ip);
        if ($proxyResult['is_proxy']) {
            return [
                'blocked' => true,
                'reason' => 'Proxy/VPN blocked',
                'value' => $ip,
                'details' => $proxyResult['details'],
                'source' => $proxyResult['source']
            ];
        }
    }
    
    // Check blacklist second
    //$blacklistResult = check_ip_blacklist($ip);
    //if ($blacklistResult['blacklisted']) {
        //return [
            //'blocked' => true, 
            //'reason' => 'IP blacklisted', 
            //'value' => $ip,
            //'details' => $blacklistResult['details'],
            //'source' => $blacklistResult['source']
        //];
    //}
    
    // Check IP blocks
    foreach ($rules['ips'] as $blockedIp) {
        if (trim($blockedIp) === $ip) {
            return ['blocked' => true, 'reason' => 'IP blocked', 'value' => $ip];
        }
    }
    
    // Check ISP blocks
    foreach ($rules['isps'] as $blockedIsp) {
        if (!empty($blockedIsp) && stripos($isp, trim($blockedIsp)) !== false) {
            return ['blocked' => true, 'reason' => 'ISP blocked', 'value' => $isp];
        }
    }
    
    // Check User Agent blocks
    foreach ($rules['user_agents'] as $blockedUA) {
        if (!empty($blockedUA) && stripos($userAgent, trim($blockedUA)) !== false) {
            return ['blocked' => true, 'reason' => 'User Agent blocked', 'value' => $blockedUA];
        }
    }
    
    // Check bot name blocks
    foreach ($rules['bot_names'] as $botName) {
        if (!empty($botName) && stripos($userAgent, trim($botName)) !== false) {
            return ['blocked' => true, 'reason' => 'Bot blocked', 'value' => $botName];
        }
    }
    
    return ['blocked' => false];
}

function render_blocked_page(array $blockInfo): void {
    // Standard blocked page for all types of blocks (including blacklisted IPs)
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, sans-serif; 
            background: #f8f9fa; 
            margin: 0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            color: #495057;
        }
        .blocked-container { 
            background: white; 
            padding: 60px 40px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            text-align: center; 
            max-width: 500px;
        }
        .blocked-icon { 
            font-size: 64px; 
            color: #dc3545; 
            margin-bottom: 20px; 
        }
        h1 { 
            color: #dc3545; 
            margin-bottom: 20px; 
            font-size: 28px;
        }
        p { 
            color: #6c757d; 
            margin-bottom: 10px; 
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <div class="blocked-icon">🚫</div>
        <h1>Access Denied</h1>
        <p>Your access to this site has been blocked.</p>
        <p>If you believe this is an error, please contact the administrator.</p>
    </div>
</body>
</html>';
    
    http_response_code(403);
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

function is_ip_whitelisted(string $ip): bool {
    $whitelistFile = FCPATH . 'data/whitelist.txt';
    
    if (!is_file($whitelistFile)) {
        return false;
    }
    
    $lines = file($whitelistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return false;
    }
    
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === $ip) {
            return true;
        }
    }
    
    return false;
}

function add_ip_to_whitelist(string $ip): bool {
    $whitelistFile = FCPATH . 'data/whitelist.txt';
    $whitelistDir = dirname($whitelistFile);
    
    // Create directory if it doesn't exist
    if (!is_dir($whitelistDir)) {
        @mkdir($whitelistDir, 0755, true);
    }
    
    // Check if IP is already in whitelist
    if (is_ip_whitelisted($ip)) {
        return true;
    }
    
    // Add IP to whitelist file
    $line = trim($ip) . "\n";
    return @file_put_contents($whitelistFile, $line, FILE_APPEND | LOCK_EX) !== false;
}
