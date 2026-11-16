<?php $icons = []; foreach ($cats as $c) $icons[$c->id] = $c->icon; ?>
<section class="hero shop-hero" data-carousel><div class="slides">
  <div class="slide"><div class="container"><div>
    <span class="chip"><i class="fa-solid fa-circle-check"></i> Verified Shop</span>
    <div class="eyebrow"><?= out($shop->city) ?> Meat Shop</div><div class="mega" style="font-size:clamp(2.8rem,8vw,7rem)"><?= out($shop->shop_name) ?></div>
    <p class="lead"><?= out($shop->tagline) ?><br><i class="fa-solid fa-location-dot"></i> <?= out($shop->address) ?></p>
    <a class="btn btn-white" href="#products">Shop Now <i class="fa-solid fa-cart-shopping"></i></a> <a class="btn btn-red" href="tel:<?= out($shop->phone) ?>"><i class="fa-solid fa-phone"></i> Call</a></div>
    <div class="art"><i class="fa-solid fa-store"></i></div></div></div>
  <?php foreach (array_slice($top, 0, 3) as $p): ?>
  <div class="slide light"><div class="container"><div>
    <span class="chip" style="background:#e8202a;color:#fff">Top Product</span>
    <div class="mega" style="font-size:clamp(2.4rem,6vw,5.5rem)"><?= out($p->name) ?></div>
    <p class="lead"><?= out($p->description) ?> &mdash; <b class="price">&#8358;<?= number_format($p->price_per_kg) ?>/kg</b></p>
    <a class="btn btn-red" href="#p<?= $p->id ?>">Buy Now <i class="fa-solid fa-arrow-right"></i></a></div>
    <?php if($p->image): ?>
      <img src="<?= out(ml_img('products', $p->image)) ?>" alt="">
    <?php else: ?>  
      <div class="art"><i class="fa-solid <?= $icons[$p->category_id] ?? 'fa-drumstick-bite' ?>"></i></div>
    <?php endif ?>
  </div></div>
  <?php endforeach; ?>
</div><div class="dots"></div></section>

<section class="section gray"><div class="container"><h2 class="h2">Top <em>products</em></h2><div class="grid" style="margin-top:1.5rem">
  <?php foreach ($top as $p): ?><div class="card"><h3><?= out($p->name) ?></h3><p class="price">&#8358;<?= number_format($p->price_per_kg) ?>/kg</p><a class="btn btn-dark" style="margin-top:.8rem" href="#p<?= $p->id ?>">Buy <i class="fa-solid fa-arrow-up-right-from-square"></i></a></div><?php endforeach; ?></div></div></section>

<section class="section" id="products"><div class="container">
  <h2 class="h2">Our <em>products</em></h2><p class="sub">Pick your weight or type the amount you want to spend.</p>
  <div class="tabs"><button class="on" data-cat="all"><i class="fa-solid fa-border-all"></i> All</button>
  <?php foreach ($cats as $c): ?><button data-cat="<?= $c->id ?>">
    <i class="fa-solid <?= out($c->icon) ?>"></i> <?= out($c->name) ?></button><?php endforeach; ?></div>
  <div class="grid">
  <?php foreach ($products as $p): ?>
   <form class="card pcard" id="p<?= $p->id ?>" data-price="<?= $p->price_per_kg ?>" data-min="<?= $p->min_kg ?>" data-cat="<?= $p->category_id ?>" action="<?= BASE_URL ?>cart/add" method="post">
    <input type="hidden" name="product_id" value="<?= $p->id ?>">
    <?php if($p->image): ?>
      <img src="<?= out(ml_img('products', $p->image)) ?>" alt="">
    <?php else: ?>  
      <div class="pimg"><i class="fa-solid <?= $icons[$p->category_id] ?? 'fa-drumstick-bite' ?>"></i></div>
    <?php endif ?>
    <h3><?= out($p->name) ?></h3><p style="font-size:.9rem;color:#666"><?= out($p->description) ?></p><div class="price">&#8358;<?= number_format($p->price_per_kg) ?>/kg</div>
    <div class="custom"><div><label>Weight (kg)</label><div class="stepper"><button data-d="-0.5">&minus;</button><input name="kg" type="number" step="0.1" min="<?= $p->min_kg ?>" value="1"><button data-d="0.5">+</button></div></div>
      <div><label>Amount (&#8358;)</label><input name="amount" type="number" step="100" min="0"></div></div>
    <button class="btn btn-red btn-block"><i class="fa-solid fa-cart-plus"></i> Add to cart</button></form>
  <?php endforeach; ?></div></div></section>
