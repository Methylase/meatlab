<form class="card" method="post" action="<?= BASE_URL ?>admin/settings_save">
    <input type="hidden" name="csrf" value="<?= out($csrf) ?>">

    <h2>Defaults for new shops</h2>
    <p class="hint">You can still change each shop on its own Edit page.</p>
    <label class="check"><input type="checkbox" name="default_can_edit_banner" value="1" <?= $s['default_can_edit_banner'] === '1' ? 'checked' : '' ?>> Owners can upload and change their banner</label>
    <label class="check"><input type="checkbox" name="default_can_add_products" value="1" <?= $s['default_can_add_products'] === '1' ? 'checked' : '' ?>> Owners can add products</label>
    <label class="check"><input type="checkbox" name="default_can_edit_price" value="1" <?= $s['default_can_edit_price'] === '1' ? 'checked' : '' ?>> Owners can change prices</label>
    <label for="default_max_products">Most products per shop</label>
    <input id="default_max_products" type="number" name="default_max_products" min="1" max="5000" value="<?= (int) $s['default_max_products'] ?>">

    <h2 style="margin-top:24px">Products and images</h2>
    <label class="check"><input type="checkbox" name="auto_approve_products" value="1" <?= $s['auto_approve_products'] === '1' ? 'checked' : '' ?>> New products go live without admin approval</label>
    <label for="max_image_mb">Largest image allowed (MB)</label>
    <input id="max_image_mb" type="number" name="max_image_mb" min="1" max="10" value="<?= (int) $s['max_image_mb'] ?>">
    <label for="categories">Product categories <span class="hint">Separate with commas</span></label>
    <textarea id="categories" name="categories"><?= out($s['categories']) ?></textarea>

    <p><button class="btn" type="submit">Save settings</button></p>
</form>
