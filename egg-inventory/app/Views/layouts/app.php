<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Egg Inventory') ?> — EggTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:    #fdf8f0;
            --warm:     #f5ede0;
            --gold:     #c8963e;
            --gold-lt:  #e8b96a;
            --brown:    #6b4226;
            --quail:    #8b7355;
            --white-eg: #e8e0d5;
            --ink:      #2c1f14;
            --muted:    #8a7060;
            --green:    #4a7c59;
            --red:      #c0392b;
            --shadow:   0 2px 16px rgba(44,31,20,.10);
            --radius:   14px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── Nav ── */
        nav {
            background: var(--brown);
            padding: 0 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: 0 2px 12px rgba(0,0,0,.18);
        }
        .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--gold-lt);
            text-decoration: none;
            letter-spacing: .03em;
        }
        .nav-brand span { color: #fff; font-weight: 300; }
        .nav-links a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            margin-left: 2rem;
            font-size: .9rem;
            font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--gold-lt); }
        .nav-links a.active { color: var(--gold-lt); }

        /* ── Layout ── */
        .page-wrap {
            max-width: 1080px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
        }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--warm);
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--brown);
        }
        .page-header p { color: var(--muted); margin-top: .3rem; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .6rem 1.3rem;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all .18s;
        }
        .btn-primary   { background: var(--gold); color: #fff; }
        .btn-primary:hover { background: var(--brown); }
        .btn-secondary { background: var(--warm); color: var(--brown); border: 1.5px solid var(--gold-lt); }
        .btn-secondary:hover { background: var(--gold-lt); color: #fff; }
        .btn-danger    { background: #fdf0ee; color: var(--red); border: 1.5px solid #f5c6c2; }
        .btn-danger:hover  { background: var(--red); color: #fff; }
        .btn-sm { padding: .4rem .85rem; font-size: .8rem; }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* ── Summary chips ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .summary-chip {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--gold);
            display: flex; flex-direction: column; gap: .35rem;
        }
        .summary-chip.quail  { border-color: var(--quail); }
        .summary-chip.white  { border-color: #bbb; }
        .summary-chip.brown  { border-color: var(--brown); }
        .summary-chip .chip-label {
            font-size: .75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .08em;
            color: var(--muted);
        }
        .summary-chip .chip-qty {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }
        .summary-chip .chip-sub { font-size: .8rem; color: var(--muted); }

        /* ── Table ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            background: var(--warm);
            padding: .75rem 1rem;
            text-align: left;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .data-table tbody tr { border-bottom: 1px solid var(--warm); transition: background .15s; }
        .data-table tbody tr:hover { background: #faf5ee; }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table td { padding: .85rem 1rem; font-size: .9rem; vertical-align: middle; }
        .data-table .actions { display: flex; gap: .5rem; }

        /* ── Badge ── */
        .badge {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .28rem .7rem;
            border-radius: 20px;
            font-size: .78rem; font-weight: 600;
        }
        .badge-quail { background: #f0ebe3; color: var(--quail); }
        .badge-white { background: #f0f0f0; color: #666; }
        .badge-brown { background: #f5ece5; color: var(--brown); }
        .badge::before { content: '🥚'; font-size: .85em; }
        .badge-quail::before { content: '🪺'; }

        /* ── Form ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .form-group { display: flex; flex-direction: column; gap: .45rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: .83rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
        input, select, textarea {
            padding: .65rem 1rem;
            border: 1.5px solid #e0d8ce;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            color: var(--ink);
            background: #fff;
            transition: border-color .18s, box-shadow .18s;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200,150,62,.15);
        }
        textarea { resize: vertical; min-height: 80px; }
        .field-error { font-size: .8rem; color: var(--red); margin-top: -.2rem; }

        .error-box {
            background: #fff5f5;
            border: 1.5px solid #f5c6c2;
            border-radius: 8px;
            padding: .9rem 1.1rem;
            margin-bottom: 1.5rem;
            color: var(--red);
            font-size: .88rem;
        }

        /* ── Misc ── */
        .empty-state {
            text-align: center; padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state .icon { font-size: 3.5rem; margin-bottom: 1rem; }
        .empty-state p { margin-top: .5rem; }
        .text-muted { color: var(--muted); }
        .mt-1 { margin-top: .5rem; }
        .mt-2 { margin-top: 1rem; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .detail-item { display: flex; flex-direction: column; gap: .3rem; }
        .detail-item .dl { font-size: .78rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
        .detail-item .dd { font-size: 1.05rem; color: var(--ink); }
    </style>
</head>
<body>
<nav>
    <a href="/eggs" class="nav-brand">Egg<span>Track</span></a>
    <div class="nav-links">
        <a href="/eggs">Inventory</a>
        <a href="/eggs/create">+ New Batch</a>
    </div>
</nav>

<div class="page-wrap">
    <?= $content ?? '' ?>
</div>
</body>
</html>
