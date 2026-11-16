<?php $self = 'admin/products' . ($shop_id ? '?shop=' . $shop_id : ''); ?>
<form class="filters" method="get" action="<?= BASE_URL ?>admin/products">
    <select name="shop" aria-label="Shop" onchange="this.form.submit()">
        <option value="0">All shops</option>
        <?php foreach ($shops as $s): ?><option value="<?= (int) $s->id ?>" <?= $shop_id === (int) $s->id ? 'selected' : '' ?>><?= out($s->shop_name) ?></option><?php endforeach; ?>
    </select>
    <select name="status" aria-label="Status" onchange="this.form.submit()">
        <option value="">Any status</option>
        <?php foreach (['pending' => 'Awaiting approval', 'approved' => 'Live', 'rejected' => 'Rejected'] as $k => $l): ?>
            <option value="<?= $k ?>" <?= $status === $k ? 'selected' : '' ?>><?= $l ?></option>
        <?php endforeach; ?>
    </select>
    <noscript><button class="btn sm" type="submit">Filter</button></noscript>
</form>

<?php if (!$products): ?>
    <div class="card empty">No products match these filters.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Product</th><th>Shop</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td class="full"><div class="who-cell">
                <div class="thumb" style="<?= $p->image ? "background-image:url('" . out(ml_img('products', $p->image)) . "')" : '' ?>"></div>
                <div><strong><?= out($p->name) ?></strong><br><span class="hint"><?= out(Modules::run('admin/get_category',$p->category_id)) ?></span></div>
            </div></td>
            <td data-label="Shop"><?= out($p->shop_name) ?></td>
            <td data-label="Price"><?= /*ml_money(*/$p->price_per_kg /*)*/ ?> / <?= out($p->unit) ?></td>
            <td data-label="Stock"><?= $p->in_stock ? 'In stock' : 'Out of stock' ?></td>
            <td data-label="Status"><?= /*ml_badge(*/ $p->status /*)*/ ?></td>
            <td class="full"><div class="actions">
                <?php foreach ([['approved', 'Approve'], ['rejected', 'Reject']] as [$to, $label]): if ($p->status === $to) continue; ?>
                    <form method="post" action="<?= BASE_URL ?>admin/product_status">
                        <input type="hidden" name="csrf" value="<?= out($csrf) ?>"><input type="hidden" name="id" value="<?= (int) $p->id ?>">
                        <input type="hidden" name="status" value="<?= $to ?>"><input type="hidden" name="return" value="<?= out($self) ?>">
                        <button class="btn sm <?= $to === 'rejected' ? 'line' : '' ?>" type="submit"><?= $label ?></button>
                    </form>
                <?php endforeach; ?>
            </div></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>

    <?php if ($pages > 1):
        $link = fn($p) => BASE_URL . 'admin/products?' . http_build_query(['page' => $p]);
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
