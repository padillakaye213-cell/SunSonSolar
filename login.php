<?php
require_once __DIR__ . '/includes/layout.php';
if (me()) redirect(home_for(me()['role']));

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tries = $_SESSION['tries'] ?? 0; $lock = $_SESSION['lock_until'] ?? 0;
    if ($lock > time()) {
        $error = 'Too many failed attempts. Please wait ' . ($lock - time()) . ' seconds and try again.';
    } else {
        $u = q('SELECT * FROM users WHERE username=?', [trim($_POST['username'] ?? '')])->fetch();
        if ($u && $u['is_active'] && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['uid'] = (int)$u['id']; $_SESSION['last'] = time(); $_SESSION['tries'] = 0;
            audit('login', $u['username'] . ' (' . $u['role'] . ')');
            redirect(home_for($u['role']));
        }
        $tries++; $_SESSION['tries'] = $tries;
        if ($tries >= 5) { $_SESSION['lock_until'] = time() + 60; $_SESSION['tries'] = 0; }
        audit('login_failed', trim($_POST['username'] ?? ''));
        $error = ($u && !$u['is_active']) ? 'This account has been deactivated. Please contact us.' : 'Incorrect username or password.';
    }
}
page_head('Login');
?>
<div class="authwrap"><div class="card authcard">
  <h1 style="font-size:2rem">Welcome back</h1>
  <p class="muted">Please enter your credentials to access your account.</p>
  <?php if (!empty($_GET['timeout'])): ?><div class="alert alert-warn">You were signed out due to inactivity.</div><?php endif; ?>
  <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post" data-validate novalidate>
    <?= csrf_field() ?>
    <div class="field"><label class="req" for="username">Username</label><input id="username" name="username" type="text" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>" autofocus></div>
    <div class="field"><label class="req" for="password">Password</label>
      <div class="pwwrap"><input id="password" name="password" type="password" required autocomplete="current-password"><button class="pwtoggle" type="button" aria-pressed="false">Show</button></div></div>
    <p style="text-align:right;margin:-.4rem 0 1rem"><a href="<?= e(url('forgot.php')) ?>">Forgot password?</a></p>
    <button class="btn btn-primary btn-block" type="submit">Log in</button>
  </form>
  <p class="center" style="margin:1.2rem 0 0">Don't have an account? <a href="<?= e(url('register.php')) ?>">Create an account</a></p>
</div></div>
<?php page_foot(); ?>
