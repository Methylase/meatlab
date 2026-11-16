<?php
class Shops extends Trongate {
    public function index(): void {
        $q = trim($_GET['q'] ?? '');
        $sql = 'SELECT * FROM shops WHERE (address LIKE :q OR city LIKE :q OR shop_name LIKE :q) ORDER BY rating DESC';
        $data['shops'] = $this->model->query_bind($sql, ['q' => '%' . $q . '%'], 'object');
        $data['q'] = $q; $data['light'] = true; $data['title'] = 'Meat shops | Meatlab';
        $data['view_module'] = 'shops'; 
        $data['view_file'] = 'results';
        $this->template('meatlab', $data);
    }

    public function show (): void {
        $shop = $this->model->get_one_where('slug', segment(3), 'shops');
        if ($shop === false) redirect('shops');
 
        $this->update_shop_top_products($shop->id);
        $data['shop'] = $shop;
        $data['products'] = $this->model->query_bind('SELECT * FROM products WHERE shop_id = :s ORDER BY RAND() DESC, id', ['s' => $shop->id], 'object');
        $data['top'] = array_slice(array_filter($data['products'], fn($p) => $p->is_top), 0, 4);
        $data['cats'] = $this->model->query('SELECT * FROM categories', 'object');
        $data['title'] = $shop->shop_name . ' | Meatlab';
        $data['view_module'] = 'shops'; $data['view_file'] = 'shop';
        $this->template('meatlab', $data);
    }

    private function update_shop_top_products($shop_id) {

        $shop_id = (int) $shop_id;

        $sales_sql = "
            SELECT oi.product_id, SUM(oi.quantity) AS total_sales 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE p.shop_id = :shop_id
            GROUP BY oi.product_id 
            ORDER BY total_sales DESC 
            LIMIT 5
        ";
        
        $params['shop_id'] = $shop_id;
        $top_items = $this->model->query_bind($sales_sql, $params, 'object');

        if (!empty($top_items)) {
            $top_product_ids = [];
            foreach ($top_items as $item) {
                $top_product_ids[] = (int) $item->product_id;
            }

            $clear_sql = "UPDATE products SET is_top = 0 WHERE shop_id = :shop_id AND is_top = 1";
            $this->model->query_bind($clear_sql, $params);

            $id_string = implode(', ', $top_product_ids);
            $update_sql = "UPDATE products SET is_top = 1 WHERE shop_id = :shop_id AND id IN ($id_string)";
            $this->model->query_bind($update_sql, $params);
        }

    }    
}


