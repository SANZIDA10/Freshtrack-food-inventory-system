<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FreshTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ft-bg: #f6f4ff;
            --ft-navy: #241b55;
            --ft-navy-soft: #352a72;
            --ft-purple: #7b3ff2;
            --ft-purple-soft: #efe7ff;
            --ft-orange: #f8a11a;
            --ft-green: #2ac38b;
            --ft-pink: #f14fa5;
            --ft-text: #1f163f;
            --ft-muted: #756f8a;
            --ft-card: rgba(255, 255, 255, 0.82);
            --ft-border: rgba(70, 53, 139, 0.12);
        }

        html, body {
            min-height: 100%;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(123, 63, 242, 0.10), transparent 24%),
                radial-gradient(circle at 15% 20%, rgba(248, 161, 26, 0.08), transparent 18%),
                linear-gradient(180deg, #ffffff 0%, #fbf9ff 100%);
            color: var(--ft-text);
        }

        .ft-topbar {
            background: #ffffff;
            border-bottom: 1px solid rgba(36, 27, 85, 0.08);
            padding: 0.85rem 1.5rem;
        }

        .ft-shell {
            background: var(--ft-navy);
            color: #fff;
            padding: 1rem 1.5rem;
        }

        .ft-brand {
            color: #fff;
            font-weight: 800;
            letter-spacing: -0.03em;
            font-size: 1.1rem;
        }

        .ft-brand:hover {
            color: #fff;
        }

        .ft-pill-nav {
            gap: 0.35rem;
            align-items: center;
        }

        .ft-pill-nav .nav-link {
            color: rgba(255, 255, 255, 0.72);
            font-weight: 700;
            border-radius: 999px;
            padding: 0.42rem 0.95rem;
        }

        .ft-pill-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .ft-search .form-control {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            border-radius: 999px;
            min-width: 220px;
        }

        .ft-search .form-control::placeholder {
            color: rgba(255, 255, 255, 0.58);
        }

        .ft-search .btn {
            border-radius: 999px;
            background: var(--ft-orange);
            border: 0;
            color: #27180a;
            font-weight: 800;
            padding-inline: 1rem;
        }

        .ft-content {
            padding: 1.25rem 1rem 0;
        }

        .ft-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8542f5 0%, #7b3ff2 45%, #6f38e7 100%);
            border-radius: 1rem;
            color: #fff;
            padding: 2rem 1.5rem;
            box-shadow: 0 16px 42px rgba(123, 63, 242, 0.25);
            margin-bottom: 1.5rem;
        }

        .ft-hero::before,
        .ft-hero::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
        }

        .ft-hero::before {
            width: 8rem;
            height: 8rem;
            top: -2rem;
            right: -1rem;
        }

        .ft-hero::after {
            width: 5rem;
            height: 5rem;
            right: 1.2rem;
            bottom: -1.2rem;
            background: rgba(255, 255, 255, 0.16);
        }

        .ft-hero h1 {
            margin: 0;
            font-size: clamp(1.55rem, 2vw, 2.1rem);
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .ft-hero p {
            margin: 0.4rem 0 0;
            color: rgba(255, 255, 255, 0.85);
        }

        .ft-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
            max-width: 34rem;
        }

        .ft-stat {
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
            border-radius: 0.8rem;
            padding: 0.75rem 0.8rem;
            min-height: 4rem;
        }

        .ft-stat .value {
            display: block;
            font-weight: 800;
            font-size: 1.45rem;
            line-height: 1;
        }

        .ft-stat .label {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
        }

        .ft-section-title {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #151326;
            margin: 1.5rem 0 0.85rem;
        }

        .ft-card,
        .ft-panel,
        .ft-table-panel,
        .ft-list-panel {
            background: var(--ft-card);
            border: 1px solid var(--ft-border);
            border-radius: 1rem;
            box-shadow: 0 12px 32px rgba(47, 31, 118, 0.06);
        }

        .ft-card {
            position: relative;
            min-height: 9rem;
            padding: 1rem 1rem 0.95rem;
            overflow: hidden;
        }

        .ft-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background: var(--ft-accent, var(--ft-purple));
        }

        .ft-card h5,
        .ft-panel h5,
        .ft-list-item strong {
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .ft-card p,
        .ft-panel p,
        .ft-panel small {
            color: var(--ft-muted);
        }

        .ft-panel {
            padding: 1rem;
        }

        .ft-kpi {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: baseline;
            margin-top: 1.1rem;
        }

        .ft-kpi .kpi-label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ft-muted);
        }

        .ft-kpi .kpi-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--ft-text);
        }

        .ft-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.24rem 0.55rem;
            font-size: 0.78rem;
            font-weight: 700;
            background: var(--ft-purple-soft);
            color: var(--ft-purple);
        }

        .ft-badge.orange {
            background: rgba(248, 161, 26, 0.16);
            color: #c87000;
        }

        .ft-badge.green {
            background: rgba(42, 195, 139, 0.16);
            color: #148b63;
        }

        .ft-badge.pink {
            background: rgba(241, 79, 165, 0.14);
            color: #b61f72;
        }

        .ft-table-panel .table {
            margin-bottom: 0;
        }

        .ft-table-panel .table thead th {
            border-bottom: 0;
            color: #131019;
            font-size: 0.74rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .ft-table-panel .table td,
        .ft-table-panel .table th {
            padding: 1rem 1.1rem;
            vertical-align: middle;
        }

        .ft-table-panel .table tbody tr:not(:last-child) td {
            border-bottom-color: rgba(71, 56, 140, 0.08);
        }

        .ft-list-panel .list-group-item {
            background: transparent;
            border-color: rgba(71, 56, 140, 0.08);
            padding: 1rem 1.1rem;
        }

        .ft-footer {
            background: var(--ft-navy);
            color: rgba(255, 255, 255, 0.62);
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }

        @media (max-width: 991.98px) {
            .ft-topbar .navbar-nav,
            .ft-shell .navbar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .ft-search {
                width: 100%;
                margin-top: 0.75rem;
            }

            .ft-search .form-control {
                min-width: 0;
                width: 100%;
            }

            .ft-stat-grid {
                grid-template-columns: 1fr;
                max-width: 100%;
            }

            .ft-hero {
                padding: 1.4rem 1.1rem;
            }
        }
    </style>
</head>
<body>

<nav class="ft-topbar navbar navbar-expand-lg">
    <div class="container-fluid px-0">
        <div class="navbar-nav flex-row gap-2 ft-pill-nav">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
            <a class="nav-link {{ request()->is('inventory') ? 'active' : '' }}" href="/inventory">Inventory</a>
            <a class="nav-link {{ request()->is('categories') ? 'active' : '' }}" href="/categories">Categories</a>
            <a class="nav-link {{ request()->is('reports') ? 'active' : '' }}" href="/reports">Reports</a>
        </div>
    </div>
</nav>

<nav class="ft-shell navbar navbar-expand-lg">
    <div class="container-fluid px-0 d-flex align-items-center gap-3 flex-wrap">
        <a class="navbar-brand ft-brand" href="/">Fresh•Track</a>
        <div class="navbar-nav flex-row gap-1 ft-pill-nav me-auto">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
            <a class="nav-link {{ request()->is('inventory') ? 'active' : '' }}" href="/inventory">Inventory</a>
            <a class="nav-link {{ request()->is('categories') ? 'active' : '' }}" href="/categories">Categories</a>
            <a class="nav-link {{ request()->is('reports') ? 'active' : '' }}" href="/reports">Reports</a>
        </div>
        <form class="d-flex ft-search ms-auto" role="search" action="/inventory" method="get">
            <input name="q" value="{{ request('q') }}" class="form-control form-control-sm me-2" type="search" placeholder="Search products..." aria-label="Search">
            <button class="btn btn-sm" type="submit">Search</button>
        </form>
    </div>
</nav>

<!-- PAGE CONTENT -->
<main class="container-fluid ft-content">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="ft-footer">
    © 2026 FreshTrack · Smart Food Inventory System
</footer>

</body>
</html>
