<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #173042;
        --muted: #61808b;
        --paper: #f7fbfa;
        --teal: #087f8c;
        --teal-dark: #075e68;
        --teal-soft: #e2f3f1;
        --coral: #ef765f;
        --coral-soft: #fff0ec;
        --sky: #3a7ea8;
        --line: #dbe9e6;
        --bs-primary: #087f8c;
        --bs-primary-rgb: 8, 127, 140;
        --bs-info: #087f8c;
        --bs-info-rgb: 8, 127, 140;
    }

    * { box-sizing: border-box; }

    body {
        min-height: 100vh;
        margin: 0;
        color: var(--ink);
        background:
            radial-gradient(circle at top left, rgba(8, 127, 140, .18), transparent 24%),
            radial-gradient(circle at bottom right, rgba(58, 126, 168, .14), transparent 28%),
            linear-gradient(135deg, #f4fbfa 0%, #edf5f9 100%);
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-shell {
        min-height: 100vh;
        padding: 32px 18px;
    }

    .login-panel {
        display: grid;
        grid-template-columns: 1.1fr 1.5fr;
        width: min(1120px, 100%);
        overflow: hidden;
        border: 1px solid rgba(219, 233, 230, .95);
        border-radius: 30px;
        background: rgba(255, 255, 255, .9);
        box-shadow: 0 30px 80px rgba(17, 39, 52, .12);
        backdrop-filter: blur(8px);
    }

    .login-hero {
        position: relative;
        padding: 40px 34px;
        background: linear-gradient(180deg, rgba(8, 127, 140, .97) 0%, rgba(7, 94, 104, .97) 100%);
        color: #fff;
    }

    .login-hero::after {
        content: "";
        position: absolute;
        right: -80px;
        bottom: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }

    .login-brand {
        color: #dffaf6;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
    }

    .brand-badge {
        display: inline-block;
        padding: .38rem .65rem;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 999px;
        background: rgba(255,255,255,.08);
        letter-spacing: .1em;
    }

    .login-title {
        position: relative;
        z-index: 1;
        color: var(--ink);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.08;
    }

    .login-subtitle {
        position: relative;
        z-index: 1;
        max-width: 30rem;
        color: var(--muted);
        font-size: .98rem;
        line-height: 1.7;
    }
    .login-hero .login-title { color: #fff; }
    .login-hero .login-subtitle { color: rgba(255,255,255,.82); }
    .login-form-card .login-brand { color: var(--teal-dark); }
    .login-form-card .login-title { color: var(--ink); }
    .login-form-card .login-subtitle { color: var(--muted); }

    .feature-list {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 12px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .feature-item {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        padding: .75rem .85rem;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 999px;
        background: rgba(255,255,255,.08);
        font-size: .9rem;
    }

    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(255,255,255,.18);
        font-size: .8rem;
        font-weight: 700;
    }

    .mini-stats {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .stat-box {
        min-width: 120px;
        padding: 14px 16px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 16px;
        background: rgba(255,255,255,.09);
    }

    .stat-box strong {
        display: block;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .stat-box span {
        color: rgba(255,255,255,.8);
        font-size: .76rem;
    }

    .login-roles {
        padding: 34px 28px 30px;
        background: linear-gradient(180deg, rgba(255,255,255,.6), rgba(247,251,250,.96));
    }

    .role-header {
        margin-bottom: 20px;
    }

    .eyebrow {
        display: inline-block;
        margin-bottom: 8px;
        color: var(--teal-dark);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .role-header h2 {
        margin: 0;
        color: var(--ink);
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: -.04em;
    }

    .role-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .role-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 220px;
        padding: 22px 18px 16px;
        border: 1px solid var(--line);
        border-radius: 18px;
        color: var(--ink);
        background: #fff;
        text-decoration: none;
        box-shadow: 0 10px 24px rgba(17, 39, 52, .04);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .role-card:hover {
        color: var(--ink);
        border-color: rgba(8, 127, 140, .5);
        box-shadow: 0 18px 32px rgba(8, 127, 140, .12);
        transform: translateY(-4px);
    }

    .role-mark {
        display: inline-flex;
        width: 46px;
        height: 46px;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 14px;
        color: #fff;
        background: var(--teal);
        font-weight: 700;
        box-shadow: 0 12px 22px rgba(8, 127, 140, .18);
    }

    .role-card:nth-child(2) .role-mark { background: var(--sky); }
    .role-card:nth-child(3) .role-mark { background: var(--coral); }

    .role-card h2 {
        margin: 0 0 10px;
        font-size: 1.32rem;
        font-weight: 800;
    }

    .role-card p {
        min-height: 72px;
        margin: 0 0 18px;
        color: var(--muted);
        font-size: .9rem;
        line-height: 1.7;
    }

    .role-link {
        margin-top: auto;
        color: var(--teal-dark);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .login-form-card {
        width: 100%;
        max-width: 460px;
        padding: 34px;
        border: 1px solid var(--line);
        border-radius: 24px;
        background: rgba(255, 255, 255, .92);
        box-shadow: 0 24px 60px rgba(23, 48, 66, .12);
    }

    .login-form-card label,
    .login-form-card input,
    .login-form-card button,
    .login-form-card .alert { font-family: Arial, sans-serif; }

    .login-form-card .form-control {
        padding: .75rem .9rem;
        border-color: var(--line);
        border-radius: 12px;
        background: #fafdfe;
    }

    .login-form-card .form-control:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 .22rem rgba(8, 127, 140, .14);
    }

    .login-button {
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
        font-weight: 700;
        box-shadow: 0 12px 24px rgba(8, 127, 140, .22);
    }

    .login-button:hover { background: linear-gradient(135deg, var(--teal-dark) 0%, var(--teal) 100%); }

    .back-link {
        color: var(--muted);
        font-family: Arial, sans-serif;
        font-size: .88rem;
        text-decoration: none;
    }

    .back-link:hover { color: var(--teal-dark); }

    @media (max-width: 900px) {
        .login-panel {
            grid-template-columns: 1fr;
        }

        .login-roles {
            padding-top: 18px;
        }
    }

    @media (max-width: 640px) {
        .role-grid {
            grid-template-columns: 1fr;
        }

        .login-hero,
        .login-roles {
            padding-left: 20px;
            padding-right: 20px;
        }
    }
</style>