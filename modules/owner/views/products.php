<div class="bar">
    <span class="hint"><?= count($products) ?> of <?= (int) $shop->max_products ?> products used</span>
    <?php if ($shop->can_add_products): ?><a class="btn" href="<?= BASE_URL ?>owner/product_form">Add product</a><?php endif; ?>
</div>
<?php if (!$products): ?>
    <div class="card empty">You have not added any products. Add your first one to start getting orders.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td class="full"><div class="who-cell">
                <div class="thumb" style="<?= $p->image ? "background-image:url('" . out(ml_img('products', $p->image)) . "')" : '' ?>"></div>
                <div><strong><?= out($p->name) ?></strong><br><span class="hint"><?= out($p->category) ?></span></div>
            </div></td>
            <td data-label="Price"><?= ml_money($p->price_per_kg ) ?> / <?= out($p->unit) ?></td>
            <td data-label="Stock"><?= $p->in_stock ? 'In stock' : 'Out of stock' ?></td>
            <td data-label="Status"><?= ml_badge($p->status ) ?></td>
            <td class="full"><div class="actions">
                <a class="btn line sm" href="<?= BASE_URL ?>owner/product_form/<?= (int) $p->id ?>">Edit</a>
                <form method="post" action="<?= BASE_URL ?>owner/product_delete" data-confirm="Delete <?= out($p->name) ?>?">
                    <input type="hidden" name="csrf" value="<?= out($csrf) ?>"><input type="hidden" name="id" value="<?= (int) $p->id ?>">
                    <button class="btn line sm" type="submit">Delete</button>
                </form>
            </div></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
    <?php if ($pages > 1):
        $link = fn($p) => BASE_URL . 'owner/products?' . http_build_query(['page' => $p]);
        $from = ($page - 1) * $perPage + 1;
        $to   = min($page * $perPage, $total);
        $start = max(1, $page - 2);
        $end   = min($pages, $page + 2);
    ?>
    <div class="pager" aria-label="Products pages">
        <div class="hint">Showing <?= $from ?>&ndash;<?= $to ?> of <?= $total ?></div>
        <div class="pager-links">
            <?php if ($page > 1): ?><a href="<?= $link($page - 1) ?>" aria-label="Previous">&lsaquo; Prev</a><?php endif; ?>
            <?php if ($start > 1): ?><a href="<?= $link(1) ?>">1</a><?php if ($start > 2): ?><span>&hellip;</span><?php endif; ?><?php endif; ?>
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a href="<?= $link($i) ?>" class="<?= $i === $page ? 'on' : '' ?>" <?= $i === $page ? 'aria-current="page"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($end < $pages): ?><?php if ($end < $pages - 1): ?><span>&hellip;</span><?php endif; ?><a href="<?= $link($pages) ?>"><?= $pages ?></a><?php endif; ?>
            <?php if ($page < $pages): ?><a href="<?= $link($page + 1) ?>" aria-label="Next">Next &rsaquo;</a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

<?php endif; ?>
