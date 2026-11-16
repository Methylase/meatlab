<?php
class Cart extends Trongate {
    
    private function lines() {
        $cart = $_SESSION['cart'] ?? ['shop_id' => 0, 'items' => []]; $lines = []; $sub = 0;
        foreach ($cart['items'] as $pid => $kg) {
            $p = $this->model->get_one_where('id', $pid, 'products'); if (!$p) continue;
            $t = round($p->price_per_kg * $kg); $sub += $t; $lines[] = ['p' => $p, 'kg' => $kg, 'total' => $t];
        }
        return [$lines, $sub, $cart['shop_id']];
    }
    
    public function index() {
        [$data['lines'], $data['sub'], $data['shop_id']] = $this->lines();
        $data['shop'] = $data['shop_id'] ? $this->model->get_one_where('id', $data['shop_id'], 'shops') : false;
        $data['light'] = true; $data['title'] = 'Your cart | Meatlab';
        $data['view_module'] = 'cart'; $data['view_file'] = 'cart'; $this->template('meatlab', $data);
    }

    public function add() {
        $p = $this->model->get_one_where('id', (int) post('product_id', true), 'products'); if (!$p) redirect('shops');
        $kg = max((float) $p->min_kg, round((float) post('kg', true), 1));
        if (($_SESSION['cart']['shop_id'] ?? $p->shop_id) != $p->shop_id) $_SESSION['cart'] = [];
        $_SESSION['cart']['shop_id'] = $p->shop_id;
        $_SESSION['cart']['items'][$p->id] = ($_SESSION['cart']['items'][$p->id] ?? 0) + $kg;
        redirect('cart');
    }
    
    public function update() {
        foreach ((array) ($_POST['kg'] ?? []) as $pid => $kg) { $kg = (float) $kg; if ($kg > 0) $_SESSION['cart']['items'][(int) $pid] = $kg; else unset($_SESSION['cart']['items'][(int) $pid]); }
        redirect('cart');
    }

    public function remove() { unset($_SESSION['cart']['items'][(int) segment(3)]); redirect('cart'); }
    
    public function _summary() { return $this->lines(); }
}
