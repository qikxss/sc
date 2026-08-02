<?php
defined("ALLOW") or exit('No direct script access allowed');

function render_admin_layout($pageTitle, $activeMenu, $content) {
    $menuItems = [
        'dashboard' => ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/admin-dashboard'],
        'email' => ['icon' => '📧', 'label' => 'Configuration', 'url' => '/admin-email'],
        'stats' => ['icon' => '📈', 'label' => 'Statistics', 'url' => '/admin-stats'],
        'blocker' => ['icon' => '🚫', 'label' => 'Blocker', 'url' => '/admin-blocker'],
    ];
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . htmlspecialchars($pageTitle) . ' - Phoniex Corp Admin</title>
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
            <nav class="sidebar-nav">';
    
    foreach ($menuItems as $key => $item) {
        $activeClass = ($activeMenu === $key) ? ' active' : '';
        $html .= '<a href="' . $item['url'] . '" class="nav-item' . $activeClass . '">
                    <span>' . $item['icon'] . '</span> ' . $item['label'] . '
                </a>';
    }
    
    $html .= '</nav>
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <h1 class="page-title">' . htmlspecialchars($pageTitle) . '</h1>
                <a href="#" onclick="logout()" class="logout-btn">Logout</a>
            </div>
            
            <div class="content">
                ' . $content . '
            </div>
        </div>
    </div>
    <script>
        async function logout() {
            await fetch("/admin-logout");
            location.href = "/admin";
        }
    </script>
</body>
</html>';
    
    return $html;
}
