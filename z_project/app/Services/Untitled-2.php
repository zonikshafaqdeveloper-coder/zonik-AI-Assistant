<?php

namespace App\Services;

use App\Models\SMS;
use Carbon\Carbon;

class SmsService
{



    public function __construct()
    {
    }

    public function sendSMS($phone, $message, $otp = '')
    {


        $now = Carbon::now();
        $date = Carbon::now()->subMinutes(5);
        $last = SMS::where('mobile', $phone)->where('otp', (int)$otp)->whereBetween('created_at', [$date, $now])->first();
        if ($last) {
            return json_encode(array("success" => false, "message" => "message_sent_failed", "ErrorMessage" => "Same message already sent on given no."));
        }

        $message = explode('|', $message);
        $text = $message[0];
        $templateId = isset($message[1]) ? $message[1] : '';

        // $api_url = $this->endPoint . "?key=" . $this->apiKey . "&campaign=" . $this->campaign . "&routeid=" . $this->routeId . "&type=text&contacts=" . $phone . "&senderid=" . $this->senderId . "&msg=" . urlencode($text) . "&template_id=" . $templateId;
        // $result = file_get_contents($api_url);

        $apiKey = urlencode('tYXchLMGV3A-TR55gfxl38o58A82wDMDlZbrZ5VFEG');

        $numbers = array(917385121432);
        $sender = urlencode('INFIBL');
        $message = rawurlencode("Welcome to Infipara solutions B.local . " . $otp . " is your One Time Password for BLocal application. Don't share this with anyone.");

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // dd($response);



        $resultStatus  = json_decode($response);
        // dd($resultStatus->messages[0]->id);
        if ($resultStatus->status == 'success') {
            $smsid = $resultStatus->messages[0]->id;
            $error = null;
        } else {
            $error = $response;
            $smsid = null;
        }
        // dd($phone);
        $sms = new SMS();
        $sms->message_id = $smsid;
        $sms->mobile = $phone;
        $sms->message = $text;
        $sms->error = $error;
        if ($otp != '') {
            $sms->otp = $otp;
        }
        $sms->save();
        if ($smsid != '') {
            return array("success" => true, "message" => "message_sent.");
        } else {
            return array("success" => false, "message" => "message_sent_failed", "ErrorMessage" => $error);
        }
    }

    public function verifyOtp($mobile, $otp)
    {
        $now = Carbon::now();
        $date = Carbon::now()->subMinutes(5);
        $otp = SMS::where('mobile', $mobile)
            ->where('otp', (int)$otp)
            ->whereDate('created_at', '>=', $date)
            ->whereDate('created_at', '<=', $now)
            ->whereNull('verified_at')
            ->first();
        if (isset($otp->message_id)) {
            $otp->update(['verified_at' => now()]);
            return (array("success" => true, "message" => "otp_verified"));
        } else {
            return (array("success" => false, "message" => "otp_verification_failed"));
        }
    }

