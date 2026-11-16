<?php
/**
 * Owner dashboard: a shop owner sees and changes ONLY their own shop.
 * Every query below is filtered by $this->shop->id, which comes from the
 * logged-in account, never from the URL or the form.
 */
class Owner extends Trongate {

    private $user;
    private $shop;
    private $units = ['kg', 'piece', 'pack', 'bundle'];

    // ---------- Pages ----------

    public function index() {
        $perPage = 10;
        $this->boot();
        $id = $this->shop->id;
        $stats = [
            'new'        => $this->count('SELECT COUNT(*) n FROM orders WHERE shop_id=:s AND status=\'pending\' AND payment_status=\'paid\'', $id),
            'processing' => $this->count('SELECT COUNT(*) n FROM orders WHERE shop_id=:s AND status IN (\'processing\',\'on_the_way\')', $id),
            'delivered'  => $this->count('SELECT COUNT(*) n FROM orders WHERE shop_id=:s AND status=\'delivered\'', $id),
            'products'   => $this->count('SELECT COUNT(*) n FROM products WHERE shop_id=:s', $id),
        ];
        
        $where  = 'shop_id = :s AND payment_status = \'paid\'';
        $params = ['s' => $id];


        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM orders WHERE ' . $where, $params, 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        $money = $this->model->query_bind(
            'SELECT COALESCE(SUM(shop_paid_amount),0) n FROM orders WHERE shop_id=:s AND shop_paid_at IS NOT NULL',
            ['s' => $id], 'object'
        );
        $stats['received'] = $money[0]->n;
        $stats['awaiting'] = $this->count('SELECT COUNT(*) n FROM orders WHERE shop_id=:s AND payment_status=\'paid\' AND status<>\'declined\' AND shop_paid_at IS NULL', $id);
        $recent = $this->model->query_bind(
            'SELECT * FROM orders WHERE shop_id=:s AND payment_status=\'paid\' ORDER BY id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset,
            ['s' => $id], 'object'
        );

