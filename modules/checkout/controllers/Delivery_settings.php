<?php
class Delivery_settings extends Trongate {

    /**
     * Main API Endpoint to calculate delivery eligibility and fee
     * Expected POST data via JSON: origin, destination, total_weight, cart_total
     */
    function calculate() {
        // Secure endpoint to allow POST requests only
        $this->api_auth();

        // Retrieve raw post data and decode JSON
        $input = json_decode(file_get_contents('php://input'), true);

        $origin      = isset($input['origin']) ? trim($input['origin']) : '';
        $destination = isset($input['destination']) ? trim($input['destination']) : '';
        $totalWeight = isset($input['total_weight']) ? (float)$input['total_weight'] : 0.0;
        $cartTotal   = isset($input['cart_total']) ? (float)$input['cart_total'] : 0.0;

        if (empty($origin) || empty($destination)) {
            $this->_send_json(['status' => 'error', 'message' => 'Origin and destination addresses are required.'], 400);
            return;
        }

        // Fetch Distance from Google Distance Matrix API
        $apiKey = 'YOUR_ACTUAL_GOOGLE_MAPS_API_KEY'; // Replace with your secure Google Maps API Key
        $distanceKm = $this->_fetch_google_distance($origin, $destination, $apiKey);

        if ($distanceKm === false) {
            $this->_send_json([
                'status' => 'blocked',
                'message' => 'We could not verify this delivery address. Please verify your street name or try adding a well-known nearby landmark.'
            ]);
            return;
        }

        // Process logic based on business requirements
        $maxAllowedDistance = 10.0; // Hard cutoff parameter for freshness
        if ($distanceKm > $maxAllowedDistance) {
            $this->_send_json([
                'status' => 'blocked',
                'distance_km' => $distanceKm,
                'message' => "We are sorry, but your location is outside our delivery zone. We only service up to {$maxAllowedDistance}km from our hub to guarantee freshly slaughtered quality."
            ]);
            return;
        }

        // Determine Base Delivery Fee Tier
        if ($distanceKm <= 3.0) {
            $deliveryFee = 400; // Hyper-local neighborhood (₦300 rider fee + ₦100 platform margin)
            $tierNotes = 'Hyper-local suburban rate applied.';
        } elseif ($distanceKm <= 6.0) {
            $deliveryFee = 700; // Extended neighborhood
            $tierNotes = 'Standard suburban rate applied.';
        } else {
            $deliveryFee = 1200; // Far suburb delivery
            $tierNotes = 'Long-distance suburban rate applied.';
        }

        // Apply dynamic conditional logic rules for Free Delivery Promotions
        $isPromoApplied = false;
        if ($totalWeight >= 5.0 && $totalWeight <= 10.0) {
            $deliveryFee = 0;
            $isPromoApplied = true;
            $tierNotes = 'Promotion Applied: Free Neighborhood Delivery for orders between 5kg and 10kg!';
        }

        // Fixed transactional platform maintenance fee
        $platformServiceFee = 150;
        $finalTotalPayable = $cartTotal + $deliveryFee + $platformServiceFee;

        // Formulate structured transactional response payload
        $responsePayload = [
            'status' => 'allowed',
            'distance_km' => round($distanceKm, 2),
            'base_weight_kg' => $totalWeight,
            'cart_subtotal' => $cartTotal,
            'delivery_fee' => $deliveryFee,
            'platform_service_fee' => $platformServiceFee,
            'total_payable' => $finalTotalPayable,
            'promo_active' => $isPromoApplied,
            'notes' => $tierNotes
        ];

        $this->_send_json($responsePayload);
    }

    /**
     * Interacts with Google Distance Matrix API endpoints safely
     */
    private function _fetch_google_distance($origin, $destination, $apiKey) {
        // Sanitize string endpoints for proper URL formatting appending Lagos localization context
        $originLocalized = $origin . ", Lagos, Nigeria";
        $destinationLocalized = $destination . ", Lagos, Nigeria";

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode($originLocalized) . "&destinations=" . urlencode($destinationLocalized) . "&mode=driving&key=" . urlencode($apiKey);

        // Make HTTP Request using stream context parameters
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Accept: application/json\r\n",
                "timeout" => 5
            ]
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true);

        if (isset($data['status']) && $data['status'] === 'OK') {
            $element = $data['rows'][0]['elements'][0];
            if (isset($element['status']) && $element['status'] === 'OK') {
                $meters = $element['distance']['value'];
                return $meters / 1000; // Convert exact distance metric integer straight to Kilometers
            }
        }

        return false;
    }

    /**
     * Standard internal helper method to output structured JSON configurations
     */
    private function _send_json($data, $http_status = 200) {
        http_response_code($http_status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
?>