    public function sendOtp($mobile)
    {
        $otp = rand(1000, 9999);
        // $message = "Welcome to Infipara solutions B.local . " . $otp . " is your One Time Password for BLocal application. Don't share this with anyone.";
        $now = Carbon::now();
        $date = Carbon::now()->subMinutes(5);
        $otps = SMS::where('mobile', $mobile)->whereBetween('created_at', [$date, $now])->count();

        $apiKey = 'Njg2MjQxMzc3NDQ0NjMzNTUxNjk1ODcxMzc0MjRlNGE=';
        $sender = 'Zonik';
        $message = urlencode('Welcome User ' . $otp . ' is OTP for Zonik Log In (Infipara Solutions) . Do not share this OTP with anyone.');
        $data = array(
            'apikey' => $apiKey,
            'sender' => $sender,
            'numbers' => $mobile,
            'message' => $message,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
        // Process your response here
        echo $response;
        return $this->sendSMS($mobile, $message, $otp);
    }


  public function sendOrderDetails($mobile, $order_id, $status)
{
    $apiKey = 'N2E1MjRhMzYzNDcyNTY2ZTMzMzA3OTc1NzE0Mjc2Mzk=';
    $sender = 'Zonik';

   if($status == 'apprvoed'){
        $message = "Dear Customer , you have received Price Offer from Zonik (Infipara solutions). Please take action to Accept , Reject or Negotiate : based on your decision. So that you can start placing orders. Incase of any issue please contact +919136411489 on whatsApp or Call.";
    }

    $message = urlencode($message);

    $data = array(
        'apikey' => $apiKey,
        'sender' => $sender,
        'numbers' => $mobile,
        'message' => $message,
    );
    

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);
    error_log(print_r($response, true));

    $resultStatus = json_decode($response, true);
    
    if ($resultStatus['status'] === 'success') {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message. Error: " . $resultStatus['errors'][0]['message'];
    }
}

public function sendOrder($data)
{
    $apiKey = 'N2E1MjRhMzYzNDcyNTY2ZTMzMzA3OTc1NzE0Mjc2Mzk=';
    $sender = 'Zonik';
    $delivery = $data['delivery'];
    $order = $data['order'];
    $mobile = $order->user->mobile_number;  // Assuming the mobile number is stored here
    $order_id = $order->order_id;
    
    if ($delivery->delivery_status === 'delivered') {
        $message = "Dear Customer, your order {$order_id} is Delivered. Download invoice here. Please share your feedback for us to serve you better by replying Yes or No. 
                    Yes - Satisfied 
                    No - Not Satisfied

                    Team Zonik 
                    (Infipara Solutions)";

    } else if ($delivery->delivery_status === 'in_progress') {
       $outlet_name = substr($order->user->name, 0, 10);  // Limit outlet name to 10 characters
        $invoice_id = substr($order->invoice_id, 0, 10);  // Limit invoice ID to 10 characters
        $payment_mode = substr($order->payment_method, 0, 10);  // Limit payment mode to 10 characters
        $total_amount = substr($order->total_discount_value, 0, 10);  // Limit total amount to 10 characters
        
        // $message = "Dear Customer, Your order $order_id is Confirmed. Sharing you the invoice $invoice_id. Invoice Value: $total_amount Outlet Name: $outlet_name Payment Mode: $payment_mode In case of any issue please contact our Infipara team at 9136411489.";
        $message = "Dear Customer, Thanks for your order $order_id Your order is confirmed and in process. Download invoice Here.Outlet Name: $outlet_name Delivery Date: 12-15-2024. In case of any issue please contact Zonik Team (Infipara Solutions) at +919136411489.";

    } else if ($delivery->delivery_status === 'ready_for_dispatch') {
        $outlet_name = substr($order->user->outlet_name, 0, 10);  // Limit outlet name to 10 characters
        $delivery_date = substr($delivery->delivery_date, 0, 10);  // Limit delivery date to 10 characters
        $invoice_id = substr($order->invoice_id, 0, 10);  // Limit invoice ID to 10 characters
        $payment_mode = substr($order->payment_method, 0, 10);  // Limit payment mode to 10 characters
        $total_amount = substr($order->total_discount_value, 0, 10);  // Limit total amount to 10 characters
        
          $message = "Dear Customer, Thanks for your order $order_id, Your order is Out Delivery - Dispatched . Download invoice Here . Outlet Name: $outlet_name Delivery Date: 12-15-2024. In case of any issue please contact Zonik Team (Infipara Solutions) at +919136411489.";
    
   } else if ($delivery->delivery_status === 'pending') {
        $outlet_name = substr($order->user->outlet_name, 0, 10);  // Limit outlet name to 10 characters
        $delivery_date = substr($delivery->delivery_date, 0, 10);  // Limit delivery date to 10 characters
        $invoice_id = substr($order->invoice_id, 0, 10);  // Limit invoice ID to 10 characters
        $payment_mode = substr($order->payment_method, 0, 10);  // Limit payment mode to 10 characters
        $total_amount = substr($order->total_discount_value, 0, 10);  // Limit total amount to 10 characters
        
          $message = "Dear Customer, Thanks for your order $order_id, Your order is received & under Review  . Outlet Name: $outlet_name Delivery Date: 12-15-2024. In case of any issue please contact Zonik Team (Infipara Solutions) at +919136411489.";
    } else {
        echo "Invalid delivery status.";
        return;
    }

    // $message = urlencode($message);

    $data = array(
        'apikey' => $apiKey,
        'sender' => $sender,
        'numbers' => $mobile,
        'message' => $message,
    );
// dd($data);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);
    error_log(print_r($response, true));
// dd($response);
    $resultStatus = json_decode($response, true);

    if ($resultStatus['status'] === 'success') {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message. Error: " . $resultStatus['errors'][0]['message'];
    }
}





    public function checkBalance()
    {
        $BALANCE_URL = "http://kutility.org/app/miscapi/" . $this->apiKey . "/getBalance/true/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BALANCE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return array('success' => false, "message" => curl_error($ch));
        }
        curl_close($ch);
        if ($this->isJson($result)) {
            return array('success' => true, 'data' => json_decode($result, true), "message" => "");
        } else {
            return array('success' => false, "message" => $result);
        }
    }

    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}