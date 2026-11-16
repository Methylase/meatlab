<section class="page-pad"><div class="container">
  <h1 class="h2">Meat shops <em><?= $q ? 'near ' . out($q) : 'on Meatlab' ?></em></h1>
  <form class="searchbox" style="margin:1.5rem 0 2.5rem" action="<?= BASE_URL ?>shops" method="get" ><input name="q" value="<?= out($q) ?>" placeholder="Search address or shop name"><button class="btn btn-red">Search <i class="fa-solid fa-magnifying-glass"></i></button></form>
  <div class="grid">
  <?php foreach ($shops as $s): ?>
    <a class="card shop-card" href="<?= BASE_URL ?>shops/show/<?= out($s->slug) ?>">
    <?php if($s->banner): ?>
      <img class="img-banner" src="<?= out(ml_img('banners', $s->banner)) ?>" alt="">
    <?php else: ?>  
      <div class="thumb"><i class="fa-solid fa-store"></i></div>
    <?php endif ?>
      
      <h3><?= out($s->shop_name) ?></h3><div class="rate"><i class="fa-solid fa-star"></i> <?= out($s->rating) ?></div>
      <p><i class="fa-solid fa-location-dot"></i> <?= out($s->address) ?></p>
    </a>
  <?php endforeach; if (!$shops) echo '<p>No shops found. Try another area.</p>'; ?>
  </div></div></section>
