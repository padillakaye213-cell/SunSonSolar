<?php
require_once __DIR__ . '/includes/layout.php';
$all = q('SELECT * FROM services WHERE is_active=1 ORDER BY sort_order')->fetchAll();
$main = array_filter($all, fn($s) => $s['is_featured']);
$rest = array_filter($all, fn($s) => !$s['is_featured']);
page_head('Services', ['desc' => 'Consultation, design, permitting, installation, maintenance, repair and monitoring.']);
?>
<div class="page-title"><div class="container"><h1>Our Services</h1><p>One team for the whole journey — from your first question to years of worry-free performance.</p></div></div>
<section class="section">
  <div class="container">
    <div class="section-head"><span class="eyebrow">Most requested</span><h2>Start here</h2></div>
    <div class="grid g3">
      <?php foreach ($main as $s): ?>
      <article class="card highlight service-card">
        <span class="tag" style="background:var(--sky);color:var(--navy)">Core service</span>
        <h3 style="font-size:1.4rem"><?= e($s['name']) ?></h3>
        <p><?= e($s['description']) ?></p>
        <p><strong style="color:var(--white)"><?= $s['base_price'] === null ? 'Custom quote' : ((float)$s['base_price'] == 0 ? 'Free' : 'From ' . money($s['base_price'])) ?></strong></p>
        <a class="btn btn-light btn-sm" href="<?= e(url(me() ? 'customer/index.php?tab=schedule' : 'contact.php?topic=schedule')) ?>">Book now</a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section alt">
  <div class="container">
    <div class="section-head"><span class="eyebrow">Complete support</span><h2>Everything else you need</h2></div>
    <div class="grid g4">
      <?php foreach ($rest as $s): ?>
      <article class="card service-card"><h3><?= e($s['name']) ?></h3><p class="small muted"><?= e($s['description']) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section">
  <div class="container prose">
    <h2>How it works</h2>
    <ol>
      <li><strong>Consult.</strong> We assess your roof, usage and budget — free.</li>
      <li><strong>Design &amp; permit.</strong> You approve the layout; we file the paperwork.</li>
      <li><strong>Install.</strong> Certified technicians build and test your system.</li>
      <li><strong>Maintain &amp; monitor.</strong> Scheduled care and live performance tracking.</li>
    </ol>
    <a class="btn btn-primary" href="<?= e(url('contact.php?topic=quote')) ?>">Request a Free Quote</a>
  </div>
</section>
<?php page_foot(); ?>
