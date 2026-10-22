<?php
require_once __DIR__ . '/includes/layout.php';
$topics = ['quote' => 'Free quote', 'schedule' => 'Schedule a service', 'general' => 'General inquiry'];
$topic = $_POST['topic'] ?? ($_GET['topic'] ?? 'general');
$errors = []; $u = me();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $msg = trim($_POST['message'] ?? '');
    if ($name === '') $errors['name'] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    if ($phone !== '' && !valid_phone($phone)) $errors['phone'] = 'Use a PH mobile number: 09XXXXXXXXX or +639XXXXXXXXX.';
    if ($msg === '') $errors['message'] = 'Please tell us how we can help.';
    if (empty($_POST['privacy_consent'])) $errors['privacy'] = 'Please accept the Data Privacy consent.';
    if (!isset($topics[$topic])) $topic = 'general';
    if (!$errors) {
        q('INSERT INTO contact_messages (name,email,phone,topic,message) VALUES (?,?,?,?,?)', [$name, $email, $phone ?: null, $topics[$topic], $msg]);
        audit('contact_message', $topics[$topic] . ' from ' . $email);
        flash('success', 'Thank you! We received your message and will reply within one business day.');
        redirect('contact.php');
    }
}
$v = fn($k, $d = '') => e($_POST[$k] ?? $d);
page_head('Contact');
?>
<div class="page-title"><div class="container"><h1>Contact Us</h1><p>Request a free quote, schedule a service or ask us anything.</p></div></div>
<section class="section"><div class="container grid g2" style="align-items:start">
  <form class="card" method="post" data-validate novalidate>
    <?= csrf_field() ?>
    <?php if ($errors): ?><div class="alert alert-error" role="alert">Please fix the highlighted fields.</div><?php endif; ?>
    <div class="field"><label for="topic">I would like to</label><select id="topic" name="topic"><?php foreach ($topics as $k => $l): ?><option value="<?= e($k) ?>"<?= $topic === $k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select></div>
    <div class="formgrid">
      <div class="field<?= isset($errors['name']) ? ' invalid' : '' ?>"><label class="req" for="name">Full name</label><input id="name" name="name" type="text" required value="<?= $v('name', $u ? $u['first_name'] . ' ' . $u['last_name'] : '') ?>"><?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?></div>
      <div class="field<?= isset($errors['email']) ? ' invalid' : '' ?>"><label class="req" for="email">Email</label><input id="email" name="email" type="email" required value="<?= $v('email', $u['email'] ?? '') ?>"><?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?></div>
    </div>
    <div class="field<?= isset($errors['phone']) ? ' invalid' : '' ?>"><label for="phone">Phone (optional)</label><input id="phone" name="phone" type="tel" placeholder="09XXXXXXXXX" value="<?= $v('phone', $u['phone'] ?? '') ?>"><?php if (isset($errors['phone'])): ?><span class="err"><?= e($errors['phone']) ?></span><?php endif; ?></div>
    <div class="field<?= isset($errors['message']) ? ' invalid' : '' ?>"><label class="req" for="message">Message</label><textarea id="message" name="message" required><?= $v('message', !empty($_GET['product']) ? 'I am interested in: ' . $_GET['product'] : '') ?></textarea><?php if (isset($errors['message'])): ?><span class="err"><?= e($errors['message']) ?></span><?php endif; ?></div>
    <div class="field"><label class="check"><input type="checkbox" name="privacy_consent" value="1"<?= !empty($_POST['privacy_consent']) ? ' checked' : '' ?>><span>I agree to the processing of my personal data as described in the <a href="<?= e(url('privacy.php')) ?>" target="_blank">Privacy Policy</a>.</span></label><?php if (isset($errors['privacy'])): ?><span class="err" style="color:var(--bad)"><?= e($errors['privacy']) ?></span><?php endif; ?></div>
    <button class="btn btn-primary" type="submit">Send message</button>
  </form>
  <aside>
    <div class="card"><h2 style="font-size:1.3rem">Pasig City Office</h2>
      <address style="font-style:normal;line-height:1.9"><?= e(setting('company_address')) ?><br>
        <a href="tel:<?= e(preg_replace('/\s/', '', setting('company_phone'))) ?>"><?= e(setting('company_phone')) ?></a><br>
        <a href="mailto:<?= e(setting('company_email')) ?>"><?= e(setting('company_email')) ?></a></address>
      <p class="small muted">Mon–Fri 8:00 AM–5:00 PM · Sat 8:00 AM–12:00 NN</p></div>
    <div class="card" style="margin-top:1rem"><h3>Already a customer?</h3><p>Log in to book, reschedule and track your services.</p><a class="btn btn-ghost" href="<?= e(url('login.php')) ?>">Login</a></div>
  </aside>
</div></section>
<?php page_foot(); ?>
