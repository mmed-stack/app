<?php

session_start();

include("../config/db.php");

$message = "";

if(isset($_POST['admin_login'])){

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM useres
            WHERE email='$email'
            AND password='$password'
            AND role='admin'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        $_SESSION['id']   = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        header("Location: ../admin/dashboard.php");
        exit();

    } else {

        $check = "SELECT * FROM useres WHERE email='$email' AND password='$password'";
        $check_result = mysqli_query($conn, $check);

        if(mysqli_num_rows($check_result) > 0){
            $message = "Acc&egrave;s refus&eacute;. Ce compte n'a pas les droits administrateur.";
        } else {
            $message = "Identifiants incorrects. Acc&egrave;s refus&eacute;.";
        }

    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <title>Admin Login &mdash; Pharmacy</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

    <style>

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #0e0e0e;
            --surface:    #161616;
            --surface-2:  #1c1c1c;
            --border:     #252525;
            --gold:       #c9a84c;
            --gold-soft:  rgba(201,168,76,0.12);
            --gold-glow:  rgba(201,168,76,0.06);
            --text:       #e8e8e8;
            --text-mid:   #888;
            --text-dim:   #444;
            --radius:     12px;
            --radius-sm:  8px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Mono', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Ambient glow */
        body::after {
            content: '';
            position: fixed;
            top: 30%; left: 50%;
            transform: translate(-50%, -50%);
            width: 500px; height: 300px;
            background: radial-gradient(ellipse, rgba(201,168,76,0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
        }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem 2.2rem;
            position: relative;
            animation: rise 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03) inset,
                0 32px 64px rgba(0,0,0,0.5),
                0 0 80px rgba(201,168,76,0.03);
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card:hover {
            border-color: #2e2e2e;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03) inset,
                0 40px 80px rgba(0,0,0,0.6),
                0 0 100px rgba(201,168,76,0.04);
            transition: box-shadow 0.4s, border-color 0.4s;
        }

        /* Status strip */
        .status-strip {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
        }

        .dot {
            width: 6px; height: 6px;
            background: var(--gold);
            border-radius: 50%;
            flex-shrink: 0;
            animation: breathe 3s ease-in-out infinite;
        }

        @keyframes breathe {
            0%, 100% { opacity: 1; box-shadow: 0 0 6px var(--gold); }
            50%       { opacity: 0.4; box-shadow: none; }
        }

        .status-text {
            font-size: 0.58rem;
            letter-spacing: 0.18em;
            color: var(--text-dim);
            text-transform: uppercase;
            flex: 1;
        }

        .status-badge {
            font-size: 0.58rem;
            letter-spacing: 0.1em;
            color: var(--gold);
            background: var(--gold-soft);
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid rgba(201,168,76,0.2);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px; height: 44px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            margin-bottom: 1.2rem;
            background: var(--surface-2);
        }

        .logo-wrap svg {
            width: 20px; height: 20px;
            color: var(--gold);
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.01em;
            margin-bottom: 0.3rem;
        }

        .subtitle {
            font-size: 0.7rem;
            color: var(--text-dim);
            letter-spacing: 0.05em;
        }

        /* Alert — error message */
        .alert-admin {
            background: rgba(201,168,76,0.07);
            border: 1px solid rgba(201,168,76,0.18);
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.72rem;
            color: rgba(201,168,76,0.85);
            margin-bottom: 1.6rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .alert-admin::before {
            content: '&#9888;';
            flex-shrink: 0;
        }

        /* Fields */
        .field-group { margin-bottom: 1.1rem; }

        .field-label {
            display: block;
            font-size: 0.62rem;
            letter-spacing: 0.12em;
            color: var(--text-dim);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .field-group input {
            width: 100%;
            background: var(--surface-2) !important;
            color: var(--text) !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.75rem 1rem !important;
            font-family: 'DM Mono', monospace !important;
            font-size: 0.85rem !important;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-group input:focus {
            border-color: rgba(201,168,76,0.4) !important;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.07) !important;
        }

        .field-group input::placeholder {
            color: var(--text-dim) !important;
        }

        /* Primary button */
        .btn-admin {
            width: 100%;
            background: var(--gold);
            color: #0a0800;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0.85rem;
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 0.4rem;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(201,168,76,0.2);
        }

        .btn-admin:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 6px 28px rgba(201,168,76,0.3);
        }

        .btn-admin:active {
            transform: translateY(0);
            opacity: 1;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            font-size: 0.6rem;
            color: var(--text-dim);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* Switch button */
        .btn-switch {
            display: block;
            width: 100%;
            text-align: center;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-mid);
            font-family: 'DM Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            padding: 0.75rem;
            text-decoration: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .btn-switch:hover {
            border-color: rgba(201,168,76,0.35);
            color: var(--gold);
            background: var(--gold-glow);
        }

        /* Footer */
        .card-footer {
            margin-top: 1.8rem;
            padding-top: 1.2rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-footer span {
            font-size: 0.58rem;
            color: var(--text-dim);
            letter-spacing: 0.08em;
        }

        .card-footer .ver {
            color: rgba(201,168,76,0.4);
        }

    </style>

</head>
<body>

    <div class="container">
        <div class="card">

            <!-- Status strip -->
            <div class="status-strip">
                <span class="dot"></span>
                <span class="status-text">Pharmacy System &mdash; Admin Portal</span>
                <span class="status-badge">SECURE</span>
            </div>

            <!-- Header -->
            <div class="header">
                
                <h1>Admin Access</h1>
                <p class="subtitle">Restricted &mdash; Authorized Personnel Only</p>
            </div>

            <!-- Error message -->
            <?php if($message != ""): ?>
                <div class="alert-admin"><?= $message ?></div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST">

                <div class="field-group">
                    <label class="field-label">Admin Email</label>
                    <input type="email" name="email" placeholder="admin@example.com" required>
                </div>

                <div class="field-group">
                    <label class="field-label">Access Code</label>
                    <input type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                </div>

                <button type="submit" name="admin_login" class="btn-admin">
                    Authenticate &amp; Enter
                </button>

            </form>

            <div class="divider"><span>or</span></div>

            <a href="login.php" class="btn-switch">
                Switch to Customer Portal
            </a>

           

        </div>
    </div>

</body>
</html>