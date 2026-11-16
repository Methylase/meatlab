<?php
$s = $shop;
$v = fn($key, $fallback = '') => $s->$key ?? $fallback;
$perm = fn($key) => $s ? (int) $s->$key : (int) $defaults[$key];
?>
<form class="card" method="post" action="<?= BASE_URL ?>admin/shop_save" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= out($csrf) ?>">
    <input type="hidden" name="id" value="<?= $s ? (int) $s->id : 0 ?>">

    <h2>Shop</h2>
    <div class="grid2">
        <div><label for="shop_name">Shop name</label><input id="shop_name" type="text" name="shop_name" value="<?= out($v('shop_name')) ?>" maxlength="120" required></div>
        <div><label for="city">City</label><input id="city" type="text" name="city" value="<?= out($v('city')) ?>" maxlength="80"></div>
    </div>
    <label for="address">Address</label>
    <input id="address" type="text" name="address" value="<?= out($v('address')) ?>" maxlength="255">

    <h2 style="margin-top:24px">Owner login</h2>
    <div class="grid2">
        <div><label for="owner_name">Owner name</label><input id="owner_name" type="text" name="owner_name" value="<?= out($v('owner_name')) ?>" maxlength="120" required></div>
        <div><label for="phone">Phone</label><input id="phone" type="tel" name="phone" value="<?= out($v('owner_phone')) ?>" maxlength="20"></div>
        <div><label for="email">Email</label><input id="email" type="email" name="email" value="<?= out($v('owner_email')) ?>" maxlength="160" readonly></div>
        <div>
            <label for="password"><?= $s ? 'New password' : 'Password' ?> <span class="hint"><?= $s ? 'Leave blank to keep the current one' : 'At least 8 characters' ?></span></label>
            <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" <?= $s ? '' : 'required' ?>>
        </div>
    </div>

    <h2 style="margin-top:24px">What this owner can do</h2>
    <label class="check"><input type="checkbox" name="is_active" value="1" <?= !$s || $s->is_active ? 'checked' : '' ?>> Shop is active (owner can log in)</label>
    <label class="check"><input type="checkbox" name="can_edit_banner" value="1" <?= $perm('can_edit_banner') ? 'checked' : '' ?>> Can upload and change the shop banner</label>
    <label class="check"><input type="checkbox" name="can_add_products" value="1" <?= $perm('can_add_products') ? 'checked' : '' ?>> Can add products</label>
    <label class="check"><input type="checkbox" name="can_edit_price" value="1" <?= $perm('can_edit_price') ? 'checked' : '' ?>> Can change prices</label>
    <label for="max_products">Most products allowed</label>
    <input id="max_products" type="number" name="max_products" min="0" max="5000" value="<?= $perm('max_products') ?>">

    <div class="actions" style="margin-top:20px">
        <button class="btn" type="submit"><?= $s ? 'Save shop' : 'Create shop' ?></button>
        <a class="btn line" href="<?= BASE_URL ?>admin/shops">Cancel</a>
    </div>
</form>
