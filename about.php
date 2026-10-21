<?php
require_once __DIR__ . '/includes/layout.php';
$tms = q('SELECT * FROM testimonials ORDER BY id')->fetchAll();
page_head('About Us');
?>
<div class="page-title"><div class="container"><h1>About Sun Son Solar</h1><p>A Pasig City company built on a simple idea: clean energy should be easy, affordable and personal.</p></div></div>
<section class="section">
  <div class="container grid g2" style="align-items:start">
    <div class="prose">
      <span class="eyebrow">Our story</span>
      <h2>Two founders, one sunny mission</h2>
      <p>Sun Son Solar began when Kat and Sonny saw neighbours in Metro Manila struggling with rising electricity bills and unreliable power. They set out to build a company that explains solar in plain language, installs it properly and stays around afterward.</p>
      <p>Today our team of designers, certified technicians and support staff serve homes, shops and small factories across the region — and every customer still gets a real person to call.</p>
    </div>
    <div class="grid" style="grid-template-columns:1fr 1fr">
      <article class="card center"><div class="avatar" style="width:84px;height:84px;margin:0 auto .8rem;font-size:1.8rem">K</div><h3>Kat</h3><p class="small muted">Co-founder · Operations &amp; Customer Experience</p></article>
      <article class="card center"><div class="avatar" style="width:84px;height:84px;margin:0 auto .8rem;font-size:1.8rem">S</div><h3>Sonny</h3><p class="small muted">Co-founder · Engineering &amp; Installations</p></article>
    </div>
  </div>
</section>
<section class="section alt">
  <div class="container grid g3">
    <article class="card"><h3>Our mission</h3><p>Make solar power simple, reliable and within reach of every Filipino household and business.</p></article>
    <article class="card"><h3>Our promise</h3><p>Honest quotes, quality components and workmanship we stand behind.</p></article>
    <article class="card"><h3>Our values</h3><p>Safety first, transparency always, and service that does not end at installation.</p></article>
  </div>
</section>
<section class="section">
  <div class="container grid g2" style="align-items:start">
    <div>
      <span class="eyebrow">Visit us</span><h2>Pasig City Office</h2>
      <address style="font-style:normal;line-height:1.9">
        <?= e(setting('company_address', 'Pasig City, Metro Manila, Philippines')) ?><br>
        Phone: <a href="tel:<?= e(preg_replace('/\s/', '', setting('company_phone'))) ?>"><?= e(setting('company_phone')) ?></a><br>
        Email: <a href="mailto:<?= e(setting('company_email')) ?>"><?= e(setting('company_email')) ?></a><br>
        Hours: Mon–Fri 8:00 AM–5:00 PM · Sat 8:00 AM–12:00 NN
      </address>
      <a class="btn btn-primary" href="<?= e(url('contact.php?topic=schedule')) ?>">Schedule a visit</a>
    </div>
    <div class="card" style="padding:0;overflow:hidden"><iframe title="Map of Pasig City" loading="lazy" style="border:0;width:100%;height:300px" src="https://www.openstreetmap.org/export/embed.html?bbox=121.0300%2C14.5300%2C121.1200%2C14.6200&amp;layer=mapnik"></iframe></div>
  </div>
</section>
<section class="section sky" id="stories">
  <div class="container">
    <div class="section-head center"><span class="eyebrow" style="color:var(--navy)">Testimonials &amp; success stories</span><h2>What our customers say</h2></div>
    <div class="grid g2">
      <?php foreach ($tms as $t): ?>
      <figure class="card quote" style="margin:0"><?= stars((int)$t['rating']) ?><p>“<?= e($t['quote']) ?>”</p><cite><?= e($t['customer_name']) ?></cite><small><?= e($t['location']) ?></small></figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php page_foot(); ?>
