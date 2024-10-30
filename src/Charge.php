<?php

namespace Sportspay;

use Stripe\Charge as CG;
use Stripe\Stripe;

class Charge
{
    /**
     * charge the amount to an existing card token
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

            $data = [
                'TERMID'     => $creds[0],
                'TYPE'       => 'S',
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
            return CG::create($params, $options);
        }
    }
}
