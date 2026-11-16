<!doctype html><html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= out($data['title'] ?? 'Meatlab | Fresh Meat Market') ?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL ?>images/favicon-32x32.png" />
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/meatlab.css"></head>
<body>
 <?php $auth = $data['auth'] ?? ''; $count = isset($_SESSION['cart']['items']) ? count($_SESSION['cart']['items']) : 0; ?>
<header class="topbar<?= !empty($data['light']) ? ' page-light' : '' ?>"><div class="container bar">
<a class="logo" href="<?= BASE_URL ?>">
  <!--<span class="logo-icon"><i class="fa-solid fa-drumstick-bite"></i></span><span class="logo-text">Meatlab</span>-->
  <img src="<?= BASE_URL ?>images/logo.png" width="100%" height="45px" alt="">
</a>

  <nav class="pill-nav"><a href="<?= BASE_URL ?>">Home</a><a href="<?= BASE_URL ?>shops">Meat Shops</a><a href="<?= BASE_URL ?>#features">Features</a><a href="#contact">Contact</a></nav>
  <div class="bar-actions">
    <a class="cart-link" href="<?= BASE_URL ?>cart" aria-label="Cart"><i class="fa-solid fa-cart-shopping"></i><?php if ($count): ?><b><?= $count ?></b><?php endif; ?></a>
    <?php if ($auth === 'login'): ?><a class="btn btn-dark" href="<?= BASE_URL ?>owners/register">Create Account <i class="fa-solid fa-user-plus"></i></a>
    <?php else: ?>
      <a class="btn btn-dark" href="<?= BASE_URL ?>owners/login"> 
        <?php if (!empty($user['name']) ): ?>
          <?= ml_initials($user['name']) ?><i class="fa-solid fa-user"></i>
        <?php else: ?>
          Log In <i class="fa-solid fa-user"></i>

        <?php endif; ?>
      </a>
      <?php endif; ?>
  </div>
</div></header> 
<main>
  <?= Template::display($data) ?>
</main>
<footer class="footer" id="contact"><div class="container">
  <div class="foot-grid">
    <div>
      <a class="logo" href="<?= BASE_URL ?>">
        <!--<span class="logo-icon"><i class="fa-solid fa-drumstick-bite"></i></span><span class="logo-text">Meatlab</span>-->
        <img src="<?= BASE_URL ?>images/logo.png" width="135px" height="45px" alt="">
      </a>
    <p style="margin-top:1rem">Find trusted meat shops near you and order fresh, verified cuts delivered to your door.</p>
      <div class="social"><a href="#"><i class="fa-brands fa-instagram"></i></a><a href="#"><i class="fa-brands fa-facebook-f"></i></a><a href="#"><i class="fa-brands fa-x-twitter"></i></a><a href="#"><i class="fa-brands fa-whatsapp"></i></a></div></div>
    <div><h4>Explore</h4><a href="<?= BASE_URL ?>">Home</a><a href="<?= BASE_URL ?>shops">Find a Shop</a><a href="<?= BASE_URL ?>#features">Features</a></div>
    <div><h4>Shop Owners</h4><a href="<?= BASE_URL ?>owners/login">Log In</a><a href="<?= BASE_URL ?>owners/register">Create Account</a><a href="<?= BASE_URL ?>owners/forgot">Forgot Password</a></div>
    <div><h4>Get in Touch</h4><p><i class="fa-solid fa-location-dot"></i> Lagos, Nigeria</p><p><i class="fa-solid fa-phone"></i> +234 8188373898</p><p><i class="fa-solid fa-envelope"></i> hello@meatlab.com</p><p><i class="fa-solid fa-lock"></i> Payments secured by Paystack</p></div>
  </div>
  <div class="foot-base">&copy; <?= date('Y') ?> Meatlab. All rights reserved.</div>
</div></footer>
<script src="<?= BASE_URL ?>js/meatlab.js"></script></body></html>
