<?php
require_once __DIR__ . '/includes/layout.php';
$link = null; $sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['identifier'] ?? '');
    $u = $id !== '' ? q('SELECT * FROM users WHERE (username=? OR email=?) AND is_active=1', [$id, $id])->fetch() : null;
    if ($u) {
        $token = bin2hex(random_bytes(32));
        q('INSERT INTO password_resets (user_id,token_hash,expires_at) VALUES (?,?,?)', [$u['id'], hash('sha256', $token), date('Y-m-d H:i:s', time() + 3600)]);
        $reset = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . url('reset.php?token=' . $token);
        @mail($u['email'], 'Reset your Sun Son Solar password', "Hello {$u['first_name']},\n\nUse this link within 1 hour to choose a new password:\n$reset\n\nIf you did not ask for this, ignore this email.", 'From: ' . setting('company_email'));
        audit('password_reset_requested', $u['username']);
        // Local testing only: show the link on screen when no mail server is configured.
        if (defined('DEV_SHOW_RESET_LINK') && DEV_SHOW_RESET_LINK) $link = $reset;
    }
    $sent = true; // same message whether or not the account exists
}
page_head('Forgot password');
?>
<div class="authwrap"><div class="card authcard">
  <h1 style="font-size:1.8rem">Forgot your password?</h1>
  <?php if ($sent): ?>
    <div class="alert alert-success">If an account matches, we sent a reset link. It expires in 1 hour.</div>
    <?php if ($link): ?><div class="alert alert-info"><strong>Dev mode:</strong> <a href="<?= e($link) ?>">Open reset link</a></div><?php endif; ?>
  <?php else: ?>
  <p class="muted">Enter your username or email and we will send you a link to reset it.</p>
  <form method="post"><?= csrf_field() ?>
    <div class="field"><label class="req" for="identifier">Username or email</label><input id="identifier" name="identifier" type="text" required autofocus></div>
    <button class="btn btn-primary btn-block" type="submit">Send reset link</button>
  </form>
  <?php endif; ?>
  <p class="center" style="margin-top:1rem"><a href="<?= e(url('login.php')) ?>">← Back to login</a></p>
</div></div>
<?php page_foot(); ?>
