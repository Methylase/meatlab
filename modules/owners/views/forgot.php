<section class="auth"><div class="auth-card"><h1 class="h2" style="font-size:2.2rem">Forgot <em>password</em></h1><p class="sub" style="margin:.5rem 0 1.5rem">Enter your email and we will send a reset link.</p>
<?php if ($errors) echo '<div class="err">' . $errors . '</div>'; ?>
<?= form_open('owners/submit_forgot') ?><div class="field"><label>Email</label><input name="email" type="email" required></div>
<button class="btn btn-red btn-block">Send reset link</button><?= form_close() ?></div></section>
