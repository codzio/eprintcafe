<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\Exception;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class EmailSending extends Controller {

	public static function landingPageLead($data) {

        $template = view('email_templates/admin/vwLandingPageLeadTemp', $data)->render();

        $emailComposer = new EmailSending();

        return $emailComposer->composeEmail(array(
            'to' => 'mail@eprintcafe.com',
            'subject' => 'Submit Lead Form',
            'body' => $template,
        ));
    }

    public static function adminTwoFactorAuth($data) {

		$template = view('email_templates.admin.vwTwoStepTemp', $data)->render();

		$emailComposer = new EmailSending();

		return $emailComposer->composeEmail(array(
			'to' => $data['email'],
			'subject' => 'Two-Factor Authentication',
			'body' => $template,
		));
	}

	public static function adminResetPassword($data) {        

		$template = view('email_templates.admin.vwForgotPassTemp', $data)->render();

		$emailComposer = new EmailSending();

		return $emailComposer->composeEmail(array(
			'to' => $data['email'],
			'subject' => 'Forgot Password',
			'body' => $template
		));
	}

    public static function customerResetPassword($data) {        

        $template = view('email_templates.customer.vwForgotPassTemp', $data)->render();

        $emailComposer = new EmailSending();

        return $emailComposer->composeEmail(array(
            'to' => $data['email'],
            'subject' => 'Forgot Password',
            'body' => $template
        ));
    }

	public static function adminPassChangeNotify($data) {

        $data['siteSettings'] = siteSettings();

		$template = view('templates.email.admin.vwChangePassword', $data)->render();

		$emailComposer = new EmailSending();
		return $emailComposer->composeEmail(array(
			'to' => $data['email'],
			'subject' => 'Security Alert - Password Changed',
			'body' => $template
		));
	}

    public static function adminEmailChangeNotify($data) {

        $data['siteSettings'] = siteSettings();

        $template = view('templates.email.admin.vwChangeEmail', $data)->render();

        $emailComposer = new EmailSending();
        return $emailComposer->composeEmail(array(
            'to' => $data['email'],
            'subject' => 'Security Alert - Email Changed',
            'body' => $template
        ));
    }

    public static function abandonedCartEmail($data) {

        $template = view('email_templates/admin/vwAbandonedTemplate', array('customer' => $data));

        $emailComposer = new EmailSending();

        return $emailComposer->composeEmail(array(
            'to' => $data->email,
            'subject' => 'Soft Reminder - Complete your order',
            'body' => $template,
        ));
    }

    public static function orderEmail($getOrder) {
        $priceData = json_decode($getOrder->price_details);
        $productDetails = json_decode($getOrder->product_details);
        $customerAddress = json_decode($getOrder->customer_address);
        $gstRate = 12;
        $hsnCode = $getOrder->unregistered_hsn_code;

        $customerName = $customerAddress->shipping_name;
        $shippingState = strtolower($customerAddress->shipping_state);

        $isIntrastate = false;

        if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
            $isIntrastate = true;
        }

        if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
            $gstRate = 18;
            $hsnCode = $getOrder->registered_hsn_code;

            $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
        }

        $gstCalc = calculateGSTComponents($getOrder->paid_amount, $gstRate);

        $data = array('order' => $getOrder, 'priceData' => $priceData, 'productDetails' => $productDetails, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate, 'hsnCode' => $hsnCode);
        $template = view('email_templates/admin/vwOrderTemplate', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

        $emailComposer = new EmailSending();

        $emailComposer->composeEmail(array(
            'to' => 'indiainttech@gmail.com',
            'subject' => 'Invoice',
            'body' => $template,
        ));

        return $emailComposer->composeEmail(array(
            'to' => $customerAddress->shipping_email,
            'subject' => 'Invoice',
            'body' => $template,
        ));
    }

    public static function orderEmailNew($orderId) {

        $orderData = OrderModel::where('id', $orderId)->first();

        if (!empty($orderData) && $orderData->count()){

            if (!empty($orderData->product_id)) {
                
                $getOrder = OrderModel::
                join('product', 'orders.product_id', '=', 'product.id')
                ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
                ->where('orders.id', $orderData->id)->first();

                $priceData = json_decode($getOrder->price_details);
                $productDetails = json_decode($getOrder->product_details);
                $customerAddress = json_decode($getOrder->customer_address);
                $gstRate = 12;
                $hsnCode = $getOrder->unregistered_hsn_code;

                $customerName = $customerAddress->shipping_name;
                $shippingState = strtolower($customerAddress->shipping_state);

                $isIntrastate = false;

                if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
                    $isIntrastate = true;
                }

                if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
                    $gstRate = 18;
                    $hsnCode = $getOrder->registered_hsn_code;

                    $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
                }

                $gstCalc = calculateGSTComponents($getOrder->paid_amount, $gstRate);

                $data = array('order' => $getOrder, 'priceData' => $priceData, 'productDetails' => $productDetails, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate, 'hsnCode' => $hsnCode);
                $template = view('email_templates/admin/vwOrderTemplate', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

            } else {

                $getOrder = OrderItemModel::
                join('product', 'order_items.product_id', '=', 'product.id')
                ->select('order_items.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
                ->where('order_items.order_id', $orderData->id)->get();

                //$priceData = json_decode($getOrder->price_details);
                //$productDetails = json_decode($getOrder->product_details);
                $customerAddress = json_decode($orderData->customer_address);
                $gstRate = 12;
                //$hsnCode = $getOrder->unregistered_hsn_code;

                $customerName = $customerAddress->shipping_name;
                $shippingState = strtolower($customerAddress->shipping_state);

                $isIntrastate = false;

                if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
                    $isIntrastate = true;
                }

                if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
                    // $gstRate = 18;
                    // $hsnCode = $getOrder->registered_hsn_code;

                    $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
                }

                $gstCalc = calculateGSTComponents($orderData->paid_amount, $gstRate);

                $data = array('order' => $orderData, 'orderItem' => $getOrder, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate);

                $template = view('email_templates/admin/vwOrderTemplateMulti', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

            }

            $emailComposer = new EmailSending();

            $emailComposer->composeEmail(array(
                'to' => 'indiainttech@gmail.com',
                'subject' => 'Invoice',
                'body' => $template,
            ));

            return $emailComposer->composeEmail(array(
                'to' => $customerAddress->shipping_email,
                'subject' => 'Invoice',
                'body' => $template,
            ));

        } else {
            return false;
        }
    }

    public static function test($data) {

        //$data['siteSettings'] = siteSettings();

        //$data = array();

        echo $template = view('email_templates.customer.vwTwoStepTemp', $data)->render();
        die();

        $emailComposer = new EmailSending();
        return $emailComposer->composeEmail(array(
            'to' => $data['email'],
            'subject' => 'Security Alert - Password Changed',
            'body' => $template,
            'debug' => $data['debug'],
            'debugLevel' => $data['level'],
        ));
    }

	public function composeEmail($mailInfo) {

        require base_path("vendor/autoload.php");
        
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {

            // Email server settings
            
            if (isset($mailInfo['debugLevel'])) {
                $mail->SMTPDebug = $mailInfo['debugLevel'];
            } else {
                $mail->SMTPDebug = 0;
            }

            $mail->isSMTP();
            $mail->Host = setting('smtp_host');             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = setting('smtp_username');   //  sender username
            $mail->Password = setting('smtp_password');       // sender password
            $mail->SMTPSecure = setting('email_encryption');                  // encryption - ssl/tls
            $mail->Port = setting('smtp_port');                          // port - 587/465

            $mail->setFrom(setting('from_address'), setting('from_name'));
            
            $mail->addAddress($mailInfo['to']);
            
            if (isset($mailInfo['cc'])) {
                $mail->addCC($mailInfo['cc']);
            }

            if (isset($mailInfo['bcc'])) {
                $mail->addBCC($mailInfo['bcc']);
            }           

            // $mail->addReplyTo('sender-reply-email', 'sender-reply-name');

            // if(isset($_FILES['emailAttachments'])) {
            //     for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
            //         $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
            //     }
            // }


            $mail->isHTML(true);                // Set email content format to HTML

            $mail->Subject = $mailInfo['subject'];
            $mail->Body    = $mailInfo['body'];

            // $mail->AltBody = plain text version of email body;

            if( !$mail->send() ) {
                //return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);

                if (isset($mailInfo['debug']) && $mailInfo['debug']) {
                    return $mail->ErrorInfo;                    
                } else {
                    return false;
                }
                
            } else {
                //return back()->with("success", "Email has been sent.");
                return true;
            }

        } catch (Exception $e) {
             //return back()->with('error','Message could not be sent.');
        	return false;
        }
    }
	
}