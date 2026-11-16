<?php $back = $is_admin ? 'admin/orders' : 'owner/orders'; ?>
<p><a href="<?= BASE_URL . $back ?>">Back to orders</a></p>

<div class="card">
    <div class="bar"><h2><?= out($order->reference) ?></h2><?= ml_badge($order->status) ?></div>
    <dl class="kv">
        <?php if ($is_admin): ?><dt>Shop</dt><dd><?= out($order->shop_name) ?></dd><?php endif; ?>
        <dt>Customer</dt><dd><?= out($order->customer_name) ?></dd>
        <dt>Phone</dt><dd><?= $order->customer_phone ? '<a href="tel:' . out($order->customer_phone) . '">' . out($order->customer_phone) . '</a>' : 'Not given' ?></dd>
        <dt>Email</dt><dd><?= out($order->customer_email ?: 'Not given') ?></dd>
        <dt>Deliver to</dt><dd><?= out($order->delivery_address ?: 'Not given') ?></dd>
        <dt>Placed</dt><dd><?= date('d M Y, g:ia', strtotime($order->created_at)) ?></dd>
        <dt>Payment</dt><dd><?= ml_badge($order->payment_status) ?></dd>
    </dl>
</div>

<div class="card">
    <h2>Items</h2>
    <div class="tablewrap"><table>
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Amount</th></tr></thead>
        <tbody>
        <?php foreach ($items as $i): ?>
            <tr>
                <td data-label="Product"><?= out($i->product_name) ?></td>
                <td data-label="Price"><?= ml_money($i->unit_price) ?></td>
                <td data-label="Qty"><?= rtrim(rtrim(number_format((float) $i->quantity, 2), '0'), '.') ?></td>
                <td data-label="Amount"><?= ml_money($i->line_total) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <p><strong>Total: <?= ml_money($order->total) ?></strong></p>
</div>

<div class="card" id="payout">
    <h2>Payment to shop</h2>
    <?php if ($order->shop_paid_at): ?>
        <dl class="kv">
            <dt>Status</dt><dd><span class="badge b-approved"><?= $is_admin ? 'Paid to shop' : 'Received' ?></span></dd>
            <dt>Amount</dt><dd><?= ml_money($order->shop_paid_amount) ?></dd>
            <dt>Paid on</dt><dd><?= date('d M Y, g:ia', strtotime($order->shop_paid_at)) ?></dd>
            <?php if ($order->shop_payout_ref): ?><dt>Reference</dt><dd><?= out($order->shop_payout_ref) ?></dd><?php endif; ?>
        </dl>
        <?php if ($is_admin): ?>
            <form method="post" action="<?= BASE_URL ?>admin/payout_undo" data-confirm="Remove this payout record? The shop will see the order as awaiting payment again." style="margin-top:12px">
                <input type="hidden" name="csrf" value="<?= out($csrf) ?>"><input type="hidden" name="id" value="<?= (int) $order->id ?>">
                <button class="btn line sm" type="submit">Undo payout record</button>
            </form>
        <?php endif; ?>
    <?php elseif ($order->status === 'declined' || $order->payment_status !== 'paid'): ?>
        <p class="hint">No payment is due to the shop for this order.</p>
    <?php elseif ($is_admin): ?>
        <p class="hint">After you pay <?= out($order->shop_name) ?>, record it here. The date and time are saved automatically.</p>
        <form method="post" action="<?= BASE_URL ?>admin/payout_save">
            <input type="hidden" name="csrf" value="<?= out($csrf) ?>"><input type="hidden" name="id" value="<?= (int) $order->id ?>">
            <div class="grid2">
                <div><label for="amount">Amount paid (₦)</label><input id="amount" type="number" name="amount" min="1" step="0.01" inputmode="decimal" value="<?= out($order->total) ?>" required></div>
                <div><label for="reference">Transfer reference <span class="hint">Optional</span></label><input id="reference" type="text" name="reference" maxlength="60"></div>
            </div>
            <label class="check"><input type="checkbox" name="notify" value="1" checked> Tell the shop owner by SMS and email</label>
            <button class="btn" type="submit">Mark as paid to shop</button>
        </form>
    <?php else: ?>
        <p><span class="badge b-processing">Awaiting</span></p>
        <p class="hint">Meatlab has not yet paid you for this order. You will get an SMS and email when it is paid.</p>
    <?php endif; ?>
</div>

<?php if (!$is_admin && $order->payment_status === 'paid'): ?>
<div class="card">
    <h2>Next step</h2>
    <?php
    $steps = [
        'pending'    => [['accept', 'Accept and start processing', ''], ['decline', 'Decline', 'line']],
        'processing' => [['dispatch', 'Ready and on the way', '']],
        'on_the_way' => [['deliver', 'Mark delivered', '']],
    ];
    $notice = [
        'pending'    => 'Accepting tells the customer by SMS and email that you are processing their order.',
        'processing' => 'This tells the customer by SMS and email that the order is ready and on the way.',
        'on_the_way' => 'This tells the customer the order has been delivered.',
    ];
    ?>
    <?php if (isset($steps[$order->status])): ?>
        <p class="hint"><?= $notice[$order->status] ?></p>
        <div class="actions">
        <?php foreach ($steps[$order->status] as [$action, $label, $style]): ?>
            <form method="post" action="<?= BASE_URL ?>owner/<?= $action ?>" <?= $action === 'decline' ? 'data-confirm="Decline this order? The customer will be told."' : '' ?>>
                <input type="hidden" name="csrf" value="<?= out($csrf) ?>"><input type="hidden" name="id" value="<?= (int) $order->id ?>">
                <button class="btn <?= $style ?>" type="submit"><?= $label ?></button>
            </form>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="hint">Nothing more to do. This order is <?= strtolower(ml_status_label($order->status)) ?>.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($notes): ?>
<div class="card">
    <h2>Messages sent</h2>
    <ul class="log">
    <?php foreach ($notes as $n): ?>
        <li><?= date('d M, g:ia', strtotime($n->created_at)) ?>: <?= $n->channel === 'sms' ? 'SMS' : 'Email' ?> for "<?= out(ml_status_label($n->event)) ?>" <?= $n->success ? 'sent' : 'failed (' . out($n->response) . ')' ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
