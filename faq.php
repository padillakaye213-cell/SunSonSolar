<?php
require_once __DIR__ . '/includes/layout.php';
$faqs = q('SELECT * FROM faqs ORDER BY sort_order, id')->fetchAll();
page_head('Frequently Asked Questions');
?>
<div class="page-title"><div class="container"><h1>Frequently Asked Questions</h1><p>Quick answers about costs, installation, maintenance and your account.</p></div></div>
<section class="section"><div class="container prose">
  <?php foreach ($faqs as $f): ?>
  <details class="faq"><summary><?= e($f['question']) ?></summary><p><?= e($f['answer']) ?></p></details>
  <?php endforeach; ?>
  <div class="card" style="margin-top:2rem"><h3>Still have questions?</h3><p>Our team is happy to help.</p><a class="btn btn-primary" href="<?= e(url('contact.php')) ?>">Contact us</a></div>
</div></section>
<?php page_foot(); ?>
