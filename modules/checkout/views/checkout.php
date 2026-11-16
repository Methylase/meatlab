<section class="page-pad"><div class="container"><h1 class="h2">Check<em>out</em></h1><div class="checkout" style="margin-top:2rem">
<div><?php if ($errors) echo '<div class="err">' . $errors . '</div>'; ?>
<?= form_open('checkout/pay') ?>
<div class="field"><label>Full name</label><input name="name" required></div>
<div class="field"><label>Phone</label><input name="phone" type="tel" required></div>
<div class="field"><label>Email (for Paystack receipt)</label><input name="email" type="email" required></div>
<div class="field"><label>Delivery address</label><textarea name="address" rows="3" required></textarea></div>
<button class="btn btn-red btn-block"><i class="fa-solid fa-lock"></i> Pay with Paystack</button><?= form_close() ?></div>
<div class="sum"><h3 style="margin-bottom:.6rem">Order summary</h3>
<?php foreach ($lines as $l): ?><div><span><?= out($l['p']->name) ?> &times; <?= $l['kg'] ?>kg</span><span>&#8358;<?= number_format($l['total']) ?></span></div><?php endforeach; ?>
<div><span>Delivery</span><span>&#8358;<?= number_format(DELIVERY_FEE) ?></span></div><div class="t"><span>Total</span><span>&#8358;<?= number_format($sub + DELIVERY_FEE) ?></span></div></div></div></div></section>
