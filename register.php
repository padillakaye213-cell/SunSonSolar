<?php
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/userform.php';
if (me()) redirect(home_for(me()['role']));

$err = []; $v = $_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $err = validate_user($_POST, false);
    if (empty($_POST['privacy_consent'])) $err['privacy'] = 'You must accept the Data Privacy consent to create an account.';
    if (!$err) {
        $id = insert_user($_POST, 'customer', null);
        $_SESSION['uid'] = $id; $_SESSION['last'] = time(); session_regenerate_id(true);
        audit('register', 'Customer self-registered: ' . $_POST['username']);
        q('INSERT INTO notifications (user_id,audience,type,title,body) VALUES (?,?,?,?,?)', [$id, 'user', 'system', 'Welcome to Sun Son Solar!', 'Your account is ready. Book a free consultation from the Schedule tab.']);
        flash('success', 'Welcome, ' . trim($_POST['first_name']) . '! Your account has been created.');
        redirect('customer/index.php');
    }
}
page_head('Create your account');
?>
<div class="authwrap"><div class="card authcard wide">
  <h1 style="font-size:2rem">Create your account</h1>
  <p class="muted">Already have an account? <a href="<?= e(url('login.php')) ?>">Log in</a>.</p>
  <form method="post" data-validate novalidate>
    <?= csrf_field() ?>
    <?php if ($err): ?><div class="alert alert-error" role="alert">Please fix the highlighted fields below.</div><?php endif; ?>
    <?php user_fields($v, $err, false, true); ?>
    <div class="field"><label class="check"><input type="checkbox" name="privacy_consent" value="1"<?= !empty($_POST['privacy_consent']) ? ' checked' : '' ?>><span>I consent to the collection and processing of my personal data in accordance with the <a href="<?= e(url('privacy.php')) ?>" target="_blank">Privacy Policy</a> and the Data Privacy Act of 2012. <span style="color:var(--bad)">*</span></span></label>
      <?php if (isset($err['privacy'])): ?><span class="err" style="color:var(--bad)" role="alert"><?= e($err['privacy']) ?></span><?php endif; ?></div>
    <button class="btn btn-primary btn-block" type="submit">Sign Up</button>
  </form>
</div></div>
<?php page_foot(); ?>
