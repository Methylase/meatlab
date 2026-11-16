<?php
/**
 * Notify: tells the customer about an order by SMS (phone) and email.
 * Called from the owner dashboard, never from a URL (methods start with "_").
 *
 * Events: processing, on_the_way, delivered, declined
 * Returns ['sms' => true|false|null, 'email' => true|false|null]
 * null means the customer left that contact blank.
 */
class Notify extends Trongate {

    public function _order_event($order_id, $event) {
        $rows = $this->model->query_bind(
            'SELECT o.*, s.shop_name, s.phone AS shop_phone
             FROM orders o JOIN shops s ON s.id = o.shop_id WHERE o.id = :id',
            ['id' => (int) $order_id],
            'object'
        );
        if (!$rows) {
            return ['sms' => null, 'email' => null];
        }
        $o = $rows[0];
        $text = $this->_message($o, $event);
        $result = ['sms' => null, 'email' => null];

        if (!empty($o->customer_phone)) {
            $result['sms'] = $this->_send_sms($o->customer_phone, $text, $o->id, $event);
        }
        if (!empty($o->customer_email)) {
            $result['email'] = $this->_send_email($o->customer_email, $o->customer_name, $o->shop_name, $text, $o->id, $event);
        }
        return $result;
    }

    /** Tells the shop owner (SMS + email) that the admin has paid them for an order. */
    public function _shop_payout($order_id) {
        $rows = $this->model->query_bind(
            'SELECT o.*, s.shop_name, a.name AS owner_name, a.phone AS owner_phone, a.email AS owner_email
             FROM orders o JOIN shops s ON s.id = o.shop_id JOIN accounts a ON a.id = s.account_id WHERE o.id = :id',
            ['id' => (int) $order_id],
            'object'
        );
        if (!$rows || empty($rows[0]->shop_paid_at)) {
            return ['sms' => null, 'email' => null];
        }
        $o = $rows[0];
        $name = explode(' ', trim($o->owner_name))[0];
        $when = date('d M Y, g:ia', strtotime($o->shop_paid_at));
        $ref = $o->shop_payout_ref ? " Ref: {$o->shop_payout_ref}." : '';
        $text = "Hi {$name}, Meatlab has paid you " . ml_money($o->shop_paid_amount) . " for order {$o->reference} on {$when}.{$ref}";

        $result = ['sms' => null, 'email' => null];
        if (!empty($o->owner_phone)) {
            $result['sms'] = $this->_send_sms($o->owner_phone, $text, $o->id, 'shop_paid');
        }
        if (!empty($o->owner_email)) {
            $result['email'] = $this->_send_email($o->owner_email, $o->owner_name, $o->shop_name, $text, $o->id, 'shop_paid');
        }
        return $result;
    }

    private function _message($o, $event) {
        $name = explode(' ', trim($o->customer_name))[0];
        $ref = $o->reference;
        $shop = $o->shop_name;
        $contact = $o->shop_phone ? " Questions? Call {$o->shop_phone}." : '';

        switch ($event) {
            case 'processing':
                return "Hi {$name}, {$shop} has accepted your order {$ref} and is processing it now.{$contact}";
            case 'on_the_way':
                return "Hi {$name}, your order {$ref} from {$shop} is ready and on the way to you.{$contact}";
            case 'delivered':
                return "Hi {$name}, your order {$ref} from {$shop} has been delivered. Thank you for ordering on Meatlab.";
            case 'declined':
                return "Hi {$name}, sorry, {$shop} could not take your order {$ref}.{$contact}";
        }
        return "Update on your order {$ref} from {$shop}.";
    }

    /** Termii: https://developers.termii.com/messaging */
    private function _send_sms($phone, $text, $order_id, $event) {
        $to = $this->_ng_number($phone);
        if (!$to || SMS_API_KEY === 'YOUR_TERMII_API_KEY') {
            $this->_log($order_id, 'sms', $event, false, !$to ? 'Invalid phone number' : 'SMS key not set');
            return false;
        }
        $payload = json_encode([
            'to' => $to, 'from' => SMS_SENDER_ID, 'sms' => $text,
            'type' => 'plain', 'channel' => 'generic', 'api_key' => SMS_API_KEY,
        ]);
        $ch = curl_init('https://api.ng.termii.com/api/sms/send');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $ok = ($response !== false && $code >= 200 && $code < 300);
        $this->_log($order_id, 'sms', $event, $ok, $response === false ? 'Network error' : substr($response, 0, 250));
        return $ok;
    }

    /** Uses PHP mail(). For better delivery, swap this for SMTP (PHPMailer) later. */
    private function _send_email($email, $name, $shop, $text, $order_id, $event) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_log($order_id, 'email', $event, false, 'Invalid email');
            return false;
        }
        $subjects = [
            'processing' => 'Your order is being processed',
            'on_the_way' => 'Your order is on the way',
            'delivered'  => 'Your order has been delivered',
            'declined'   => 'Update on your order',
            'shop_paid'  => 'Meatlab has paid you',
        ];
        $subject = ($subjects[$event] ?? 'Order update') . ' - ' . $shop;
        $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . ">\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n";
        $ok = @mail($email, $subject, $text . "\n\n- Meatlab", $headers);
        $this->_log($order_id, 'email', $event, $ok, $ok ? 'Sent' : 'mail() failed');
        return $ok;
    }

    /** 0803... or +234803... or 234803... becomes 234803... */
    private function _ng_number($phone) {
        $n = preg_replace('/\D+/', '', $phone);
        if (strlen($n) === 11 && $n[0] === '0') {
            $n = '234' . substr($n, 1);
        }
        return (strlen($n) === 13 && substr($n, 0, 3) === '234') ? $n : null;
    }

    private function _log($order_id, $channel, $event, $ok, $response) {
        $this->model->insert([
            'order_id' => (int) $order_id,
            'channel'  => $channel,
            'event'    => $event,
            'success'  => $ok ? 1 : 0,
            'response' => $response,
        ], 'order_notifications');
    }
}
