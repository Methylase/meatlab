<section class="page-pad"><div class="container" style="text-align:center;max-width:560px">
<i class="fa-solid <?= $ok ? 'fa-circle-check' : 'fa-circle-xmark' ?>" style="font-size:4rem;color:var(--red)"></i>
<h1 class="h2" style="margin:1rem 0"><?= $ok ? 'Payment <em>successful</em>' : 'Payment <em>failed</em>' ?></h1>
<p class="sub" style="margin-inline:auto"><?= $ok ? 'Thank you! Your order ' . out($order->customer_payment_reference) . ' is confirmed and the shop is preparing it.' : 'We could not confirm your payment. You have not been charged for a failed attempt.' ?></p>
<a class="btn btn-red" href="<?= BASE_URL ?><?= $ok ? 'shops' : 'cart' ?>"><?= $ok ? 'Keep shopping' : 'Back to cart' ?></a></div></section>
