<section class="page-pad"><div class="container"><h1 class="h2">Your <em>cart</em></h1>
<?php if (!$lines): ?><p class="sub">Your cart is empty.</p><a class="btn btn-red" href="<?= BASE_URL ?>shops">Browse shops</a>
<?php else: ?><p class="sub">Ordering from <b><?= out($shop->shop_name) ?></b></p>
<div class="checkout"><form action="<?= BASE_URL ?>cart/update" method="post"><div style="overflow-x:auto"><table>
<tr><th>Product</th><th>Weight (kg)</th><th>Amount</th><th></th></tr>
<?php foreach ($lines as $l): ?><tr><td><?= out($l['p']->name) ?></td><td><input style="width:80px;padding:.5rem;border:1px solid #ccc;border-radius:8px" type="number" step="0.1" min="0" name="kg[<?= $l['p']->id ?>]" value="<?= $l['kg'] ?>"></td><td>&#8358;<?= number_format($l['total']) ?></td><td><a href="<?= BASE_URL ?>cart/remove/<?= $l['p']->id ?>" aria-label="Remove"><i class="fa-solid fa-trash"></i></a></td></tr><?php endforeach; ?>
</table></div><button class="btn btn-dark" style="margin-top:1rem">Update cart</button></form>
<div class="sum"><div><span>Subtotal</span><span>&#8358;<?= number_format($sub) ?></span></div><div><span>Delivery</span><span>&#8358;<?= number_format(1500) ?></span></div><div class="t"><span>Total</span><span>&#8358;<?= number_format($sub + 1500) ?></span></div>
<a class="btn btn-red btn-block" style="margin-top:1rem" href="<?= BASE_URL ?>checkout">Proceed to checkout</a></div></div><?php endif; ?></div></section>
