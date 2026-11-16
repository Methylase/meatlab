<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | Meatlab</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>dash_assets/dash.css">
</head>
<body>
<div class="login-wrap">
    <form class="login" method="post" action="<?= BASE_URL ?>portal/submit_login" autocomplete="on">
        <a class="brand" href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>images/logo.png" alt=""><span>Meatlab</span></a>
        <p class="sub">Log in to manage your shop</p>
        <?php if (!empty($flash)): ?>
            <div class="flash f-<?= out($flash['type']) ?>" role="alert"><?= out($flash['message']) ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf" value="<?= out($csrf) ?>">
        <label for="login">Phone or email</label>
        <input id="login" type="text" name="login" value="<?= out($login) ?>" autocomplete="username" required autofocus>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" autocomplete="current-password" required>
        <p><button class="btn btn-red block" type="submit">Log in</button></p>
    </form>
</div>
</body>
</html>
