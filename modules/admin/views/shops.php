<div class="bar"><span class="hint"><?= count($shops) ?> shops</span><a class="btn" href="<?= BASE_URL ?>admin/shop_form">Add shop</a></div>
<?php if (!$shops): ?>
    <div class="card empty">No shops yet. Add the first meat shop and its owner.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Shop</th><th>Owner</th><th>Products</th><th>Orders</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($shops as $s): ?>
        <tr>
            <td class="full"><div class="who-cell">
                <div class="thumb" style="<?= $s->banner ? "background-image:url('" . out(ml_img('banners', $s->banner)) . "')" : '' ?>"></div>
                <div><strong><?= out($s->shop_name) ?></strong><br><span class="hint"><?= out($s->city) ?></span></div>
            </div></td>
            <td data-label="Owner"><?= out($s->owner_name) ?><br><span class="hint"><?= out($s->owner_phone ?: $s->owner_email) ?></span></td>
            <td data-label="Products"><a href="<?= BASE_URL ?>admin/products?shop=<?= (int) $s->id ?>"><?= (int) $s->product_count ?></a></td>
            <td data-label="Orders"><a href="<?= BASE_URL ?>admin/orders?shop=<?= (int) $s->id ?>"><?= (int) $s->order_count ?></a></td>
            <td data-label="Status"><?= $s->is_active ? '<span class="badge b-approved">Active</span>' : '<span class="badge b-declined">Off</span>' ?></td>
            <td class="full"><a class="btn line sm" href="<?= BASE_URL ?>admin/shop_form/<?= (int) $s->id ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>

    <?php if ($pages > 1):
        $link = fn($p) => BASE_URL . 'admin/shops?' . http_build_query(['page' => $p]);
        $from = ($page - 1) * $perPage + 1;
        $to   = min($page * $perPage, $total);
        $start = max(1, $page - 2);
        $end   = min($pages, $page + 2);
    ?>
    <div class="pager" aria-label="Shops pages">
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
