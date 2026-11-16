<?php
$role = $role ?? 'owner';
$home = $role === 'admin' ? 'admin' : 'owner';
// key => [label, path, Font Awesome icon]
if ($role === 'admin') {
    $nav = [
        'home'     => ['Overview', 'admin', 'fa-gauge-high'],
        'shops'    => ['Shops', 'admin/shops', 'fa-store'],
        'products' => ['All products', 'admin/products', 'fa-drumstick-bite'],
        'orders'   => ['All orders', 'admin/orders', 'fa-receipt'],
        'settings' => ['Settings', 'admin/settings', 'fa-gear'],
    ];
    $role_label = 'Administrator';
} else {
    $nav = [
        'home'     => ['Overview', 'owner', 'fa-gauge-high'],
        'orders'   => ['Orders', 'owner/orders', 'fa-receipt'],
        'products' => ['My products', 'owner/products', 'fa-drumstick-bite'],
        'shop'     => ['Shop and banner', 'owner/shop', 'fa-image'],
    ];
    $role_label = !empty($shop) ? $shop->shop_name : 'Shop owner';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= out($page_title ?? 'Dashboard') ?> | Meatlab</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL ?>images/favicon-32x32.png" />
    <!-- Font Awesome. Remove this line if your site already loads Font Awesome 6 in another file. -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= BASE_URL ?>dash_assets/dash.css?v=<?= @filemtime(APPPATH . 'public/dash_assets/dash.css') ?>">
</head>
<body>
<header class="top">
    <button class="burger" id="burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="side">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>
    <!-- Same logo as the website. Transparent PNG at public/images/logo.png -->
    <a class="brand" href="<?= BASE_URL . $home ?>">
        <img src="<?= BASE_URL ?>images/logo.png" alt="Meatlab">
    </a>

    <details class="profile" id="profile">
        <summary class="profile-btn" aria-label="Account menu">
            <span class="avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
            <span class="pname"><?= out(ml_initials($user['name'] ?? '')) ?></span>
            <i class="fa-solid fa-chevron-down caret" aria-hidden="true"></i>
        </summary>
        <div class="menu" id="profileMenu">
            <div class="menu-head">
                <span class="avatar big" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                <div><strong><?= out($user['name'] ?? '') ?></strong><small><?= out($role_label) ?></small></div>
            </div>
            <a href="<?= BASE_URL ?>portal/logout"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Logout</a>
        </div>
    </details>
</header>

<div class="shell">
    <nav class="side" id="side" aria-label="Dashboard menu">
        <?php foreach ($nav as $key => [$label, $path, $icon]): ?>
            <a href="<?= BASE_URL . $path ?>" class="<?= ($nav_active ?? '') === $key ? 'on' : '' ?>" <?= ($nav_active ?? '') === $key ? 'aria-current="page"' : '' ?>>
                <span class="lbl"><i class="fa-solid <?= $icon ?>" aria-hidden="true"></i><?= $label ?></span>
                <?php if ($key === 'orders' && $role === 'owner' && ($pending ?? 0) > 0): ?>
                    <b class="count"><?= (int) $pending ?></b>
                <?php elseif ($key === 'products' && $role === 'admin' && ($pending ?? 0) > 0): ?>
                    <b class="count"><?= (int) $pending ?></b>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
        <?php if ($role === 'owner' && !empty($shop)): ?>
            <a class="ext" href="<?= BASE_URL . 'shops/show/' . out($shop->slug) ?>" target="_blank" rel="noopener">
                <span class="lbl"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>View my shop page</span>
            </a>
        <?php endif; ?>
        <a class="logout" href="<?= BASE_URL ?>portal/logout">
            <span class="lbl"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>Logout</span>
        </a>
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
<script src="<?= BASE_URL ?>dash_assets/dash.js?v=<?= @filemtime(APPPATH . 'public/dash_assets/dash.js') ?>"></script>
</body>
</html>
