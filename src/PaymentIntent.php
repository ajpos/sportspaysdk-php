<?php

namespace Sportspay;

use Stripe\PaymentIntent as PI;
use Stripe\Stripe;

class PaymentIntent {

    /**
     * Start a new payment session
     * 
     * @param array $params The parameters for creating a payment session
     * @param array $options The options including account credentials
     * @return array The response from the API
     */
    public static function create(array $params, array $options) {
        if (Sportspay::isUsingSportspay())
        {
            // Split the credentials from the 'stripe_account' option
            $creds = explode('_', $options['stripe_account']);
            
            if (count($creds) < 2) {
                throw new \Exception('Invalid credentials format. Expected TERMID_PASS format.');
            }

            // Prepare data for the StartSession request
            $data = [
                'TERMID'     => $creds[0],
                'TYPE'       => 'W',
                'PASS'       => $creds[1],
                'ACTION'     => 'StartSession',
                'AMT'        => number_format($params['amount'], 2, '.', ''),
                'CUSTEMAIL'  => $params['receipt_email'],
                'REQUESTID'  => $params['requestId'],
                'LANG'       => $params['language'] ?? 'E', // Default to English if not provided
            ];

            // Optional fields
            if (!empty($params['invoice'])) {
                $data['INV'] = $params['invoice'];
            }

            if (!empty($params['description'])) {
                $data['DESC'] = $params['description'];
            }

            // Make the API request and return the response
            return Sportspay::request('POST', $data);
        }else{
            return PI::create($params, $options);
        }
    }

    /**
     * void the recent transaction
     * 
     * @param array $params The parameters for inititating the void transaction
     * @param array $options The options including account credentials
     * @return array The response from the API
     */
    public static function cancel($params = null, $opts = null){
        if (Sportspay::isUsingSportspay())
        {
            // Split the credentials from the 'stripe_account' option
            $creds = explode('_', $options['stripe_account']);
            
            if (count($creds) < 2) {
                throw new \Exception('Invalid credentials format. Expected TERMID_PASS format.');
            }

            $data = [
                'TERMID'     => $creds[0],
                'TYPE'       => 'V',
                'PASS'       => $creds[1],
                'TOKEN'      => $params['token'],
                'AMT'        => number_format($params['amount'], 2, '.', ''),
                'CUSTEMAIL'  => $params['receipt_email'],
                'REQUESTID'  => $params['requestId'],
            ];

            // Optional fields
            if (!empty($params['invoice'])) {
                $data['INV'] = $params['invoice'];
            }

            if (!empty($params['description'])) {
                $data['DESC'] = $params['description'];
            }

            // Make the API request and return the response
            return Sportspay::request('POST', $data);
        }else{
            return PI::cancel($params, $options);
        }
    }

    /**
     * this is the get result function 
     * 
     * @param array $params The parameters for inititating the void transaction
     * @param array $options The options including account credentials
     * @return array The response from the API
     */
    public static function capture($params = null, $opts = null){
        if (Sportspay::isUsingSportspay())
        {
            // Split the credentials from the 'stripe_account' option
            $creds = explode('_', $options['stripe_account']);
            
            if (count($creds) < 2) {
                throw new \Exception('Invalid credentials format. Expected TERMID_PASS format.');
            }

            $data = [
                'TERMID'     => $creds[0],
                'TYPE'       => 'W', // Web-Redirect
                'PASS'       => $creds[1],
                'ACTION'     => 'GetResult',
                'SECUREID'  => $options['idempotency_key'],
                'ACK'       => 'Y'
            ];

            // Optional fields
            if (!empty($params['invoice'])) {
                $data['INV'] = $params['invoice'];
            }

            if (!empty($params['description'])) {
                $data['DESC'] = $params['description'];
            }

            // Make the API request and return the response
            return Sportspay::request('POST', $data);
        }else{
            return PI::capture($params, $options);
        }
    } 
}
