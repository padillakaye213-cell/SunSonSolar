<?php
require_once __DIR__ . '/includes/layout.php';

$cats = q('SELECT * FROM categories ORDER BY id')->fetchAll();
$cat = $_GET['cat'] ?? '';
$search = trim($_GET['q'] ?? '');
$brand = $_GET['brand'] ?? '';
$price = $_GET['price'] ?? '';
$sort = $_GET['sort'] ?? 'name';

$where = ['p.is_active=1']; $args = [];
if ($cat !== '') { $where[] = 'c.slug=?'; $args[] = $cat; }
if ($search !== '') { $where[] = '(p.name LIKE ? OR p.short_desc LIKE ? OR p.brand LIKE ?)'; array_push($args, "%$search%", "%$search%", "%$search%"); }
if ($brand !== '') { $where[] = 'p.brand=?'; $args[] = $brand; }
if ($price === 'quote') $where[] = 'p.price IS NULL';
elseif ($price === 'u10') $where[] = 'p.price < 10000';
elseif ($price === '10-50') $where[] = 'p.price BETWEEN 10000 AND 50000';
elseif ($price === 'o50') $where[] = 'p.price > 50000';
$order = ['name' => 'p.name', 'low' => 'p.price IS NULL, p.price', 'high' => 'p.price DESC'][$sort] ?? 'p.name';

$rows = q('SELECT p.*, c.name cat_name, c.slug cat_slug FROM products p JOIN categories c ON c.id=p.category_id WHERE ' . implode(' AND ', $where) . " ORDER BY $order", $args)->fetchAll();
$brands = q('SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL ORDER BY brand')->fetchAll(PDO::FETCH_COLUMN);

page_head('Products', ['desc' => 'Browse solar panels, inverters, batteries, racking and mounting, and wires.']);
?>
<div class="page-title"><div class="container"><h1>Products</h1><p>Panels, inverters, batteries, racking and wires — everything for a complete solar system.</p></div></div>
<section class="section">
  <div class="container">
    <nav class="chips" aria-label="Categories">
      <a class="chip<?= $cat === '' ? ' active' : '' ?>" href="<?= e(url('products.php')) ?>">All</a>
      <?php foreach ($cats as $c): ?><a class="chip<?= $cat === $c['slug'] ? ' active' : '' ?>" href="<?= e(url('products.php?cat=' . $c['slug'])) ?>"><?= e($c['name']) ?></a><?php endforeach; ?>
    </nav>
    <form class="filters" method="get" role="search">
      <?php if ($cat): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
      <div class="f" style="flex:2"><label for="q">Search</label><input type="search" id="q" name="q" value="<?= e($search) ?>" placeholder="e.g. 550W, hybrid, lithium"></div>
      <div class="f"><label for="brand">Brand</label><select id="brand" name="brand"><option value="">All brands</option><?php foreach ($brands as $b): ?><option<?= $brand === $b ? ' selected' : '' ?>><?= e($b) ?></option><?php endforeach; ?></select></div>
      <div class="f"><label for="price">Price</label><select id="price" name="price">
        <?php foreach (['' => 'Any price', 'u10' => 'Under ₱10,000', '10-50' => '₱10,000 – ₱50,000', 'o50' => 'Over ₱50,000', 'quote' => 'Request quote only'] as $k => $v): ?><option value="<?= e($k) ?>"<?= $price === $k ? ' selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
      <div class="f"><label for="sort">Sort by</label><select id="sort" name="sort">
        <?php foreach (['name' => 'Name', 'low' => 'Price: low to high', 'high' => 'Price: high to low'] as $k => $v): ?><option value="<?= e($k) ?>"<?= $sort === $k ? ' selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?></select></div>
      <button class="btn btn-primary" type="submit">Apply</button>
      <a class="btn btn-ghost" href="<?= e(url('products.php')) ?>">Reset</a>
    </form>
    <p class="muted" aria-live="polite"><?= count($rows) ?> product<?= count($rows) === 1 ? '' : 's' ?> found</p>
    <?php if (!$rows): ?><div class="card center"><h3>No products match your filters.</h3><p>Try a different search or <a href="<?= e(url('contact.php?topic=quote')) ?>">ask us for a recommendation</a>.</p></div><?php endif; ?>
    <div class="grid g4">
      <?php foreach ($rows as $p): ?>
      <article class="card pcard">
        <a class="art" href="<?= e(url('product.php?id=' . $p['id'])) ?>" tabindex="-1" aria-hidden="true"><?= product_art($p['cat_slug'], $p['image'], $p['name']) ?></a>
        <div class="body">
          <span class="cat"><?= e($p['cat_name']) ?></span>
          <h3><a href="<?= e(url('product.php?id=' . $p['id'])) ?>"><?= e($p['name']) ?></a></h3>
          <p class="small muted"><?= e($p['short_desc']) ?></p>
          <?php if ($p['price'] !== null): ?><div class="price"><?= money($p['price']) ?></div><?php else: ?><div class="price quote">Request quote</div><?php endif; ?>
          <a class="btn btn-ghost btn-sm" href="<?= e(url('product.php?id=' . $p['id'])) ?>">View details</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php page_foot(); ?>
