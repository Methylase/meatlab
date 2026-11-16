<?php $p = $product; $locked = $p && !$shop->can_edit_price; ?>
<form class="card" method="post" action="<?= BASE_URL ?>owner/product_save" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= out($csrf) ?>">
    <input type="hidden" name="id" value="<?= $p ? (int) $p->id : 0 ?>">

    <label for="name">Product name</label>
    <input id="name" type="text" name="name" value="<?= out($p->name ?? '') ?>" maxlength="140" required placeholder="e.g. Fresh goat meat">

    <div class="grid2">
        <div>
            <label for="category">Category</label>
            <select id="category" name="category">
                <?php foreach ($categories as $c): ?>
                    
                    <option value=<?= $c ?> <?= ($p->category ?? '') === $c ? 'selected' : '' ?>><?= out($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="unit">Sold per</label>
            <select id="unit" name="unit">
                <?php foreach ($units as $u): ?>
                    <option <?= ($p->unit ?? 'kg') === $u ? 'selected' : '' ?>><?= out($u) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <label for="price">Price (₦) <?= $locked ? '<span class="hint">Locked by the admin</span>' : '' ?></label>
    <input id="price" type="number" name="price" min="1" step="0.01" inputmode="decimal" value="<?= out($p->price_per_kg ?? '') ?>" required <?= $locked ? 'readonly' : '' ?>>

    <label for="description">Description <span class="hint">Optional</span></label>
    <textarea id="description" name="description" maxlength="1000"><?= out($p->description ?? '') ?></textarea>

    <label>Photo <span class="hint">JPG, PNG or WebP</span></label>
    <div class="pic" id="picPreview" style="<?= !empty($p->image) ? "background-image:url('" . out(ml_img('products', $p->image)) . "')" : '' ?>"><?= !empty($p->image) ? '' : 'No photo' ?></div><br>
    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="#picPreview">

    <label class="check"><input type="checkbox" name="in_stock" value="1" <?= !$p || $p->in_stock ? 'checked' : '' ?>> In stock</label>

    <div class="actions">
        <button class="btn" type="submit"><?= $p ? 'Save changes' : 'Add product' ?></button>
        <a class="btn line" href="<?= BASE_URL ?>owner/products">Cancel</a>
    </div>
</form>
