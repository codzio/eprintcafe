<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SmsSending extends Controller {

	public static function orderDelivered($number, $orderId) {

        $data = [
            'template_id' => '65d71ea1d6fc053e9d64b002',
            'short_url' => 0,
            'recipients' => [[
                'mobiles' => '91'.$number,
                'orderid' => $orderId
            ]]
        ];

        $data = json_encode($data);

        $curlRequest = new SmsSending();
        return $curlRequest->curlRequest($data)->type;

	}

	public static function orderPlaced($number, $name) {        
        $data = [
            'template_id' => '65d71e63d6fc0570394a60a2',
            'short_url' => 0,
            'recipients' => [[
                'mobiles' => '91'.$number,
                'name' => $name
            ]]
        ];

        $data = json_encode($data);

        $curlRequest = new SmsSending();
        return $curlRequest->curlRequest($data)->type;
	}

    public static function sendOTP($number, $otp) {
        
        $data = [
            'template_id' => '65d717e8d6fc0513e11608c3',
            'short_url' => '0',
            'recipients' => [[
                'mobiles' => '91'.$number,
                'number' => $otp
            ]]
        ];

        $data = json_encode($data);

        $curlRequest = new SmsSending();
        return $curlRequest->curlRequest($data)->type;  

    }

    public static function forgotOTP($number, $otp) {        
        $data = [
            'template_id' => '65d6e3aad6fc0539211feb42',
            'short_url' => '0',
            'recipients' => [[
                'mobiles' => '91'.$number,
                'number' => $otp
            ]]
        ];

        $data = json_encode($data);

        $curlRequest = new SmsSending();
        return $curlRequest->curlRequest($data)->type; 
    }

    public function curlRequest($postFields) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://control.msg91.com/api/v5/flow/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $postFields,
          CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'authkey: 414577Aa4We7UI65d72386P1',
            'content-type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
	
}