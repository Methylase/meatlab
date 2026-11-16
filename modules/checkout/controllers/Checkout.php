<?php
require_once APPPATH . 'config/paystack.php';
require_once APPPATH . 'config/meatlab.php';
class Checkout extends Trongate {
    function index() {
        [$data['lines'], $data['sub']] = Modules::run('cart/_summary');
        if (!$data['lines']) redirect('cart');
        $data['errors'] = $_SESSION['co_err'] ?? ''; unset($_SESSION['co_err']);
        $data['light'] = true; $data['title'] = 'Checkout | Meatlab';
        $data['view_module'] = 'checkout'; $data['view_file'] = 'checkout'; $this->template('meatlab', $data);
    }

    function pay() {
        [$lines, $sub, $shop_id] =  Modules::run('cart/_summary'); if (!$lines) redirect('cart');
        $this->validation->set_rules('name', 'name', 'required');
        $this->validation->set_rules('phone', 'phone', 'required');
        $this->validation->set_rules('email', 'email', 'required|valid_email_address');
        $this->validation->set_rules('address', 'address', 'required');
        if ($this->validation->run() !== true) { $_SESSION['co_err'] = validation_errors(); redirect('checkout'); }
        $total = $sub + DELIVERY_FEE; $ref = 'ML' . time() . random_int(100, 999);
        $oid = $this->model->insert(['reference' => $ref, 'shop_id' => $shop_id, 'customer_name' => post('name', true), 'customer_phone' => post('phone', true),
            'customer_email' => post('email', true), 'delivery_address' => post('address', true), 'subtotal' => $sub, 'delivery' => DELIVERY_FEE, 'total' => $total], 'orders');
        foreach ($lines as $l) $this->model->insert(['order_id' => $oid, 'product_id' => $l['p']->id, 'product_name' => $l['p']->name, 'kg' => $l['kg'], 'unit_price' => $l['p']->price_per_kg], 'order_items');
        $res = $this->paystack('POST', 'transaction/initialize', ['email' => post('email', true), 'amount' => $total * 100, 'reference' => $ref, 'callback_url' => BASE_URL . 'checkout/callback']);
        if (empty($res['status'])) { $_SESSION['co_err'] = 'Payment could not start. Please try again.'; redirect('checkout'); }
        redirect($res['data']['authorization_url']);



    }

