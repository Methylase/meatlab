<div class="stats">
    <div class="stat"><b><?= (int) $stats['shops'] ?></b><span>Shops</span></div>
    <div class="stat"><b><?= (int) $stats['products'] ?></b><span>Products</span></div>
    <div class="stat <?= $stats['review'] ? 'hot' : '' ?>"><b><?= (int) $stats['review'] ?></b><span>Products awaiting approval</span></div>
    <div class="stat"><b><?= (int) $stats['orders'] ?></b><span>Paid orders</span></div>
    <div class="stat"><b><?= (int) $stats['open'] ?></b><span>Orders in progress</span></div>
    <div class="stat"><b><?= /*ml_money(*/$stats['revenue'] /*)*/ ?></b><span>Paid order value</span></div>
</div>

<div class="bar"><h2>Latest orders</h2><a class="btn line sm" href="<?= BASE_URL ?>admin/orders">See all orders</a></div>
<?php if (!$recent): ?>
    <div class="card empty">No paid orders yet.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Order</th><th>Shop</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($recent as $o): ?>
        <tr>
            <td data-label="Order"><?= out($o->reference) ?></td>
            <td data-label="Shop"><?= out($o->shop_name) ?></td>
            <td data-label="Customer"><?= out($o->customer_name) ?></td>
            <td data-label="Total"><?= ml_money($o->total) ?></td>
            <td data-label="Status"><?= ml_badge($o->status) ?></td>
            <td class="full"><a class="btn line sm" href="<?= BASE_URL ?>admin/order/<?= (int) $o->id ?>">Open</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>

    <?php if ($pages > 1):
        $link = fn($p) => BASE_URL . 'admin?' . http_build_query(['page' => $p]);
        $from = ($page - 1) * $perPage + 1;
        $to   = min($page * $perPage, $total);
        $start = max(1, $page - 2);
        $end   = min($pages, $page + 2);
    ?>
    <div class="pager" aria-label="Orders pages">
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
