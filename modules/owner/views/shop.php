<form class="card" method="post" action="<?= BASE_URL ?>owner/shop_save" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= out($csrf) ?>">
    <h2><?= out($shop->shop_name) ?></h2>

    <label>Shop banner <span class="hint">Wide image, about 1600 x 500. JPG, PNG or WebP.</span></label>
    <div class="banner" id="bannerPreview" style="<?= $shop->banner ? "background-image:url('" . out(ml_img('banners', $shop->banner)) . "')" : '' ?>"><?= $shop->banner ? '' : 'No banner yet' ?></div>
    <?php if ($shop->can_edit_banner): ?>
        <label for="banner"><?= $shop->banner ? 'Replace banner' : 'Upload banner' ?></label>
        <input id="banner" type="file" name="banner" accept="image/jpeg,image/png,image/webp" data-preview="#bannerPreview">
    <?php else: ?>
        <p class="hint">Banner changes are turned off for your shop. Contact the admin to change it.</p>
    <?php endif; ?>

    <div class="grid2">
        <div><label for="address">Address</label><input id="address" type="text" name="address" value="<?= out($shop->address) ?>" maxlength="255"></div>
        <div><label for="city">City</label><input id="city" type="text" name="city" value="<?= out($shop->city) ?>" maxlength="80"></div>
        <div><label for="phone">Shop phone <span class="hint">Shown to customers in order messages</span></label><input id="phone" type="tel" name="phone" value="<?= out($shop->phone) ?>" maxlength="20"></div>
    </div>
    <label for="description">About your shop</label>
    <textarea id="description" name="description" maxlength="1000"><?= out($shop->description) ?></textarea>
    <p><button class="btn" type="submit">Save shop</button></p>
</form>
