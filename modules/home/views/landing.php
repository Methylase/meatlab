<section class="hero" data-carousel>
 <div class="slides">
  <div class="slide"><div class="container"><div>
    <div class="eyebrow">Fresh Meat</div><div class="mega">Market</div>
    <p class="lead">Order farm-fresh beef, chicken and goat from verified butchers around you.</p>
    <a class="btn btn-white" href="#find">Find a Shop <i class="fa-solid fa-magnifying-glass"></i></a></div>
    <div class="art"><i class="fa-solid fa-drumstick-bite"></i></div></div></div>
  <div class="slide light"><div class="container"><div>
    <div class="mega">Premium<br><em>Meat</em></div>
    <p class="lead">Hand-cut, hygienically processed and delivered cold to your door.</p>
    <a class="btn btn-red" href="<?= BASE_URL ?>shops">Shop Now</a></div>
    <div class="art"><i class="fa-solid fa-bacon"></i></div></div></div>
  <?php foreach ($shops as $s): ?>
  <div class="slide shop"><div class="container"><div>
    <span class="chip"><i class="fa-solid fa-circle-check"></i> Verified Shop</span>
    <div class="mega" style="font-size:clamp(2.6rem,7vw,6rem)"><?= out($s->shop_name) ?></div>
    <div class="rate"><?php for($i=0;$i<5;$i++) echo '<i class="fa-solid fa-star"></i>'; ?> <?= out($s->rating) ?></div>
    <p class="lead"><?= out($s->tagline) ?><br><i class="fa-solid fa-location-dot"></i> <?= out($s->address) ?></p>
    <a class="btn btn-white" href="<?= BASE_URL ?>shops/show/<?= out($s->slug) ?>">Visit Shop <i class="fa-solid fa-arrow-right"></i></a></div>
    <div class="art"><i class="fa-solid fa-store"></i></div></div></div>
  <?php endforeach; ?>
 </div>
 <div class="dots"></div>
 <div class="hero-stats"><div class="container">
   <div class="stat"><b>4.9 <i class="fa-solid fa-star" style="color:#ffc928"></i></b>Customer Rating</div>
   <div><span class="chip"><i class="fa-solid fa-circle-check"></i> Own Production</span><span class="chip"><i class="fa-solid fa-award"></i> Verified Quality</span></div>
   <div class="stat"><b>5K+</b>Satisfied Customers</div></div></div>
</section>

<section class="section gray" id="find"><div class="container">
  <h2 class="h2">Find a <em>meat shop</em> near you</h2>
  <p class="sub">Enter your address or area and see every meat shop that delivers to you.</p>
  <form class="searchbox" action="<?= BASE_URL ?>shops" method="get">
    <button type="button" class="locate" id="locate" aria-label="Use my location"><i class="fa-solid fa-location-crosshairs"></i></button>
    <input id="q" name="q" placeholder="Enter your address, e.g. Ikeja, Lagos" required>
    <button class="btn btn-red" type="submit">Search <i class="fa-solid fa-magnifying-glass"></i></button>
  </form></div></section>

<section class="section" id="features"><div class="container">
  <h2 class="h2">Why <em>Meatlab</em></h2><p class="sub">Everything you need to buy meat you can trust.</p>
  <div class="grid">
   <?php foreach ([
    ['fa-location-dot','Shops Near You','Search by address and compare nearby butchers instantly.'],
    ['fa-scale-balanced','Buy Your Way','Choose exact weight or just type the naira amount you want.'],
    ['fa-shield-heart','Verified Quality','Every shop is vetted for hygiene and freshness.'],
    ['fa-truck-fast','Fast Delivery','Fast delivery straight to your doorstep.'],
    ['fa-credit-card','Secure Payments','Pay safely with card, transfer or USSD via Paystack.'],
    ['fa-store','Built for Shops','Owners get their own storefront with banners and categories.']] as $f): ?>
   <div class="card feat"><i class="fa-solid <?= $f[0] ?>"></i><h3><?= $f[1] ?></h3><p><?= $f[2] ?></p></div>
   <?php endforeach; ?></div></div></section>

<section class="section" style="padding-top:0"><div class="container"><div class="cta">
  <div><h2 class="h2">Own a meat shop?</h2><p style="margin-top:.5rem">Create your storefront and start taking orders today.</p></div>
  <a class="btn btn-white" href="<?= BASE_URL ?>owners/register">Create Account</a></div></div></section>