        $this->page('index', 'Overview', 'home', compact('stats', 'recent',  'total', 'page', 'pages', 'perPage'));
      
    }

    public function shop() {
        $this->boot();
        $this->page('shop', 'Shop and banner', 'shop');
    }

    public function shop_save() {
        $this->module('guard');
        $this->boot();
        $this->guard->_csrf_check();

        $update = [
            'address'     => substr(trim($_POST['address'] ?? ''), 0, 255),
            'city'        => substr(trim($_POST['city'] ?? ''), 0, 80),
            'phone'       => substr(preg_replace('/[^\d+]/', '', $_POST['phone'] ?? ''), 0, 20),
            'description' => substr(trim($_POST['description'] ?? ''), 0, 1000),
        ];

        if (!empty($_FILES['banner']['name'])) {
            if (!$this->shop->can_edit_banner) {
                $this->guard->_flash('Banner changes are turned off for your shop. Contact the admin.', 'err');
                redirect('owner/shop');
            }
            $up = $this->guard->_save_image('banner', 'banners');
            if ($up['error']) {
                $this->guard->_flash($up['error'], 'err');
                redirect('owner/shop');
            }
            $this->guard->_delete_image('banners', $this->shop->banner);
            $update['banner'] = $up['file'];
        }

        $this->model->update($this->shop->id, $update, 'shops');
        $this->guard->_flash('Shop saved.');
        redirect('owner/shop');
    }

    public function products() {
        $this->boot();
        $perPage = 10;

        // Build the WHERE clause once, reuse it for the count and the page query
        $where  = 'shop_id = :s';
        $params = ['s' => $this->shop->id];

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM products WHERE ' . $where, $params, 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        $products = $this->model->query_bind(
            'SELECT * FROM products WHERE shop_id=:s ORDER BY id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset, ['s' => $this->shop->id], 'object'
        );

        $this->page('products', 'My products', 'products', compact('products', 'total', 'page', 'pages', 'perPage'));

    }

    public function product_form($id = null) {
        $this->boot();
        $product = null;
        if ($id !=null) {
            $product = $this->own_product((int) $id);
        } elseif (!$this->shop->can_add_products) {
            $this->module('guard')->_flash('Adding products is turned off for your shop. Contact the admin.', 'err');
            redirect('owner/products');
        }
        $categories = $this->categories();
        $units = $this->units;
        $this->page('product_form', $product ? 'Edit product' : 'Add product', 'products', compact('product', 'categories', 'units'));
    }

    public function product_save() {
        $this->module('guard');
        $this->boot();
        $this->guard->_csrf_check();

        $id = (int) ($_POST['id'] ?? 0);
        $existing = $id ? $this->own_product($id) : null;
        $back = $id ? 'owner/product_form/' . $id : 'owner/product_form';

        $name = trim($_POST['name'] ?? '');
        $price = $_POST['price'] ?? '';
        $unit = $_POST['unit'] ?? 'kg';
        $category = $_POST['category'] ?? '';

        if ($name === '' || strlen($name) > 140) {
            $this->guard->_flash('Enter a product name (up to 140 characters).', 'err');
            redirect($back);
        }
        if (!is_numeric($price) || $price <= 0 || $price > 100000000) {
            $this->guard->_flash('Enter a price greater than zero.', 'err');
            redirect($back);
        }
        if (!in_array($unit, $this->units, true)) {
            $unit = 'kg';
        }
        if (!in_array($category, $this->categories(), true)) {
            $category = 'Other';
        }

        if (!$existing) {
            if (!$this->shop->can_add_products) {
                $this->guard->_flash('Adding products is turned off for your shop.', 'err');
                redirect('owner/products');
            }
            $total = $this->count('SELECT COUNT(*) n FROM products WHERE shop_id=:s', $this->shop->id);
            if ($total >= (int) $this->shop->max_products) {
                $this->guard->_flash('You reached your limit of ' . (int) $this->shop->max_products . ' products. Ask the admin to raise it.', 'err');
                redirect('owner/products');
            }
        } elseif (!$this->shop->can_edit_price && (float) $price !== (float) $existing->price) {
            $this->guard->_flash('Price changes are turned off for your shop. Contact the admin.', 'err');
            redirect($back);
        }

        $up = $this->guard->_save_image('image', 'products');
        if ($up['error']) {
            $this->guard->_flash($up['error'], 'err');
            redirect($back);
        }

        $data = [
            'name'        => $name,
            'category'    => $category,
            'description' => substr(trim($_POST['description'] ?? ''), 0, 1000),
            'price_per_kg'       => round((float) $price, 2),
            'unit'        => $unit,
            'in_stock'    => empty($_POST['in_stock']) ? 0 : 1,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        if ($up['file']) {
            $data['image'] = $up['file'];
            if ($existing) {
                $this->guard->_delete_image('products', $existing->image);
            }
        }

        if ($existing) {
            $this->model->update($existing->id, $data, 'products');
            $this->guard->_flash('Product updated.');
        } else {
            $data['shop_id'] = $this->shop->id;
            $data['status'] = $this->guard->_setting('auto_approve_products', '1') === '1' ? 'approved' : 'pending';
            $this->model->insert($data, 'products');
            $this->guard->_flash($data['status'] === 'approved'
                ? 'Product added and live.'
                : 'Product added. It goes live after the admin approves it.');
        }
        redirect('owner/products');
    }


    private function get_category_id($name) {

        $category = $this->model->query_bind('SELECT id FROM categories WHERE name=:name', ['name' => $name], 'object');
        return $category[0]->id;
    }  

    public function product_delete() {
        $this->module('guard');
        $this->boot();
        $this->guard->_csrf_check();
        $product = $this->own_product((int) ($_POST['id'] ?? 0));

        $in_orders = $this->count('SELECT COUNT(*) n FROM order_items WHERE product_id=:s', $product->id);
        if ($in_orders > 0) {
            // Keep order history intact: hide it instead of deleting.
            $this->model->update($product->id, ['in_stock' => 0], 'products');
            $this->guard->_flash('This product is in past orders, so it was marked out of stock instead of deleted.');
        } else {
            $this->guard->_delete_image('products', $product->image);
            $this->model->delete($product->id, 'products');
            $this->guard->_flash('Product deleted.');
        }
        redirect('owner/products');
    }

    /*public function orders() {
        $this->boot();
        $filter = $_GET['status'] ?? 'all';
        $allowed = ['pending', 'processing', 'on_the_way', 'delivered', 'declined'];
        $sql = 'SELECT * FROM orders WHERE shop_id=:s AND payment_status=\'paid\'';
        if (in_array($filter, $allowed, true)) {
            $sql .= ' AND status=\'' . $filter . '\'';
        } else {
            $filter = 'all';
        }
        $pay = $_GET['pay'] ?? '';
        if ($pay === 'received') { $sql .= ' AND shop_paid_at IS NOT NULL'; }
        elseif ($pay === 'awaiting') { $sql .= ' AND shop_paid_at IS NULL AND status<>\'declined\''; }
        else { $pay = ''; }
        $orders = $this->model->query_bind($sql . ' ORDER BY id DESC LIMIT 200', ['s' => $this->shop->id], 'object');
        $this->page('orders', 'Orders', 'orders', compact('orders', 'filter', 'pay'));
    }*/

    public function orders() {
        $this->boot();
        $perPage = 10;
        $allowed = ['pending', 'processing', 'on_the_way', 'delivered', 'declined'];

        $filter = $_GET['status'] ?? 'all';
        if (!in_array($filter, $allowed, true)) { $filter = 'all'; }

        $pay = $_GET['pay'] ?? '';
        if (!in_array($pay, ['received', 'awaiting'], true)) { $pay = ''; }

        // Build the WHERE clause once, reuse it for the count and the page query
        $where  = 'shop_id = :s AND payment_status = \'paid\'';
        $params = ['s' => $this->shop->id];

        if ($filter !== 'all') {
            $where .= ' AND status = :st';
            $params['st'] = $filter;
        }
        if ($pay === 'received') {
            $where .= ' AND shop_paid_at IS NOT NULL';
        } elseif ($pay === 'awaiting') {
            $where .= ' AND shop_paid_at IS NULL AND status <> \'declined\'';
        }

        $count = $this->model->query_bind('SELECT COUNT(*) AS n FROM orders WHERE ' . $where, $params, 'object');
        $total = (int) ($count[0]->n ?? 0);

        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);
        $offset = ($page - 1) * $perPage;

        // LIMIT/OFFSET are cast to int above, so they are safe to inline
        $orders = $this->model->query_bind(
            'SELECT * FROM orders WHERE ' . $where . ' ORDER BY id DESC LIMIT ' . $perPage . ' OFFSET ' . $offset,
            $params, 'object'
        );

        $this->page('orders', 'Orders', 'orders',
            compact('orders', 'filter', 'pay', 'total', 'page', 'pages', 'perPage'));
    }        

    public function order($id = 0) {
        $this->boot();
        $order = $this->own_order((int) $id);
        $items = $this->model->query_bind('SELECT * FROM order_items WHERE order_id=:o', ['o' => $order->id], 'object');
        $notes = $this->model->query_bind('SELECT * FROM order_notifications WHERE order_id=:o ORDER BY id DESC', ['o' => $order->id], 'object');
        $this->page('order_detail', 'Order ' . $order->reference, 'orders', ['order' => $order, 'items' => $items, 'notes' => $notes, 'is_admin' => false]);
    }

    // ---------- Order actions (POST only) ----------

    public function accept()   { $this->move('pending',    'processing', 'processing'); }
    public function dispatch() { $this->move('processing', 'on_the_way', 'on_the_way'); }
    public function deliver()  { $this->move('on_the_way', 'delivered',  'delivered'); }
    public function decline()  { $this->move('pending',    'declined',   'declined'); }

    /** Moves an order one step, then tells the customer. */
    private function move($from, $to, $event) {
        $this->module('guard');
        $this->module('notify');
        $this->boot();
        $this->guard->_csrf_check();
        $order = $this->own_order((int) ($_POST['id'] ?? 0));
        $back = 'owner/order/' . $order->id;

        if ($order->payment_status !== 'paid' || $order->status !== $from) {
            $this->guard->_flash('This order is already ' . strtolower(ml_status_label($order->status)) . '. Refresh the page.', 'err');
            redirect($back);
        }

        $stamp = ['processing' => 'accepted_at', 'on_the_way' => 'dispatched_at', 'delivered' => 'delivered_at'][$to] ?? null;
        $update = ['status' => $to];
        if ($stamp) {
            $update[$stamp] = date('Y-m-d H:i:s');
        }
        $this->model->update($order->id, $update, 'orders');

        $sent = $this->notify->_order_event($order->id, $event);
        $this->guard->_flash($this->summary($to, $sent), ($sent['sms'] === false || $sent['email'] === false) ? 'warn' : 'ok');
        redirect($back);
    }

    private function summary($to, $sent) {
        $titles = ['processing' => 'Order accepted', 'on_the_way' => 'Marked ready and on the way',
                   'delivered' => 'Marked delivered', 'declined' => 'Order declined'];
        $parts = [];
        foreach (['sms' => 'SMS', 'email' => 'email'] as $key => $label) {
            if ($sent[$key] === true)  { $parts[] = $label . ' sent'; }
            if ($sent[$key] === false) { $parts[] = $label . ' failed (see the notification log below)'; }
        }
        if ($sent['sms'] === null && $sent['email'] === null) {
            $parts[] = 'the customer left no phone or email';
        }
        return $titles[$to] . ': ' . implode(', ', $parts) . '.';
    }

    // ---------- Internals ----------

    private function boot() {
        $this->module('guard');
        $this->user = $this->guard->_require('owner');
        $rows = $this->model->query_bind('SELECT * FROM shops WHERE account_id=:a LIMIT 1', ['a' => $this->user['id']], 'object');
        $this->shop = $rows ? $rows[0] : null;
        if (!$this->shop) {
            die('No shop is linked to this account yet. Ask the admin to set one up.');
        }
        if (!$this->shop->is_active) {
            die('Your shop is switched off. Contact the Meatlab admin.');
        }
    }

    private function count($sql, $shop_id) {
        $rows = $this->model->query_bind($sql, ['s' => $shop_id], 'object');
        return (int) $rows[0]->n;
    }

    private function own_product($id) {
        $rows = $this->model->query_bind('SELECT * FROM products WHERE id=:i AND shop_id=:s', ['i' => $id, 's' => $this->shop->id], 'object');
        if (!$rows) {
            http_response_code(404);
            die('Product not found.');
        }
        return $rows[0];
    }

    private function own_order($id) {
        $rows = $this->model->query_bind('SELECT * FROM orders WHERE id=:i AND shop_id=:s', ['i' => $id, 's' => $this->shop->id], 'object');
        if (!$rows) {
            http_response_code(404);
            die('Order not found.');
        }
        return $rows[0];
    }

    private function categories() {
        $this->module('guard');
        $list = explode(',', $this->guard->_setting('categories', 'Other'));
        return array_values(array_filter(array_map('trim', $list)));
    }

    private function page($view, $title, $nav, $extra = []) {
         $this->module('guard');
        $data = $extra + [
            'view_module' => 'owner',
            'view_file'   => $view,
            'page_title'  => $title,
            'nav_active'  => $nav,
            'role'        => 'owner',
            'user'        => $this->user,
            'shop'        => $this->shop,
            'csrf'        => $this->guard->_csrf_token(),
            'flash'       => $this->guard->_take_flash(),
            'pending'     => $this->count('SELECT COUNT(*) n FROM orders WHERE shop_id=:s AND status=\'pending\' AND payment_status=\'paid\'', $this->shop->id),
        ];
        $this->template('dashboard', $data);
    }
}
