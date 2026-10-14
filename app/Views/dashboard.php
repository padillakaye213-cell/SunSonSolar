<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Sun Son Solar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #EAF2FB; color: #132433; margin: 0; padding: 60px 20px; }
        .card { background: #FFF; border: 1px solid #D7E3EF; border-radius: 12px; padding: 32px; max-width: 560px; margin: 0 auto; }
        h1 { color: #0B4C8C; margin: 0 0 8px; }
        p { color: #5C7185; }
        a { color: #1668B8; font-weight: 600; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Welcome, <?= esc($role) ?> <?= esc($name) ?></h1>
        <p>You are signed in successfully.</p>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </div>
</body>
</html>