<?php
// Add this line at the END of config/config.php:
//   require_once __DIR__ . '/meatlab.php';

// ---- Customer notifications ----
define('SMS_API_KEY', 'tlv_cUMK5CQyWUcLF64CM-dXiqCab9r_Qs5JM2nsi_m8IE0');   // https://termii.com
define('SMS_SENDER_ID', 'Meatlab');             // must be approved by Termii
define('MAIL_FROM', 'orders@meatlab.ng');
define('MAIL_FROM_NAME', 'Meatlab');
define('GOOGLE_MAPS_API_KEY', '');

// ---- Uploads (public/uploads/banners and public/uploads/products) ----
define('UPLOAD_DIR',  'uploads/');

// ---- View helpers ----
function ml_money($n) {
    return '₦' . number_format((float) $n, 2);
}

function ml_status_label($s) {
    $labels = [
        'pending' => 'New', 'processing' => 'Processing', 'on_the_way' => 'On the way',
        'delivered' => 'Delivered', 'declined' => 'Declined',
        'approved' => 'Live', 'rejected' => 'Rejected',
        'paid' => 'Paid', 'unpaid' => 'Unpaid', 'failed' => 'Failed', 'shop_paid' => 'Shop payout',
    ];
    return $labels[$s] ?? ucfirst($s);
}

function ml_badge($s) {
    return '<span class="badge b-' . htmlspecialchars($s) . '">' . ml_status_label($s) . '</span>';
}

function ml_img($folder, $file) {
    return $file ? BASE_URL . 'uploads/' . $folder . '/' . rawurlencode($file) : '';
}

function ml_initials($names){
    $initials = explode(' ',$names);
    if(count($initials) > 1){
        $initials = strtoupper(substr($initials[0],0,1).''.substr($initials[1],0,1)); 
    }else{
        $initials = strtoupper(substr($initials[0],0,1)); 
    }

    return $initials;
}
