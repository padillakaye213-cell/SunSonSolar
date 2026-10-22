<?php
require_once __DIR__ . '/includes/layout.php';
$slug = $_GET['p'] ?? '';
if ($slug) {
    $post = q('SELECT * FROM posts WHERE slug=?', [$slug])->fetch();
    if (!$post) { http_response_code(404); page_head('Not found'); echo '<div class="container section"><h1>Article not found</h1><a href="' . e(url('blog.php')) . '">Back to Blog &amp; News</a></div>'; page_foot(); exit; }
    page_head($post['title'], ['desc' => $post['excerpt']]);
    ?>
    <div class="page-title"><div class="container"><div class="crumbs"><a href="<?= e(url('blog.php')) ?>">Blog &amp; News</a></div><h1><?= e($post['title']) ?></h1><p><?= e(ucfirst($post['category'])) ?> · <?= e(dt($post['published_at'], 'F j, Y')) ?></p></div></div>
    <section class="section"><div class="container prose">
      <?php foreach (preg_split('/\R{2,}/', $post['body']) as $para): ?><p><?= nl2br(e($para)) ?></p><?php endforeach; ?>
      <a class="btn btn-ghost" href="<?= e(url('blog.php')) ?>">← All articles</a>
    </div></section>
    <?php page_foot(); exit;
}
$f = $_GET['c'] ?? '';
$posts = $f ? q('SELECT * FROM posts WHERE category=? ORDER BY published_at DESC', [$f])->fetchAll() : q('SELECT * FROM posts ORDER BY published_at DESC')->fetchAll();
page_head('Blog & News');
?>
<div class="page-title"><div class="container"><h1>Blog &amp; News</h1><p>Guides, company updates and solar news for Filipino homes and businesses.</p></div></div>
<section class="section"><div class="container">
  <nav class="chips"><a class="chip<?= !$f ? ' active' : '' ?>" href="<?= e(url('blog.php')) ?>">All</a><a class="chip<?= $f === 'blog' ? ' active' : '' ?>" href="<?= e(url('blog.php?c=blog')) ?>">Blog</a><a class="chip<?= $f === 'news' ? ' active' : '' ?>" href="<?= e(url('blog.php?c=news')) ?>">News</a></nav>
  <div class="grid g2">
    <?php foreach ($posts as $p): ?>
    <article class="card"><span class="tag"><?= e(ucfirst($p['category'])) ?></span> <time class="small muted" datetime="<?= e($p['published_at']) ?>"><?= e(dt($p['published_at'], 'M j, Y')) ?></time>
      <h2 style="font-size:1.3rem;margin-top:.4rem"><a href="<?= e(url('blog.php?p=' . $p['slug'])) ?>" style="text-decoration:none"><?= e($p['title']) ?></a></h2>
      <p><?= e($p['excerpt']) ?></p><a href="<?= e(url('blog.php?p=' . $p['slug'])) ?>">Read more →</a></article>
    <?php endforeach; ?>
  </div>
</div></section>
<?php page_foot(); ?>
