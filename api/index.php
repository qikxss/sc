<?php
define("ALLOW", true);
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

session_start();

require FCPATH . 'api\Tembuluk\System\helpers.php';
require FCPATH . 'Tembuluk/System/activity.php';
require FCPATH . 'Tembuluk/System/blocker.php';
require FCPATH . 'Tembuluk/Gobot/blocker.php';
require FCPATH . 'Tembuluk/System/access_codes.php';
require FCPATH . 'Tembuluk/Views/admin/layout.php';
require FCPATH . 'Tembuluk/System/core.php';

$core = new goApp();
$core->runApp();


