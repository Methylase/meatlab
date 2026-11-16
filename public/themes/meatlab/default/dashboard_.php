<?php
$role = $role ?? 'owner';
$home = $role === 'admin' ? 'admin' : 'owner';
if ($role === 'admin') {
    $nav = [
        'home'     => ['Overview', 'admin'],
        'shops'    => ['Shops', 'admin/shops'],
        'products' => ['All products', 'admin/products'],
        'orders'   => ['All orders', 'admin/orders'],
        'settings' => ['Settings', 'admin/settings'],
    ];
} else {
    $nav = [
        'home'     => ['Overview', 'owner'],
        'orders'   => ['Orders', 'owner/orders'],
        'products' => ['My products', 'owner/products'],
        'shop'     => ['Shop and banner', 'owner/shop'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= out($page_title ?? 'Dashboard') ?> | Meatlab</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>dash_assets/dash.css">
    <link href="<?= BASE_URL ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<header class="top">
    <button class="burger" id="burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="side">
        <span></span><span></span><span></span>
    </button>
    <!-- Same logo as the website: icon and "Meatlab" side by side. Change the path if your logo file differs. -->
    <a class="brand" href="<?= BASE_URL . $home ?>">
        <img src="<?= BASE_URL ?>images/logo.png" alt="">
    </a>
    <div class="who">
        <span><?= out(ml_initials($user['name']) ?? '') ?></span>
        <a href="<?= BASE_URL ?>portal/logout">Log out</a>
    </div>
</header>

<div class="shell">
    <nav class="side" id="side" aria-label="Dashboard menu">
        <?php foreach ($nav as $key => [$label, $path]): ?>
            <a href="<?= BASE_URL . $path ?>" class="<?= ($nav_active ?? '') === $key ? 'on' : '' ?>">
                <?php if($label  == 'Overview'): ?>
                    <i class="fa fa-home"></i> <?= $label ?>
                <?php elseif($label  == 'Shops'): ?>                    
                    <i class="fa fa-shopping-bag"></i> <?= $label ?>
                <?php elseif($label  == 'All products'): ?>                    
                    <i class="fa fa-product-hunt"></i> <?= $label ?>      
                <?php elseif($label  == 'All orders'): ?>                    
                    <i class="fa fa-shopping-cart"></i> <?= $label ?>    
                <?php elseif($label  == 'Settings'): ?>                    
                    <i class="fa fa-settings"></i> <?= $label ?>    
                <?php elseif($label  == 'Shop and banner'): ?>                    
                    <i class="fa fa-shopping-bag"></i> <?= $label ?>                                                           
                <?php endif ?> 
                <?php if ($key === 'orders' && $role === 'owner' && ($pending ?? 0) > 0): ?>
                    <b class="count"><?= (int) $pending ?></b>
                <?php elseif ($key === 'products' && $role === 'admin' && ($pending ?? 0) > 0): ?>
                    <b class="count"><?= (int) $pending ?></b>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
        <?php if ($role === 'owner' && !empty($shop)): ?>
            <a class="ext" href="<?= BASE_URL . 'shops/show/' . out($shop->slug) ?>" target="_blank" rel="noopener">View my shop page</a>
        <?php endif; ?>
    </nav>
    <div class="scrim" id="scrim"></div>

    <main>
        <h1><?= out($page_title ?? '') ?></h1>
        <?php if (!empty($flash)): ?>
            <div class="flash f-<?= out($flash['type']) ?>" role="status"><?= out($flash['message']) ?></div>
        <?php endif; ?>
        <?php Template::display($data); ?>
    </main>
</div>
<script src="<?= BASE_URL ?>dash_assets/dash.js"></script>
</body>
</html>
