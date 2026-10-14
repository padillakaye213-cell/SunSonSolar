<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun Son Solar — Sign in</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--blue-dark:#0B4C8C;--blue:#1668B8;--blue-light:#EAF2FB;--white:#FFF;--ink:#132433;--ink-soft:#5C7185;--line:#D7E3EF;--err:#C13A2E;--radius:8px;--font:'Inter',sans-serif;}
        *{box-sizing:border-box;}
        body{font-family:var(--font); background:var(--blue-light); color:var(--ink); margin:0; min-height:100vh;}
        .page{max-width:440px; margin:0 auto; padding:56px 20px 80px;}
        .brand{display:flex; align-items:center; gap:10px; margin-bottom:24px;}
        .brand-mark{width:32px; height:32px; border-radius:50%; background:var(--blue);}
        .brand-name{font-weight:700; font-size:18px; color:var(--blue-dark);}
        .card{background:var(--white); border:1px solid var(--line); border-radius:12px; padding:32px 28px;}
        .card h1{font-size:22px; margin:0 0 6px;}
        .card p{font-size:14px; color:var(--ink-soft); margin:0 0 24px;}
        .card a{color:var(--blue); text-decoration:none; font-weight:600;}
        .field{margin-bottom:14px; display:flex; flex-direction:column; gap:5px;}
        .field label{font-size:13px; font-weight:500;}
        .field input{font-family:var(--font); font-size:14px; padding:10px 12px; border:1.5px solid var(--line); border-radius:var(--radius); outline:none; width:100%;}
        .field input:focus{border-color:var(--blue); box-shadow:0 0 0 3px rgba(22,104,184,0.15);}
        .btn-primary{width:100%; padding:12px 16px; border:0; border-radius:var(--radius); background:var(--blue); color:#FFF; font-family:var(--font); font-size:15px; font-weight:600; cursor:pointer; margin-top:6px;}
        .btn-primary:hover{background:var(--blue-dark);}
        .flash{padding:12px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; text-align:center;}
        .flash.success{background:var(--blue-light); border:1px solid var(--blue); color:var(--blue-dark);}
        .flash.error{background:#FDECEA; border:1px solid var(--err); color:var(--err);}
    </style>
</head>
<body>
<div class="page">
    <div class="brand">
        <span class="brand-mark"></span>
        <span class="brand-name">Sun Son Solar</span>
    </div>

    <div class="card">
        <h1>Sign in</h1>
        <p>New to Sun Son Solar? <a href="<?= site_url('register') ?>">Create an account</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/attemptSignin') ?>">
            <?= csrf_field() ?>
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Sign in</button>
        </form>
    </div>
</div>
</body>
</html>