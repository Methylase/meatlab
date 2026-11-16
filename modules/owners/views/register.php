<section class="auth"><div class="auth-card"><h1 class="h2" style="font-size:2.2rem">Create <em>account</em></h1><p class="sub" style="margin:.5rem 0 1.5rem">Open your Meatlab storefront.</p>
<?php if ($errors) echo '<div class="err">' . $errors . '</div>'; ?>
<?= form_open('owners/submit_register') ?>
<?php foreach (['owner_name' => 'Your name', 'shop_name' => 'Shop name', 'phone' => 'Phone number', 'email' => 'Email', 'address' => 'Shop address', 'city' => 'City'] as $n => $l): ?>
<div class="field"><label><?= $l ?></label><input name="<?= $n ?>" required></div><?php endforeach; ?>
<div class="field"><label>Password (min 8 characters)</label><input name="password" type="password" minlength="8" required></div>
<button class="btn btn-red btn-block">Create account</button><?= form_close() ?></div></section>
