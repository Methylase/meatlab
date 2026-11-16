<?php
// Put your keys here (or set env vars). Never commit live keys.
define('PAYSTACK_SECRET', getenv('PAYSTACK_SECRET_KEY') ?: 'sk_test_a446d054a122c0ccaaff5227cb013998e1c96fa9');
define('PAYSTACK_PUBLIC', getenv('PAYSTACK_PUBLIC_KEY') ?: 'pk_test_87f35dc014fc50fa82c2dedbc8ce848a5094d103');
define('DELIVERY_FEE', 1500);