    /*function pay()
    {
        // 1. Get cart information
        [$lines, $sub, $shop_id] = Modules::run('cart/_summary');

        if (!$lines) {
            redirect('cart');
        }

        // 2. Validate checkout form
        $this->validation->set_rules('name', 'Name', 'required');
        $this->validation->set_rules('phone', 'Phone', 'required');
        $this->validation->set_rules(
            'email',
            'Email',
            'required|valid_email_address'
        );
        $this->validation->set_rules('address', 'Address', 'required');

        if ($this->validation->run() !== true) {
            $_SESSION['co_err'] = validation_errors();
            redirect('checkout');
        }

        $customerAddress = trim(post('address', true));
        $email = post('email', true);

        $totalWeight = 0;

        foreach ($lines as $line) {
            $totalWeight += (float) $line['kg'];
        }

        // Fetch shop information
        $shopRows = $this->model->get_one_where(
            'id',
            (int)$shop_id,
            'shops'
        );

        $shop = $shopRows ?? null;

        if (!$shop || empty($shop->address)) {
            $_SESSION['co_err'] =
                'We could not verify the meat shop delivery location.';
            redirect('checkout');
        }

        $shopAddress = $shop->address;

        // Calculate actual driving distance using Google Maps
        $distanceKm = $this->_fetch_google_distance(
            $shopAddress,
            $customerAddress
        );

        if ($distanceKm === false) {
            $_SESSION['co_err'] =
                'We could not verify your delivery address. ' .
                'Please check the street name or add a nearby landmark.';
            redirect('checkout');
        }

        // Block delivery beyond 10 km
        $maxAllowedDistance = 10.0;

        if ($distanceKm > $maxAllowedDistance) {
            $_SESSION['co_err'] =
                'Your location is outside our delivery zone. ' .
                'We currently deliver within 10 km of the meat shop.';
            redirect('checkout');
        }

        // Calculate the delivery fee based on distance
        if ($distanceKm <= 3.0) {
            $deliveryFee = 400;
        } elseif ($distanceKm <= 6.0) {
            $deliveryFee = 700;
        } else {
            $deliveryFee = 1200;
        }

        // Apply free delivery promotion for orders of 5–10 kg
        $promoApplied = false;

        if ($totalWeight >= 5.0 && $totalWeight <= 10.0) {
            $deliveryFee = 0;
            $promoApplied = true;
        }

        // Add platform service fee
        $platformServiceFee = 100;

        // Calculate final payable amount
        $total = $sub + $deliveryFee + $platformServiceFee;

        // Generate payment reference
        $ref = 'ML' . time() . random_int(100, 999);

        // Save order
        $oid = $this->model->insert(
            [   
                'reference' => $ref, 
                'shop_id' => $shop_id, 'customer_name' => post('name', true), 
                'customer_phone' => post('phone', true),
                'customer_email' => post('email', true), 
                'delivery_address' => post('address', true), 
                'subtotal' => $sub, 'delivery' => DELIVERY_FEE, 
                'total' => $total
            ], 
            'orders'
        );

        // Save order items
        foreach ($lines as $line) {
            $this->model->insert(
                [   
                    'order_id' => $oid, 'product_id' => $l['p']->id, 
                    'product_name' => $l['p']->name, 'kg' => $l['kg'], 
                    'unit_price' => $l['p']->price_per_kg
                ], 
                'order_items'
            );
        }

        // Initialize Paystack with the correct final amount
        $res = $this->paystack(
            'POST',
            'transaction/initialize',
            [
                'email' => $email,
                'amount' => (int) round($total * 100),
                'reference' => $ref,
                'callback_url' => BASE_URL . 'checkout/callback'
            ]
        );

        if (
            empty($res['status']) ||
            empty($res['data']['authorization_url'])
        ) {
            $_SESSION['co_err'] =
                'Payment could not start. Please try again.';
            redirect('checkout');
        }

        // Redirect customer to Paystack
        redirect($res['data']['authorization_url']);
    }*/

    function callback() {
        $ref = $_GET['reference'] ?? ''; $res = $this->paystack('GET', 'transaction/verify/' . rawurlencode($ref));
        $order = $this->model->get_one_where('reference', $ref, 'orders');
        $ok = $order && !empty($res['data']['status']) && $res['data']['status'] === 'success' && (int) $res['data']['amount'] === (int) $order->total * 100;
        if ($ok) { $this->model->update($order->id, ['status' => 'paid'], 'orders'); unset($_SESSION['cart']); }
        $data['ok'] = $ok; $data['order'] = $order; $data['light'] = true; $data['title'] = 'Payment | Meatlab';
        $data['view_module'] = 'checkout'; $data['view_file'] = 'result'; $this->template('meatlab', $data);
    }
    private function paystack($method, $path, $body = null) {
        $ch = curl_init('https://api.paystack.co/' . $path);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method, CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . PAYSTACK_SECRET, 'Content-Type: application/json']]);
        if ($body) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        $out = curl_exec($ch); curl_close($ch); return json_decode($out ?: '[]', true);
    }
    
    private function _fetch_google_distance($origin, $destination)
    {
        // Store this key in your server-side configuration.
        $apiKey = GOOGLE_MAPS_API_KEY;

        $origin = trim($origin) . ', Lagos, Nigeria';
        $destination = trim($destination) . ', Lagos, Nigeria';

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?' .
            http_build_query([
                'origins' => $origin,
                'destinations' => $destination,
                'mode' => 'driving',
                'units' => 'metric',
                'key' => $apiKey
            ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n",
                'timeout' => 8
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true);

        // Validate the Google API response
        if (
            !is_array($data) ||
            ($data['status'] ?? '') !== 'OK'
        ) {
            return false;
        }

        $element = $data['rows'][0]['elements'][0] ?? null;

        if (
            !is_array($element) ||
            ($element['status'] ?? '') !== 'OK' ||
            !isset($element['distance']['value'])
        ) {
            return false;
        }

        // Google returns distance in metres. Convert to kilometres.
        return $element['distance']['value'] / 1000;
    }

}
