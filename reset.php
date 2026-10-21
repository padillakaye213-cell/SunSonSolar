<?php
require_once __DIR__ . '/includes/layout.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$row = $token ? q('SELECT * FROM password_resets WHERE token_hash=? AND used=0 AND expires_at>NOW()', [hash('sha256', $token)])->fetch() : null;
$err = '';
if ($row && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = $_POST['password'] ?? '';
    if ($m = password_problem($pw)) $err = $m;
    elseif ($pw !== ($_POST['confirm'] ?? '')) $err = 'Passwords do not match.';
    else {
        q('UPDATE users SET password_hash=? WHERE id=?', [password_hash($pw, PASSWORD_DEFAULT), $row['user_id']]);
        q('UPDATE password_resets SET used=1 WHERE id=?', [$row['id']]);
        $_SESSION['uid'] = null; unset($_SESSION['uid']);
        audit('password_reset_done', 'user #' . $row['user_id']);
        flash('success', 'Password updated. You can log in now.');
        redirect('login.php');
    }
}
page_head('Reset password');
?>
<div class="authwrap"><div class="card authcard">
<?php if (!$row): ?>
  <h1 style="font-size:1.8rem">Link expired</h1><p>This reset link is invalid or has expired.</p><a class="btn btn-primary" href="<?= e(url('forgot.php')) ?>">Request a new link</a>
<?php else: ?>
  <h1 style="font-size:1.8rem">Choose a new password</h1>
  <?php if ($err): ?><div class="alert alert-error" role="alert"><?= e($err) ?></div><?php endif; ?>
  <form method="post" data-validate novalidate><?= csrf_field() ?><input type="hidden" name="token" value="<?= e($token) ?>">
    <div class="field"><label class="req" for="password">New password</label><div class="pwwrap"><input id="password" name="password" type="password" required data-strength autocomplete="new-password"><button class="pwtoggle" type="button">Show</button></div><div class="meter"><span></span></div><div class="meter-label"></div></div>
    <div class="field"><label class="req" for="confirm">Confirm password</label><div class="pwwrap"><input id="confirm" name="confirm" type="password" required autocomplete="new-password"><button class="pwtoggle" type="button">Show</button></div></div>
    <button class="btn btn-primary btn-block" type="submit">Update password</button>
  </form>
<?php endif; ?>
</div></div>
<?php page_foot(); ?>
