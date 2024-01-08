<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\Exception;

class EmailSending extends Controller {

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

    

    public static function test($data) {

        //$data['siteSettings'] = siteSettings();

        $data = array();

        $template = view('templates.email.admin.vwChangePassword', $data)->render();

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
            $mail->Host = 'smtp.gmail.com';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'alfaizm19@gmail.com';   //  sender username
            $mail->Password = 'oyvi ygcl iipl bpld';       // sender password
            $mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
            $mail->Port = '465';                          // port - 587/465

            $mail->setFrom('alfaizm19@gmail.com', 'Ecom');
            
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