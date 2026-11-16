<?php
/**
 * Admin dashboard: sets up shops, controls what each owner may do,
 * and sees every product and every order across all shops.
 */
class Admin extends Trongate {

    private $user;

    // ---------- Overview ----------

    public function index() {
        $this->boot();
        $perPage = 10;
        $stats = [
            'shops'    => $this->scalar('SELECT COUNT(*) n FROM shops'),
            'products' => $this->scalar('SELECT COUNT(*) n FROM products'),
            'review'   => $this->scalar('SELECT COUNT(*) n FROM products WHERE status=\'pending\''),
            'orders'   => $this->scalar('SELECT COUNT(*) n FROM orders WHERE payment_status=\'paid\''),
            'open'     => $this->scalar('SELECT COUNT(*) n FROM orders WHERE payment_status=\'paid\' AND status IN (\'pending\',\'processing\',\'on_the_way\')'),
            'revenue'  => $this->scalar('SELECT COALESCE(SUM(total),0) n FROM orders WHERE payment_status=\'paid\' AND status<>\'declined\''),
        ];

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM orders', [], 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        $recent = $this->rows(
            'SELECT o.*, s.shop_name FROM orders o JOIN shops s ON s.id=o.shop_id
             WHERE o.payment_status=\'paid\' ORDER BY o.id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset,
        );
        $this->page('index', 'Overview', 'home', compact('stats', 'recent', 'total', 'page', 'pages', 'perPage'));
     
    }

    // ---------- Shops ----------

    public function shops() {
        $this->boot();
        $perPage = 10;

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM shops', [], 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        $shops = $this->rows(
            'SELECT s.*, a.name AS owner_name, a.phone AS owner_phone, a.email AS owner_email,
                    (SELECT COUNT(*) FROM products p WHERE p.shop_id=s.id) AS product_count,
                    (SELECT COUNT(*) FROM orders o WHERE o.shop_id=s.id AND o.payment_status=\'paid\') AS order_count
             FROM shops s JOIN accounts a ON a.id=s.account_id ORDER BY s.id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset
        );
        $this->page('shops', 'Shops', 'shops', compact('shops', 'total', 'page', 'pages', 'perPage'));
    }

    public function shop_form($id = null) {

        $this->module('guard');
        $this->boot();
        $shop = null;
        if ($id != null) {
            $rows = $this->rows('SELECT s.*, a.name AS owner_name, a.phone AS owner_phone, a.email AS owner_email
                                 FROM shops s JOIN accounts a ON a.id=s.account_id WHERE s.id=:i', ['i' => (int) $id]);
            if (!$rows) { http_response_code(404); die('Shop not found.'); }
            $shop = $rows[0];
        }
        
        $defaults = [
            'can_edit_banner'  => (int) $this->guard->_setting('default_can_edit_banner', 1),
            'can_edit_price'   => (int) $this->guard->_setting('default_can_edit_price', 1),
            'can_add_products' => (int) $this->guard->_setting('default_can_add_products', 1),
            'max_products'     => (int) $this->guard->_setting('default_max_products', 50),
        ];
        $this->page('shop_form', $shop ? 'Edit shop' : 'Add shop', 'shops', compact('shop', 'defaults'));
    }

    public function shop_save() {
        $this->module('guard');
        $this->boot();
        $this->guard = $this->module('guard');
        $this->guard->_csrf_check();

        $id = (int) ($_POST['id'] ?? 0);
        $back = $id ? 'admin/shop_form/' . $id : 'admin/shop_form';

        $shop_name = trim($_POST['shop_name'] ?? '');
        $owner = trim($_POST['owner_name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = preg_replace('/\D+/', '', $_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($shop_name === '' || $owner === '') {
            $this->guard->_flash('Enter the shop name and the owner name.', 'err');
            redirect($back);
        }
        if ($email === '' && $phone === '') {
            $this->guard->_flash('Enter a phone number or an email so the owner can log in.', 'err');
            redirect($back);
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->guard->_flash('That email address is not valid.', 'err');
            redirect($back);
        }

        $shop_row = $id ? $this->rows('SELECT * FROM shops WHERE id=:i', ['i' => $id]) : [];
        $account_id = $shop_row ? (int) $shop_row[0]->account_id : 0;

        // Phone and email must be unique across accounts.
        $clash = $this->rows(
            'SELECT id FROM accounts WHERE id<>:me AND ((:e<>\'\' AND email=:e2) OR (:p<>\'\' AND phone=:p2)) LIMIT 1',
            ['me' => $account_id, 'e' => $email, 'e2' => $email, 'p' => $phone, 'p2' => $phone]
        );
        if ($clash) {
            $this->guard->_flash('Another account already uses that phone number or email.', 'err');
            redirect($back);
        }

        if (!$id && strlen($password) < 8) {
            $this->guard->_flash('Set a password of at least 8 characters for the new owner.', 'err');
            redirect($back);
        }
        if ($id && $password !== '' && strlen($password) < 8) {
            $this->guard->_flash('A new password must be at least 8 characters.', 'err');
            redirect($back);
        }

        $account = ['name' => $owner, 'email' => $email !== '' ? $email : null, 'phone' => $phone !== '' ? $phone : null];
        $perms = [
            'is_active'        => empty($_POST['is_active']) ? 0 : 1,
            'can_edit_banner'  => empty($_POST['can_edit_banner']) ? 0 : 1,
            'can_edit_price'   => empty($_POST['can_edit_price']) ? 0 : 1,
            'can_add_products' => empty($_POST['can_add_products']) ? 0 : 1,
            'max_products'     => max(0, min(5000, (int) ($_POST['max_products'] ?? 50))),
        ];

        $details = [
            'shop_name'    => $shop_name,
            'address' => substr(trim($_POST['address'] ?? ''), 0, 255),
            'city'    => substr(trim($_POST['city'] ?? ''), 0, 80),
        ];

        if ($id) {
            if ($password !== '') {
                $account['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $account['is_active'] = $perms['is_active'];
            $this->model->update($account_id, $account, 'accounts');
            $this->model->update($id, $details + $perms, 'shops');
            $this->guard->_flash('Shop saved.');
        } else {
            $account['role'] = 'owner';
            $account['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            $account_id = $this->model->insert($account, 'accounts');
            $details['account_id'] = $account_id;
            $details['slug'] = $this->unique_slug($shop_name);
            $this->model->insert($details + $perms, 'shops');
            $this->guard->_flash('Shop created. The owner can log in with the phone or email you entered.');
        }
        redirect('admin/shops');
    }

    // ---------- Products from every shop ----------

    public function products() {
        $this->boot();
        $perPage = 10;
        $shop_id = (int) ($_GET['shop'] ?? 0);
        $status = $_GET['status'] ?? '';

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM products', [], 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        $sql = 'SELECT p.*, s.shop_name FROM products p JOIN shops s ON s.id=p.shop_id WHERE 1=1';
        $params = [];
        if ($shop_id) { $sql .= ' AND p.shop_id=:s'; $params['s'] = $shop_id; }
        if (in_array($status, ['pending', 'approved', 'rejected'], true)) { $sql .= ' AND p.status=\'' . $status . '\''; }
        $products = $this->rows($sql . ' ORDER BY p.id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset, $params);
        $shops = $this->rows('SELECT id, shop_name FROM shops ORDER BY shop_name');
        $this->page('products', 'All products', 'products', compact('products', 'shops', 'shop_id', 'status', 'total', 'page', 'pages', 'perPage'));
    }

    public function product_status() {
        $this->module('guard');
        $this->boot();
       
        $this->guard->_csrf_check();
        $new = $_POST['status'] ?? '';
        if (!in_array($new, ['approved', 'rejected', 'pending'], true)) {
            redirect('admin/products');
        }
        $this->model->update((int) ($_POST['id'] ?? 0), ['status' => $new], 'products');
        $this->guard->_flash('Product set to ' . strtolower(/*ml_status_label(*/$new/*)*/) . '.');
        redirect($_POST['return'] ?? 'admin/products');
    }

    // ---------- Orders from every shop ----------

    public function orders() {
        $this->boot();
        $perPage = 3;
        $shop_id = (int) ($_GET['shop'] ?? 0);
        $status = $_GET['status'] ?? '';
        $sql = 'SELECT o.*, s.shop_name FROM orders o JOIN shops s ON s.id=o.shop_id WHERE o.payment_status=\'paid\'';

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM orders', [], 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;



        $params = [];
        if ($shop_id) { $sql .= ' AND o.shop_id=:s'; $params['s'] = $shop_id; }
        if (in_array($status, ['pending', 'processing', 'on_the_way', 'delivered', 'declined'], true)) { $sql .= ' AND o.status=\'' . $status . '\''; }
        $payout = $_GET['payout'] ?? '';
        if ($payout === 'paid') { $sql .= ' AND o.shop_paid_at IS NOT NULL'; }
        elseif ($payout === 'unpaid') { $sql .= ' AND o.shop_paid_at IS NULL AND o.status<>\'declined\''; }
        else { $payout = ''; }
        $orders = $this->rows($sql . ' ORDER BY o.id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset, $params);
        $shops = $this->rows('SELECT id, shop_name FROM shops ORDER BY shop_name');
        $this->page('orders', 'All orders', 'orders', compact('orders', 'shops', 'shop_id', 'status', 'payout', 'total', 'page', 'pages', 'perPage'));
    }

    public function order($id = 0) {
        $this->boot();
        $rows = $this->rows('SELECT o.*, s.shop_name FROM orders o JOIN shops s ON s.id=o.shop_id WHERE o.id=:i', ['i' => (int) $id]);
        if (!$rows) { http_response_code(404); die('Order not found.'); }
        $order = $rows[0];
        $items = $this->rows('SELECT * FROM order_items WHERE order_id=:o', ['o' => $order->id]);
        $notes = $this->rows('SELECT * FROM order_notifications WHERE order_id=:o ORDER BY id DESC', ['o' => $order->id]);
        // Admin reuses the owner's order screen, read-only.
        $this->page('order_detail', 'Order ' . $order->reference, 'orders',
            ['order' => $order, 'items' => $items, 'notes' => $notes, 'is_admin' => true], 'owner');
    }

    // ---------- Paying shops ----------

    /** Records that the admin paid the shop for an order, with a time stamp, and can tell the shop. */
    public function payout_save() {
        $this->module('guard');
        $this->module('notify');
        $this->boot();
        
        $this->guard->_csrf_check();

        $id = (int) ($_POST['id'] ?? 0);
        $back = 'admin/order/' . $id;
        $rows = $this->rows('SELECT * FROM orders WHERE id=:i', ['i' => $id]);
        if (!$rows) { http_response_code(404); die('Order not found.'); }
        $order = $rows[0];

        if ($order->payment_status !== 'paid' || $order->status === 'declined') {
            $this->guard->_flash('Only paid orders that were not declined can be paid out to a shop.', 'err');
            redirect($back);
        }
        if ($order->shop_paid_at) {
            $this->guard->_flash('This order is already marked as paid to the shop.', 'err');
            redirect($back);
        }
        $amount = $_POST['amount'] ?? '';
        if (!is_numeric($amount) || $amount <= 0 || $amount > 1000000000) {
            $this->guard->_flash('Enter the amount you paid the shop (more than zero).', 'err');
            redirect($back);
        }

        $this->model->update($id, [
            'shop_paid_at'     => date('Y-m-d H:i:s'),
            'shop_paid_amount' => round((float) $amount, 2),
            'shop_payout_ref'  => substr(trim($_POST['reference'] ?? ''), 0, 60) ?: null,
            'shop_paid_by'     => $this->user['id'],
        ], 'orders');

        if (!empty($_POST['notify'])) {
            $sent = $this->notify->_shop_payout($id);
            $parts = [];
            if ($sent['sms'] === true)    { $parts[] = 'SMS sent'; }
            if ($sent['sms'] === false)   { $parts[] = 'SMS failed'; }
            if ($sent['email'] === true)  { $parts[] = 'email sent'; }
            if ($sent['email'] === false) { $parts[] = 'email failed'; }
            if (!$parts) { $parts[] = 'the owner has no phone or email on file'; }
            $failed = $sent['sms'] === false || $sent['email'] === false;
            $this->guard->_flash('Marked as paid to the shop; ' . implode(', ', $parts) . '.', $failed ? 'warn' : 'ok');
        } else {
            $this->guard->_flash('Marked as paid to the shop.');
        }
        redirect($back);
    }

    /** For mistakes: clears the payout record. The shop sees the order as awaiting payment again. */
    public function payout_undo() {
        $this->module('guard');
        $this->boot();
        
        $this->guard->_csrf_check();
        $id = (int) ($_POST['id'] ?? 0);
        $this->model->update($id, ['shop_paid_at' => null, 'shop_paid_amount' => null, 'shop_payout_ref' => null, 'shop_paid_by' => null], 'orders');
        $this->guard->_flash('Payout record removed.');
        redirect('admin/order/' . $id);
    }

    // ---------- Settings ----------

    public function settings() {
        $this->module('guard');
        $this->boot();
        $this->guard->module('guard');
        $s = [];
        foreach (['auto_approve_products', 'default_max_products', 'default_can_edit_banner', 'default_can_edit_price',
                  'default_can_add_products', 'max_image_mb', 'categories'] as $name) {
            $s[$name] = $this->guard->_setting($name, '');
        }
        $this->page('settings', 'Settings', 'settings', ['s' => $s]);
    }

    public function settings_save() {
        $this->module('guard');
        $this->boot();
        $this->guard->_csrf_check();

        $cats = array_filter(array_map('trim', explode(',', $_POST['categories'] ?? '')));
        $cats = array_unique(array_map(fn($c) => substr(str_replace(',', '', $c), 0, 40), $cats));
        if (!$cats) { $cats = ['Other']; }

        $values = [
            'auto_approve_products'    => empty($_POST['auto_approve_products']) ? '0' : '1',
            'default_max_products'     => (string) max(1, min(5000, (int) ($_POST['default_max_products'] ?? 50))),
            'default_can_edit_banner'  => empty($_POST['default_can_edit_banner']) ? '0' : '1',
            'default_can_edit_price'   => empty($_POST['default_can_edit_price']) ? '0' : '1',
            'default_can_add_products' => empty($_POST['default_can_add_products']) ? '0' : '1',
            'max_image_mb'             => (string) max(1, min(10, (int) ($_POST['max_image_mb'] ?? 3))),
            'categories'               => implode(',', $cats),
        ];
        foreach ($values as $name => $value) {
            $this->model->query_bind(
                'INSERT INTO settings (name, value) VALUES (:n, :v) ON DUPLICATE KEY UPDATE value=:v2',
                ['n' => $name, 'v' => $value, 'v2' => $value]
            );
        }
        $this->guard->_flash('Settings saved. New shops start with these defaults.');
        redirect('admin/settings');
    }

    // ---------- Internals ----------

    private function boot() {
        $this->module('guard');

         $this->user = $this->guard->_require('admin');
        //$this->user = $this->module('guard')->_require('admin');
        //$this->user = $this->admin;
    }

    private function rows($sql, $params = []) {
        return $this->model->query_bind($sql, $params, 'object');
    }

    private function scalar($sql) {
        $rows = $this->model->query($sql, 'object');
        return $rows ? $rows[0]->n : 0;
    }

    private function unique_slug($name) {
        $this->module('guard');
        $base = $this->guard->_slug($name);
        $slug = $base;
        $i = 2;
        while ($this->rows('SELECT id FROM shops WHERE slug=:s', ['s' => $slug])) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function get_category($id) {

        $category = $this->rows('SELECT name FROM categories WHERE id=:i', ['i' => $id]);
        return $category[0]->name;

    }    

    private function page($view, $title, $nav, $extra = [], $view_module = 'admin') {
        $this->module('guard');
        $data = $extra + [
            'view_module' => $view_module,
            'view_file'   => $view,
            'page_title'  => $title,
            'nav_active'  => $nav,
            'role'        => 'admin',
            'user'        => $this->user,
            'csrf'        => $this->guard->_csrf_token(),
            'flash'       => $this->guard->_take_flash(),
            'pending'     => $this->scalar('SELECT COUNT(*) n FROM products WHERE status=\'pending\''),
        ];
        $this->template('dashboard', $data);
    }
}
