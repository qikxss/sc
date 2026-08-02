<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container { 
            perspective: 1000px;
        }
        .login-form { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 400px;
            transform: rotateY(0deg);
            transition: all 0.3s ease;
        }
        .login-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }
        h1 { 
            margin-bottom: 40px; 
            text-align: center; 
            color: #2d3748;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-group { 
            margin-bottom: 25px; 
            position: relative;
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }
        input { 
            width: 100%; 
            padding: 15px 20px; 
            border: 2px solid #e2e8f0; 
            border-radius: 0; 
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        button { 
            width: 100%; 
            padding: 15px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            border: none; 
            border-radius: 0; 
            font-size: 16px; 
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        button:active {
            transform: translateY(0);
        }
        .error { 
            color: #e53e3e; 
            margin-top: 15px; 
            padding: 10px;
            background: rgba(229, 62, 62, 0.1);
            border-radius: 0;
            font-size: 14px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form class="login-form" id="loginForm">
            <h1>Admin Panel</h1>
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" required>
            </div>
            <button type="submit">Login</button>
            <div class="error" id="error" style="display:none;"></div>
        </form>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const error = document.getElementById("error");
            
            try {
                // Get access code from URL path
                const pathParts = window.location.pathname.split('/').filter(p => p);
                const accessCode = pathParts.length > 0 ? pathParts[0] : '';
                
                // Use access code in fetch URL
                const loginUrl = accessCode ? `/${accessCode}/admin-login` : "/admin-login";
                const res = await fetch(loginUrl, {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({username, password})
                });
                const data = await res.json();
                
                if (data.success) {
                    // Redirect to admin dashboard with access code
                    const dashboardUrl = accessCode ? `/${accessCode}/admin` : "/admin";
                    location.href = dashboardUrl;
                } else {
                    error.textContent = "Invalid credentials";
                    error.style.display = "block";
                }
            } catch (err) {
                error.textContent = "Login failed: " + err.message;
                error.style.display = "block";
            }
        });
    </script>
</body>
</html>
