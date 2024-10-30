<?php

require __DIR__ . '/vendor/autoload.php';

use Sportspay\Sportspay;
use Sportspay\PaymentIntent;

Sportspay::setApiKey('your_api_key');
//if you are using sportspay 
//Sportspay::useSportspay();
//else it will use stripe

$payment = PaymentIntent::create([
    'amount' => 10000, //use in cents => $100
    'currency' => 'cad',
    'description' => 'some description',
    'receipt_email' => 'aj@posconnect.com',
    'application_fee_amount' => 1200, //sportspay will auto calculate this..
    'customer' => 'customer id',//stripe_customer_id or merchant_id for sportspay,
    'metadata' => 'meta data sample'
], ['stripe_account' => 'some_id',
    'idempotency_key' => $fields['billingScheduleId'],
]);

print_r($paymentIntent);

?>