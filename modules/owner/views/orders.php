<?php
$tabs = ['all' => 'All', 'pending' => 'New', 'processing' => 'Processing', 'on_the_way' => 'On the way', 'delivered' => 'Delivered', 'declined' => 'Declined'];
$paytabs = ['' => 'Any payment', 'received' => 'Paid to me', 'awaiting' => 'Awaiting payment'];
$link = function ($status, $pay) {
    $q = [];
    if ($status !== 'all' && $status !== '') { $q[] = 'status=' . $status; }
    if ($pay !== '') { $q[] = 'pay=' . $pay; }
    return BASE_URL . 'owner/orders' . ($q ? '?' . implode('&', $q) : '');
};
?>
<div class="tabs">
    <?php foreach ($tabs as $k => $label): ?>
        <a class="<?= $filter === $k ? 'on' : '' ?>" href="<?= $link($k, $pay) ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>
<div class="tabs">
    <?php foreach ($paytabs as $k => $label): ?>
        <a class="<?= $pay === $k ? 'on' : '' ?>" href="<?= $link($filter, $k) ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<?php if (!$orders): ?>
    <div class="card empty">No orders here yet. Paid orders from customers show up on this page.</div>
<?php else: ?>
<div class="tablewrap"><table>
    <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Payment from Meatlab</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td data-label="Order"><?= out($o->reference) ?><br><span class="hint"><?= date('d M, g:ia', strtotime($o->created_at)) ?></span></td>
            <td data-label="Customer"><?= out($o->customer_name) ?></td>
            <td data-label="Total"><?= ml_money($o->total) ?></td>
            <td data-label="Status"><?= ml_badge($o->status) ?></td>
            <td data-label="Payment">
                <?php if ($o->shop_paid_at): ?>
                    <span class="badge b-approved">Received</span><br>
                    <span class="hint"><?= ml_money($o->shop_paid_amount) ?> on <?= date('d M Y, g:ia', strtotime($o->shop_paid_at)) ?></span>
                <?php elseif ($o->status === 'declined'): ?>
                    <span class="hint">Not applicable</span>
                <?php else: ?>
                    <span class="badge b-processing">Awaiting</span>
                <?php endif; ?>
            </td>
            <td class="full"><a class="btn sm <?= $o->status === 'pending' ? '' : 'line' ?>" href="<?= BASE_URL ?>owner/order/<?= (int) $o->id ?>"><?= $o->status === 'pending' ? 'Review order' : 'Open' ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>

    <?php if ($pages > 1):
        $q = array_filter(['status' => $filter !== 'all' ? $filter : '', 'pay' => $pay]);
        $link = fn($p) => BASE_URL . 'owner/orders?' . http_build_query($q + ['page' => $p]);
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
