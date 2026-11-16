<section class="auth"><div class="auth-card"><h1 class="h2" style="font-size:2.2rem">Shop owner <em>login</em></h1><p class="sub" style="margin:.5rem 0 1.5rem">Log in with your phone number or email.</p>

<?php if ($errors) echo '<div class="err">' . $errors . '</div>'; ?>

<?php if (!empty($flash)): ?>
    <div class="err f-<?= out($flash['type']) ?>" role="alert"><?= out($flash['message']) ?></div>
<?php endif; ?>
<?= form_open('portal/submit_login') ?>
<input type="hidden" name="csrf" value="<?= out($csrf) ?>">

<div class="field"><label>Phone number or email</label><input name="login" value="<?= out($login) ?>" autocomplete="username" required></div>
<div class="field"><label>Password</label><input name="password" type="password" autocomplete="current-password" required></div>
<div class="row-between"><label><input type="checkbox" name="remember" value="1"> Remember me</label><a href="<?= BASE_URL ?>owners/forgot">Forgot password?</a></div>
<button class="btn btn-red btn-block" type="submit">Log In</button><?= form_close() ?></div></section>
