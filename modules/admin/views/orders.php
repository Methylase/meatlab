<form class="filters" method="get" action="<?= BASE_URL ?>admin/orders">
    <select name="shop" aria-label="Shop" onchange="this.form.submit()">
        <option value="0">All shops</option>
        <?php foreach ($shops as $s): ?><option value="<?= (int) $s->id ?>" <?= $shop_id === (int) $s->id ? 'selected' : '' ?>><?= out($s->shop_name) ?></option><?php endforeach; ?>
    </select>
    <select name="status" aria-label="Order status" onchange="this.form.submit()">
        <option value="">Any status</option>
        <?php foreach (['pending' => 'New', 'processing' => 'Processing', 'on_the_way' => 'On the way', 'delivered' => 'Delivered', 'declined' => 'Declined'] as $k => $l): ?>
            <option value="<?= $k ?>" <?= $status === $k ? 'selected' : '' ?>><?= $l ?></option>
        <?php endforeach; ?>
    </select>
    <select name="payout" aria-label="Shop payment" onchange="this.form.submit()">
        <option value="">Paid and unpaid shops</option>
        <option value="unpaid" <?= $payout === 'unpaid' ? 'selected' : '' ?>>Not yet paid to shop</option>
        <option value="paid" <?= $payout === 'paid' ? 'selected' : '' ?>>Paid to shop</option>
    </select>
    <noscript><button class="btn sm" type="submit">Filter</button></noscript>
</form>

<?php if (!$orders): ?>
    <div class="card empty">No paid orders match these filters.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Order</th><th>Shop</th><th>Customer</th><th>Total</th><th>Status</th><th>Paid to shop</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td data-label="Order"><?= out($o->reference) ?><br><span class="hint"><?= date('d M, g:ia', strtotime($o->created_at)) ?></span></td>
            <td data-label="Shop"><?= out($o->shop_name) ?></td>
            <td data-label="Customer"><?= out($o->customer_name) ?></td>
            <td data-label="Total"><?= ml_money($o->total) ?></td>
            <td data-label="Status"><?= ml_badge($o->status) ?></td>
            <td data-label="Paid to shop">
                <?php if ($o->shop_paid_at): ?>
                    <span class="badge b-approved">Paid</span><br>
                    <span class="hint"><?= ml_money($o->shop_paid_amount) ?> on <?= date('d M Y, g:ia', strtotime($o->shop_paid_at)) ?></span>
                <?php elseif ($o->status === 'declined'): ?>
                    <span class="hint">Not applicable</span>
                <?php else: ?>
                    <span class="badge b-pending">Not paid</span>
                <?php endif; ?>
            </td>
            <td class="full"><a class="btn sm <?= (!$o->shop_paid_at && $o->status !== 'declined') ? '' : 'line' ?>" href="<?= BASE_URL ?>admin/order/<?= (int) $o->id ?>#payout"><?= (!$o->shop_paid_at && $o->status !== 'declined') ? 'Pay shop' : 'Open' ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>

    <?php if ($pages > 1):
        $link = fn($p) => BASE_URL . 'admin/orders?' . http_build_query(['page' => $p]);
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
