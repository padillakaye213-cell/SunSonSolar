<?php
require_once __DIR__ . '/includes/layout.php';
$p = q('SELECT p.*, c.name cat_name, c.slug cat_slug FROM products p JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.is_active=1', [(int)($_GET['id'] ?? 0)])->fetch();
if (!$p) { http_response_code(404); page_head('Product not found'); echo '<div class="container section"><h1>Product not found</h1><p><a href="' . e(url('products.php')) . '">Back to products</a></p></div>'; page_foot(); exit; }
$related = q('SELECT p.*, c.slug cat_slug FROM products p JOIN categories c ON c.id=p.category_id WHERE p.category_id=? AND p.id<>? AND p.is_active=1 LIMIT 4', [$p['category_id'], $p['id']])->fetchAll();
page_head($p['name'], ['desc' => $p['short_desc']]);
?>
<div class="page-title"><div class="container">
  <div class="crumbs"><a href="<?= e(url('products.php')) ?>">Products</a> › <a href="<?= e(url('products.php?cat=' . $p['cat_slug'])) ?>"><?= e($p['cat_name']) ?></a></div>
  <h1><?= e($p['name']) ?></h1></div></div>
<section class="section">
  <div class="container grid g2" style="align-items:start">
    <div class="card" style="padding:0;overflow:hidden"><div style="aspect-ratio:4/3"><?= product_art($p['cat_slug'], $p['image'], $p['name']) ?></div></div>
    <div>
      <span class="eyebrow"><?= e($p['brand'] ?: $p['cat_name']) ?></span>
      <p style="font-size:1.1rem"><?= e($p['short_desc']) ?></p>
      <p style="font-size:2rem;font-weight:800;color:var(--navy);margin:.2rem 0"><?= $p['price'] !== null ? money($p['price']) : 'Request quote' ?></p>
      <p><?= $p['stock'] > 0 ? '<span class="badge badge-ok">In stock</span>' : '<span class="badge badge-warn">Made to order</span>' ?></p>
      <p><?= e($p['long_desc']) ?></p>
      <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin:1.2rem 0">
        <a class="btn btn-primary" href="<?= e(url('contact.php?topic=quote&product=' . urlencode($p['name']))) ?>"><?= $p['price'] !== null ? 'Ask about this product' : 'Request a quote' ?></a>
        <a class="btn btn-ghost" href="<?= e(url('products.php')) ?>">Back to catalog</a>
      </div>
      <?php if ($p['specs']): ?>
      <h2 style="font-size:1.2rem">Specifications</h2>
      <table class="specs"><tbody>
        <?php foreach (preg_split('/\R/', $p['specs']) as $line): if (!str_contains($line, ':')) continue; [$k, $v] = array_map('trim', explode(':', $line, 2)); ?>
        <tr><th scope="row"><?= e($k) ?></th><td><?= e($v) ?></td></tr>
        <?php endforeach; ?>
      </tbody></table>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php if ($related): ?>
<section class="section alt"><div class="container">
  <h2>You may also like</h2>
  <div class="grid g4">
    <?php foreach ($related as $r): ?>
    <article class="card pcard"><a class="art" href="<?= e(url('product.php?id=' . $r['id'])) ?>" tabindex="-1" aria-hidden="true"><?= product_art($r['cat_slug'], $r['image'], $r['name']) ?></a>
      <div class="body"><h3><a href="<?= e(url('product.php?id=' . $r['id'])) ?>"><?= e($r['name']) ?></a></h3>
      <div class="price<?= $r['price'] === null ? ' quote' : '' ?>"><?= $r['price'] !== null ? money($r['price']) : 'Request quote' ?></div></div></article>
    <?php endforeach; ?>
  </div></div></section>
<?php endif; ?>
<?php page_foot(); ?>
