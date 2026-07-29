<?php
if (!defined('ALLOW')) { http_response_code(403); exit; }

function generate_access_code(): string {
    return sprintf('%08x-%04x-%04x-%04x-%012x',
        mt_rand(0, 0xffffffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffffffffffff)
    );
}

function get_ip_access_code(string $ip): string {
    $codesFile = FCPATH . 'data/ip_codes.json';
    $codesDir = dirname($codesFile);
    
    if (!is_dir($codesDir)) {
        @mkdir($codesDir, 0755, true);
    }
    
    $codes = [];
    if (is_file($codesFile)) {
        $json = file_get_contents($codesFile);
        $data = json_decode($json, true);
        if (is_array($data)) {
            $codes = $data;
        }
    }
    
    // Check if IP already has a code
    if (isset($codes[$ip])) {
        return $codes[$ip];
    }
    
    // Generate new unique code for this IP
    do {
        $newCode = generate_access_code();
    } while (in_array($newCode, $codes)); // Ensure uniqueness
    
    $codes[$ip] = $newCode;
    
    // Save updated codes
    $json = json_encode($codes, JSON_PRETTY_PRINT);
    @file_put_contents($codesFile, $json, LOCK_EX);
    
    return $newCode;
}

function is_valid_access_code(string $code, string $ip): bool {
    $codesFile = FCPATH . 'data/ip_codes.json';
    
    if (!is_file($codesFile)) {
        return false;
    }
    
    $json = file_get_contents($codesFile);
    $codes = json_decode($json, true);
    
    if (!is_array($codes)) {
        return false;
    }
    
    return isset($codes[$ip]) && $codes[$ip] === $code;
}

function get_all_ip_codes(): array {
    $codesFile = FCPATH . 'data/ip_codes.json';
    
    if (!is_file($codesFile)) {
        return [];
    }
    
    $json = file_get_contents($codesFile);
    $codes = json_decode($json, true);
    
    return is_array($codes) ? $codes : [];
}
