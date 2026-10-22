<?php
require_once __DIR__ . '/includes/layout.php';
$tms = q('SELECT * FROM testimonials ORDER BY id LIMIT 3')->fetchAll();
$featured = q('SELECT * FROM services WHERE is_featured=1 AND is_active=1 ORDER BY sort_order')->fetchAll();
page_head('Power Your Future with Solar', ['no_flash' => false]);
?>
<section class="hero">
  <div class="container">
    <div>
      <span class="eyebrow" style="color:var(--sky)">Pasig City · Metro Manila</span>
      <h1>Power Your Future with Solar</h1>
      <p class="lead">Cut your electricity bill, protect against brownouts and take control of your energy with a system designed and installed by Sun Son Solar.</p>
      <div class="actions">
        <a class="btn btn-light" href="<?= e(url('contact.php?topic=quote')) ?>">Request a Free Quote</a>
        <a class="btn btn-ghost on-dark" href="<?= e(url('contact.php?topic=schedule')) ?>">Schedule a Service</a>
      </div>
    </div>
    <div class="hero-card" aria-label="Typical results">
      <div class="stat"><span>Typical bill reduction</span><b>Up to 80%</b></div>
      <div class="stat"><span>Panel performance warranty</span><b>25 years</b></div>
      <div class="stat"><span>Typical payback</span><b>4–6 years</b></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center"><span class="eyebrow">Why go solar</span><h2>Benefits that add up every month</h2></div>
    <div class="grid g3">
      <article class="card feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 2v20M17 6H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div><h3>Cost savings</h3><p>Generate your own power and slash your monthly electricity bill. Most customers recover their investment within a few years, then enjoy free energy for decades.</p></article>
      <article class="card feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 019.8 6.1C15.5 5 17 4.5 19 2c1 2 2 4.5 2 8 0 5.5-4.8 10-10 10z"/><path d="M2 21c0-3 1.9-5.5 6-7"/></svg></div><h3>Sustainability</h3><p>Every kilowatt-hour from the sun avoids fossil-fuel emissions. Lower your household or business carbon footprint without lowering your comfort.</p></article>
      <article class="card feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h8l-1 8 10-12h-8z"/></svg></div><h3>Energy independence</h3><p>Pair solar with a battery to keep the lights on during outages and shield yourself from rising utility rates.</p></article>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-head center"><span class="eyebrow">What we do</span><h2>From first consultation to lifetime care</h2></div>
    <div class="grid g3">
      <?php foreach ($featured as $s): ?>
      <article class="card highlight"><span class="tag" style="background:var(--sky);color:var(--navy)">Core service</span><h3><?= e($s['name']) ?></h3><p><?= e($s['description']) ?></p></article>
      <?php endforeach; ?>
    </div>
    <p class="center" style="margin-top:1.5rem"><a class="btn btn-ghost" href="<?= e(url('services.php')) ?>">See all services</a></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center"><span class="eyebrow">Shop</span><h2>Quality components, honest pricing</h2></div>
    <div class="grid g4">
      <?php foreach (q('SELECT * FROM categories ORDER BY id')->fetchAll() as $c): ?>
      <a class="card center" style="text-decoration:none" href="<?= e(url('products.php?cat=' . $c['slug'])) ?>">
        <div style="width:90px;margin:0 auto .6rem;border-radius:16px;overflow:hidden"><?= product_art($c['slug'], null, $c['name']) ?></div>
        <h3><?= e($c['name']) ?></h3></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section sky">
  <div class="container">
    <div class="section-head center"><span class="eyebrow" style="color:var(--navy)">Success stories</span><h2>Trusted by homes and businesses</h2></div>
    <div class="grid g3">
      <?php foreach ($tms as $t): ?>
      <figure class="card quote" style="margin:0"><?= stars((int)$t['rating']) ?><p>“<?= e($t['quote']) ?>”</p><cite><?= e($t['customer_name']) ?></cite><small><?= e($t['location']) ?></small></figure>
      <?php endforeach; ?>
    </div>
    <p class="center" style="margin-top:1.5rem"><a class="btn btn-primary" href="<?= e(url('about.php#stories')) ?>">Read more stories</a></p>
  </div>
</section>

<section class="cta-band">
  <h2>Ready to see how much you can save?</h2>
  <p>Book a free consultation — no obligation.</p>
  <a class="btn btn-light" href="<?= e(url('contact.php?topic=quote')) ?>">Request a Free Quote</a>
  <a class="btn btn-ghost on-dark" href="<?= e(url('register.php')) ?>">Create an account</a>
</section>
<?php page_foot(); ?>
