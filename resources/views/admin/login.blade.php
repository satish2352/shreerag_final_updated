<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shreerag Engineering And Auto Pvt Ltd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('website/assets/img/logo/Layer 2.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Inter', sans-serif; overflow: hidden; }

        body {
            background: url("{{ asset('img/logo/bg22.png') }}") center / cover no-repeat fixed;
            background-color: #05111f;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: rgba(3, 10, 26, 0.60); z-index: 0;
        }

        /* ── Logo ── */
        .page-logo {
            position: fixed; top: 20px; left: 28px; z-index: 10;
            opacity: 0; animation: fadeUp 0.6s ease 0.1s forwards;
        }
        .page-logo img { height: 54px; width: auto; filter: drop-shadow(0 2px 10px rgba(0,0,0,0.5)); }

        /* ── Main area ── */
        .page-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px 44px 10px 36px;
            position: relative; z-index: 1;
            gap: 24px;
            overflow: hidden;
        }

        /* ══ LEFT PANEL ══ */
        .left-panel {
            flex: 1; min-width: 0;
            display: flex; flex-direction: column;
            align-items: flex-start;
            gap: 0;
            padding-left: 8px;
        }

        /* Teal accent bar */
        .accent-bar {
            width: 48px; height: 3px;
            background: #0bb8b2; border-radius: 2px;
            margin-bottom: 10px;
            opacity: 0; animation: fadeUp 0.5s ease 0.2s forwards;
        }

        /* Main heading */
        .main-heading {
            font-size: 32px; font-weight: 900; color: #fff;
            line-height: 1.1; margin-bottom: 6px; white-space: nowrap;
            text-align: left;
            opacity: 0; animation: fadeUp 0.7s ease 0.35s forwards;
        }
        .main-heading span { color: #0bb8b2; }

        .main-sub {
            font-size: 13px; color: rgba(255,255,255,0.55);
            margin-bottom: 4px; text-align: left;
            opacity: 0; animation: fadeUp 0.6s ease 0.55s forwards;
        }

        /* ─ Trolley section ─ */
        .trolley-section {
            position: relative;
            width: 630px;      /* 165px left-pad + 290px trolley + 175px right for s3 */
            flex-shrink: 0;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px 0 10px 165px;
            margin-top: 14px;  /* shift trolley slightly down */
        }

        .trolley-img {
            width: 290px; height: auto;
            display: block; position: relative; z-index: 2;
            filter: drop-shadow(0 12px 32px rgba(11,184,178,0.3))
                    drop-shadow(0 4px 12px rgba(0,0,0,0.6));
            opacity: 0; animation: fadeUp 0.9s ease 0.4s forwards;
        }

        /* ─ Spec callout boxes ─ */
        .spec-box {
            position: absolute;
            display: flex; align-items: center; gap: 10px;
            background: rgba(5, 18, 40, 0.82);
            border: 1px solid rgba(11,184,178,0.5);
            border-radius: 12px;
            padding: 10px 14px;
            z-index: 3;
            opacity: 0;
            white-space: nowrap;
        }

        .spec-icon {
            width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
            background: rgba(11,184,178,0.2);
            border: 1px solid rgba(11,184,178,0.5);
            display: flex; align-items: center; justify-content: center;
            color: #0bb8b2; font-size: 15px;
        }

        .spec-text { font-size: 12px; font-weight: 700; color: #fff; line-height: 1.3; }

        /* Connector: horizontal line + dot */
        .spec-connector {
            position: absolute;
            height: 1.5px;
            background: linear-gradient(90deg, rgba(11,184,178,0.25), #0bb8b2);
            top: 50%; transform: translateY(-50%);
            z-index: 2;
        }
        .spec-dot {
            position: absolute;
            width: 9px; height: 9px; border-radius: 50%;
            background: #0bb8b2;
            box-shadow: 0 0 8px rgba(11,184,178,1), 0 0 20px rgba(11,184,178,0.5);
            top: 50%; transform: translateY(-50%);
            z-index: 3;
        }

        /* Trolley left-edge = 165px, trolley width = 290px, right-edge = 455px */

        /* Durable Structure — upper left */
        .s1 {
            top: 18%; right: calc(100% - 165px + 8px);
            animation: slideFromLeft 0.6s cubic-bezier(0.22,1,0.36,1) 0.8s forwards,
                       specGlow 2.2s ease-in-out 1.4s infinite;
        }
        .s1-conn {
            top: calc(18% + 28px);
            left: calc(165px - 44px); width: 44px;
            animation: growLine 0.4s ease 1.4s forwards; opacity: 0;
        }
        .s1-dot { top: calc(18% + 28px); left: calc(165px - 4px);
            animation: popDot 0.3s ease 1.8s forwards, dotGlow 1.6s ease-in-out 2.1s infinite; opacity: 0; }

        /* Custom Designs — lower left */
        .s2 {
            top: 55%; right: calc(100% - 165px + 8px);
            animation: slideFromLeft 0.6s cubic-bezier(0.22,1,0.36,1) 1.3s forwards,
                       specGlow 2.2s ease-in-out 1.9s infinite;
        }
        .s2-conn {
            top: calc(55% + 28px);
            left: calc(165px - 44px); width: 44px;
            animation: growLine 0.4s ease 1.9s forwards; opacity: 0;
        }
        .s2-dot { top: calc(55% + 28px); left: calc(165px - 4px);
            animation: popDot 0.3s ease 2.3s forwards, dotGlow 1.6s ease-in-out 2.6s infinite; opacity: 0; }

        /* Heavy Duty Wheels — right of trolley (165+290=455px) */
        .s3 {
            top: 58%; left: calc(165px + 290px + 8px);
            animation: slideFromRight 0.6s cubic-bezier(0.22,1,0.36,1) 1.8s forwards,
                       specGlow 2.2s ease-in-out 2.4s infinite;
        }
        .s3-conn {
            top: calc(58% + 28px);
            left: calc(165px + 290px - 44px); width: 44px;
            background: linear-gradient(90deg, #0bb8b2, rgba(11,184,178,0.25));
            animation: growLine 0.4s ease 2.4s forwards; opacity: 0;
        }
        .s3-dot { top: calc(58% + 28px); left: calc(165px + 290px - 48px);
            animation: popDot 0.3s ease 2.8s forwards, dotGlow 1.6s ease-in-out 3.1s infinite; opacity: 0; }

        @keyframes slideFromLeft  { from { opacity:0; transform:translateX(-28px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideFromRight { from { opacity:0; transform:translateX(28px);  } to { opacity:1; transform:translateX(0); } }
        @keyframes growLine       { from { opacity:0; transform:scaleX(0); transform-origin:left; } to { opacity:1; transform:scaleX(1); } }
        @keyframes popDot         { from { opacity:0; transform:translateY(-50%) scale(0); } to { opacity:1; transform:translateY(-50%) scale(1); } }
        @keyframes fadeUp         { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        @keyframes cardSlideIn    { from { opacity:0; transform:translateX(50px) scale(0.97); } to { opacity:1; transform:translateX(0) scale(1); } }
        @keyframes specGlow {
            0%, 100% { box-shadow: 0 0 0px rgba(11,184,178,0);   border-color: rgba(11,184,178,0.45); }
            50%       { box-shadow: 0 0 18px rgba(11,184,178,0.65), 0 0 40px rgba(11,184,178,0.2); border-color: rgba(11,184,178,1); }
        }
        @keyframes dotGlow {
            0%, 100% { box-shadow: 0 0 6px rgba(11,184,178,0.8), 0 0 14px rgba(11,184,178,0.4); }
            50%       { box-shadow: 0 0 18px rgba(11,184,178,1),  0 0 36px rgba(11,184,178,0.8); }
        }

        /* ─ Content row: trolley + features side by side ─ */
        .content-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0;
            justify-content: flex-start;
            margin-top: 6px;
        }

        /* ─ Features column (right of trolley) ─ */
        .features-row {
            display: flex; flex-direction: column; gap: 5px;
            padding-left: 20px;
            align-self: center;
        }

        .feat-item {
            display: flex; align-items: center; gap: 11px;
            padding: 5px 10px; border-radius: 11px;
            background: rgba(5,18,40,0.45);
            border: 1px solid rgba(11,184,178,0.18);
            opacity: 0;
            transition: background 0.2s, border-color 0.2s;
        }
        .feat-item:hover {
            background: rgba(11,184,178,0.12);
            border-color: rgba(11,184,178,0.5);
        }

        .feat-icon {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            background: rgba(11,184,178,0.2);
            border: 1px solid rgba(11,184,178,0.5);
            display: flex; align-items: center; justify-content: center;
            color: #0bb8b2; font-size: 16px;
            box-shadow: 0 0 8px rgba(11,184,178,0.15);
        }

        .feat-text strong { display: block; font-size: 14px; font-weight: 700; color: #fff; line-height: 1.2; }
        .feat-text span   { font-size: 11.5px; color: rgba(255,255,255,0.55); }

        .fi-1 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 1.8s forwards; }
        .fi-2 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 2.1s forwards; }
        .fi-3 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 2.4s forwards; }
        .fi-4 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 2.7s forwards; }
        .fi-5 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 3.0s forwards; }
        .fi-6 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 3.3s forwards; }
        .fi-7 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 3.6s forwards; }
        .fi-8 { animation: slideFromRight 0.45s cubic-bezier(0.22,1,0.36,1) 3.9s forwards; }

        /* ══ LOGIN CARD ══ */
        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 22px;
            padding: 38px 36px 30px;
            width: 400px; flex-shrink: 0;
            box-shadow: 0 28px 80px rgba(0,0,0,0.42), 0 0 0 1px rgba(255,255,255,0.1);
            backdrop-filter: blur(6px);
            opacity: 0;
            animation: cardSlideIn 0.8s cubic-bezier(0.22,1,0.36,1) 0.2s forwards;
        }

        .card-h   { font-size: 26px; font-weight: 800; color: #0f1e40; margin-bottom: 4px; }
        .card-sub { font-size: 13px; color: #6b7a96; margin-bottom: 22px; }

        .f-label { display: block; font-size: 13px; font-weight: 600; color: #1a2a4a; margin-bottom: 6px; }

        .f-wrap { position: relative; margin-bottom: 14px; }
        .f-wrap .fi-l {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #b5bfc8; font-size: 15px; pointer-events: none;
        }
        .f-wrap .fi-r {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: #b5bfc8; font-size: 15px; cursor: pointer;
        }
        .f-wrap input {
            width: 100%; height: 50px;
            background: #f5f7fb; border: 1.5px solid #e4e9f2; border-radius: 11px;
            padding: 0 42px; font-size: 14px; color: #1a2a4a;
            font-family: 'Inter', sans-serif; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .f-wrap input:focus { border-color: #0bb8b2; background: #fff; box-shadow: 0 0 0 3px rgba(11,184,178,0.12); }
        .f-wrap input::placeholder { color: #c2cad8; }

        .rf-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
        .rf-row label { display: flex; align-items: center; gap: 7px; font-size: 13px; color: #4a5568; cursor: pointer; }
        .rf-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: #0bb8b2; }

        .forgot-lnk { font-size: 13px; color: #1a7ae0; text-decoration: none; font-weight: 500; }
        .forgot-lnk:hover { text-decoration: underline; }

        .signin-btn {
            width: 100%; height: 50px;
            background: linear-gradient(135deg, #0bb8b2 0%, #007f7a 100%);
            color: #fff; border: none; border-radius: 11px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            font-family: 'Inter', sans-serif; letter-spacing: 0.3px;
            box-shadow: 0 4px 18px rgba(11,184,178,0.4);
            transition: opacity 0.2s, transform 0.1s, box-shadow 0.2s;
            margin-bottom: 6px;
        }
        .signin-btn:hover { opacity: 0.91; transform: translateY(-1px); box-shadow: 0 8px 28px rgba(11,184,178,0.55); }
        .signin-btn:active { transform: translateY(0); }

        .card-foot { text-align: center; border-top: 1px solid #eef1f7; padding-top: 14px; margin-top: 16px; }
        .card-foot .sec-txt { font-size: 12px; color: #8a9ab5; font-weight: 500; }
        .card-foot .tag-txt { font-size: 11px; color: #b5bfcf; margin-top: 3px; }

        .alert { border-radius: 10px; font-size: 13px; }
        .error { color: #dc3545; font-size: 12px; }

        /* ── Footer bar ── */
        .page-footer {
            position: relative; z-index: 1;
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 36px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 12px; color: rgba(255,255,255,0.4);
        }
        .page-footer a { color: rgba(255,255,255,0.4); text-decoration: none; }
        .page-footer a:hover { color: #0bb8b2; }

        @media (max-width: 991.98px) {
            .page-logo, .left-panel, .page-footer { display: none; }
            .page-wrapper { justify-content: center; padding: 24px 16px; }
            .login-card { width: 100%; max-width: 420px; }
        }
    </style>
</head>

<body>

    {{-- Logo top-left --}}
    <div class="page-logo">
        <img src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}" alt="Shreerag">
    </div>

    <div class="page-wrapper">

        {{-- ══ LEFT PANEL ══ --}}
        <div class="left-panel">

            <div class="accent-bar"></div>
            <h1 class="main-heading">From <span>Design</span> to Movement</h1>
            <p class="main-sub">Precision Engineering. Reliable Trolley Solutions.</p>

            {{-- Trolley + features in a row --}}
            <div class="content-row">
            <div class="trolley-section">

                {{-- Spec: Durable Structure (left, upper) --}}
                <div class="spec-box s1">
                    <div class="spec-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="spec-text">Durable<br>Structure</div>
                </div>
                <div class="spec-connector s1-conn"></div>
                <div class="spec-dot s1-dot"></div>

                {{-- Spec: Custom Designs (left, lower) --}}
                <div class="spec-box s2">
                    <div class="spec-icon"><i class="fa-solid fa-gear"></i></div>
                    <div class="spec-text">Custom<br>Designs</div>
                </div>
                <div class="spec-connector s2-conn"></div>
                <div class="spec-dot s2-dot"></div>

                {{-- Trolley image --}}
                <img src="{{ asset('img/logo/trolly33.png') }}" alt="Shreerag Trolley" class="trolley-img">

                {{-- Spec: Heavy Duty Wheels (right) --}}
                <div class="spec-box s3">
                    <div class="spec-icon"><i class="fa-solid fa-circle-dot"></i></div>
                    <div class="spec-text">Heavy Duty<br>Wheels</div>
                </div>
                <div class="spec-connector s3-conn"></div>
                <div class="spec-dot s3-dot"></div>

            </div>{{-- /trolley-section --}}

            {{-- Features column — right of trolley --}}
            <div class="features-row">
                <div class="feat-item fi-1">
                    <div class="feat-icon"><i class="fa-solid fa-drafting-compass"></i></div>
                    <div class="feat-text">
                        <strong>Design</strong>
                        <span>Engineering Drawings</span>
                    </div>
                </div>
                <div class="feat-item fi-2">
                    <div class="feat-icon"><i class="fa-solid fa-industry"></i></div>
                    <div class="feat-text">
                        <strong>Manufacturing</strong>
                        <span>Production &amp; Workflow</span>
                    </div>
                </div>
                <div class="feat-item fi-3">
                    <div class="feat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div class="feat-text">
                        <strong>Inventory</strong>
                        <span>Real-time Stock Control</span>
                    </div>
                </div>
                <div class="feat-item fi-4">
                    <div class="feat-icon"><i class="fa-solid fa-chart-bar"></i></div>
                    <div class="feat-text">
                        <strong>Reports</strong>
                        <span>Business Insights</span>
                    </div>
                </div>
                <div class="feat-item fi-5">
                    <div class="feat-icon"><i class="fa-solid fa-file-invoice"></i></div>
                    <div class="feat-text">
                        <strong>Purchase Order</strong>
                        <span>Vendor &amp; PO Management</span>
                    </div>
                </div>
                <div class="feat-item fi-6">
                    <div class="feat-icon"><i class="fa-solid fa-calculator"></i></div>
                    <div class="feat-text">
                        <strong>Estimation</strong>
                        <span>Cost &amp; BOM Planning</span>
                    </div>
                </div>
                <div class="feat-item fi-7">
                    <div class="feat-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                    <div class="feat-text">
                        <strong>Production</strong>
                        <span>Work Order Tracking</span>
                    </div>
                </div>
                <div class="feat-item fi-8">
                    <div class="feat-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="feat-text">
                        <strong>Dispatch</strong>
                        <span>Delivery &amp; Logistics</span>
                    </div>
                </div>
            </div>{{-- /features-row --}}
            </div>{{-- /content-row --}}

        </div>

        {{-- ══ LOGIN CARD ══ --}}
        <div class="login-card">

            <h3 class="card-h">Welcome Back!</h3>
            <p class="card-sub">Sign in to continue to Shreerag ERP</p>

            @if (isset($return_data['msg_alert']) && $return_data['msg_alert'] == 'green')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $return_data['msg'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <label class="f-label" for="email">Username / Email</label>
                <div class="f-wrap">
                    <i class="fa-regular fa-user fi-l"></i>
                    <input type="text" id="email" name="email" placeholder="Enter your username or email">
                </div>

                <label class="f-label" for="password">Password</label>
                <div class="f-wrap">
                    <i class="fa-solid fa-lock fi-l"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    <i class="fa-regular fa-eye-slash fi-r" id="togglePassword"></i>
                </div>

                @if (config('captcha.enabled'))
                    <div class="mb-3">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                    </div>
                @endif
                @if ($errors->has('g-recaptcha-response'))
                    <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                @endif

                <div class="rf-row">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#" class="forgot-lnk">Forgot Password?</a>
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="location_address" id="location_address">

                <button type="submit" class="signin-btn">Sign In &nbsp;&rarr;</button>
            </form>

            <div class="card-foot">
                <div class="sec-txt">
                    <i class="fa-solid fa-lock" style="color:#0bb8b2;font-size:11px;"></i>
                    &nbsp;Secure Enterprise Access
                </div>
                <div class="tag-txt">Your Business &bull; Our Technology &bull; A Stronger Tomorrow</div>
            </div>

        </div>
    </div>

    {{-- Footer bar --}}
    <footer class="page-footer">
        <span>&copy; 2026 Shreerag Engineering &amp; Auto Pvt. Ltd. &nbsp;|&nbsp; Shreerag ERP &nbsp;v2.0.0</span>
        <span>
            <a href="#">Privacy Policy</a> &nbsp;|&nbsp;
            <a href="#">Terms of Use</a> &nbsp;|&nbsp;
            <a href="#">Support</a>
        </span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $.validator.addMethod("spcenotallow", function(value, element) {
                return this.optional(element) || value.trim().length > 0;
            }, "Enter some valid text");
            $("#loginForm").validate({
                rules: {
                    email: { required: true, email: true },
                    password: { required: true },
                },
                messages: {
                    email: { required: "Please enter your email Id.", email: "Please enter a valid email Id." },
                    password: { required: "Please enter the password." },
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#togglePassword").click(function() {
                const passwordField = $("#password");
                const type = passwordField.attr("type") === "password" ? "text" : "password";
                passwordField.attr("type", type);
                $(this).toggleClass("fa fa-eye fa-eye fa-eye-slash");
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loginForm = document.getElementById("loginForm");
            loginForm.addEventListener("submit", function(event) {
                event.preventDefault();
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById("latitude").value = position.coords.latitude;
                        document.getElementById("longitude").value = position.coords.longitude;
                        loginForm.submit();
                    }, function(error) {
                        loginForm.submit();
                    }, { timeout: 5000, maximumAge: 60000 });
                } else {
                    loginForm.submit();
                }
            });
        });
    </script>
</body>
</html>
