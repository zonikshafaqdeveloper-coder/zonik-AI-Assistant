<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PhonePecontroller extends Controller
{
 public function phonePe($totalDiscountValue)
{
    $data = array (
        'merchantId' => 'DIZCOVERONLINE',
        'merchantTransactionId' => 'TDIZ'.rand(11111,9999999),
        'merchantUserId' => 'MUID123' . time(),
        'amount' => 1 * 100,
        'redirectUrl' => route('response'),
        'redirectMode' => 'POST',
        'callbackUrl' => route('response'),
        'mobileNumber' => '9999999999',
        'paymentInstrument' => array (
            'type' => 'PAY_PAGE',
        ),
    );

    $encode = base64_encode(json_encode($data));

    $saltKey = '8d87c7dd-8878-40a9-ad26-bdda12db3c96';
    $saltIndex = 1;

    $string = $encode.'/pg/v1/pay'.$saltKey;
    $sha256 = hash('sha256',$string);

    $finalXHeader = $sha256.'###'.$saltIndex;

    $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/pay')
            ->withHeader('Content-Type:application/json')
            ->withHeader('X-VERIFY:'.$finalXHeader)
            ->withData(json_encode(['request' => $encode]))
            ->post();

    $rData = json_decode($response);
       

    $rUrl = str_replace(' ', '%20', $rData->data->instrumentResponse->redirectInfo->url);
     dd($rUrl);
    // Store the rUrl in the session
    session(['rUrl' => $rUrl]);
    
    // Retrieve the rUrl from the session
    $rUrl = session('rUrl');
    
    // Debugging: Output the rUrl

    
    // Redirect to the rUrl
    return redirect()->to($rUrl);
    // return response()->json(['url' => $rUrl]);
}

public function delayRedirect()
{
    // Retrieve the URL from the session
    $rUrl = session('rUrl');

    // Check if the URL exists
    if (!$rUrl) {
        return redirect('/')->with('error', 'Redirection URL not found.');
    }

    // Wait for 2 seconds
    sleep(2);

    // Redirect to the stored URL
    return redirect()->away($rUrl);
}


    public function response(Request $request)
    {
    //     $input = $request->all();
    //         dd($input);
    //          print_r($input['transactionId']);
    //         exit;

        // $saltKey = '8d87c7dd-8878-40a9-ad26-bdda12db3c96';
        // $saltIndex = 1;

        // $finalXHeader = hash('sha256','/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'].$saltKey).'###'.$saltIndex;
        // // print_r($finalXHeader);
        // // exit;
        // $response = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'])
        //         ->withHeader('Content-Type:application/json')
        //         ->withHeader('accept:application/json')
        //         ->withHeader('X-VERIFY:'.$finalXHeader)
        //         ->withHeader('X-MERCHANT-ID:'.$input['transactionId'])
        //         ->get();

        // if ($input['code'] == 'PAYMENT_SUCCESS') {
             return view('web.checkout_confirm');
        // }


    }



    //Phone Pay Pay Page

    public function index()
    {
        return view('welcome')->with('res_data', 'Please Pay & Repond From The Payment Gateway Will Come In This Section');
    }

    //Phone Pay Pay Page

    public function refund()
    {
        return view('refund')->with('res_data', 'Please Refund & Repond From The Payment Gateway Will Come In This Section')->with('res_data_status', 'After Refund Refund Status Will Come');
    }

    //Phone Pay Payment Function

    public function payment_init(Request $request)
    {
        try {
            $normalPayLoad = [
                "merchantId"            => "DIZCOVERONLINE",
                "merchantTransactionId" => uniqid(),
                "merchantUserId"        => "MUID123",
                "amount"                => 100,
                "redirectUrl"           => route('pay-return-url'),
                "redirectMode"          => "POST",
                "callbackUrl"           => route('pay-return-url'),
                "mobileNumber"          => "9999999999",
                "paymentInstrument"     => [
                    "type" => "PAY_PAGE",
                ],
            ];

            //Making Data Encoded

            $encode = base64_encode(json_encode($normalPayLoad));

            //Getting The Checksum Value

            $finalXvaify = self::get_checksum_value_request($encode);

            //Curl Lib Payment

            $response = self::payment_with_curl_lib($finalXvaify, $encode);

            return $response;
        } catch (\Exception $e) {
            return view('welcome')->with('res_data', $e->getMessage());
        }

    }

    //Checksome Genertor Request

    private function get_checksum_value_request($payload)
    {
        $saltKey   = env('SALT_MAIN_KEY_PHONEPAY');
        $saltIndex = env('SALT_MAIN_INDEX_PHONEPAY');

        $string = $payload . '/pg/v1/pay' . $saltKey;

        $sha256        = hash('sha256', $string);
        $final_x_vaify = $sha256 . '###' . $saltIndex;
        return $final_x_vaify;
    }

    //Checksome Genertor Request

    private function get_checksum_value_refund($payload)
    {
        $saltKey   = env('SALT_MAIN_KEY_PHONEPAY');
        $saltIndex = env('SALT_MAIN_INDEX_PHONEPAY');

        $string = $payload . '/pg/v1/refund' . $saltKey;

        $sha256        = hash('sha256', $string);
        $final_x_vaify = $sha256 . '###' . $saltIndex;
        return $final_x_vaify;
    }

    //Normal Payment With Curl Lib

    private function payment_with_curl_lib($finalXvaify, $encode)
    {
        $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/pay')
            ->withHeader('Content-Type:application/json')
            ->withHeader('X-VERIFY:' . $finalXvaify)
            ->withData(json_encode(['request' => $encode]))
            ->enableDebug(public_path('test.txt'))
            ->post();
        $return_data = json_decode($response);
        //dd($return_data);

        return redirect()->to($return_data->data->instrumentResponse->redirectInfo->url);

    }

    //Normal Payment With Curl

    private function payment_with_curl($finalXvaify, $encode)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.phonepe.com/apis/hermes/pg/v1/pay');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accept:: application/json',
            'X-VERIFY: ' . $finalXvaify,
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array('request' => $encode)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        return redirect()->to($response->data->instrumentResponse->redirectInfo->url);
    }

    //Phone Pay Return Function

    public function payment_return(Request $request)
    {
        try {

            $saltKey   = env('SALT_MAIN_KEY_PHONEPAY');
            $saltIndex = env('SALT_MAIN_INDEX_PHONEPAY');

            if ($request->code == 'PAYMENT_SUCCESS' && !empty($request->merchantId) && !empty($request->transactionId) && !empty($request->providerReferenceId)) {

                $statusURL      = 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/' . $request->merchantId . '/' . $request->transactionId;
                $final_checksum = self::get_checksum_value_respond($request->merchantId, $request->transactionId);

                $response = Curl::to($statusURL)
                    ->withHeader('Content-Type:application/json')
                    ->withHeader('accept:application/json')
                    ->withHeader('X-VERIFY:' . $final_checksum)
                    ->withHeader('X-MERCHANT-ID:' . $request->transactionId)
                    ->enableDebug(public_path('test.txt'))
                    ->get();

                //DB OPERATION

                //PLease add your code.

                //RETURN TO VIEW

                return view('welcome')->with('res_data', $response);
            } else {
                return view('welcome')->with('res_data', 'Error!!. Respond Not Send');
            }
        } catch (\Exception $e) {
            return view('welcome')->with('res_data', $e->getMessage());
        }
    }

    //Phone Pay Payment Callback Function

    public function payment_callback(Request $request)
    {

    }

    //Checksome Genertor Request

    private function get_checksum_value_respond($merchantId, $transactionId)
    {
        $saltKey   = env('SALT_MAIN_KEY_PHONEPAY');
        $saltIndex = env('SALT_MAIN_INDEX_PHONEPAY');

        $string = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $transactionId . $saltKey) . '###' . $saltIndex;

        return $string;
    }

    //Phone Pay Refund Function

    public function payment_refund(Request $request)
    {
        try {

            $saltKey   = env('SALT_MAIN_KEY_PHONEPAY');
            $saltIndex = env('SALT_MAIN_INDEX_PHONEPAY');

            $tid = $request->refund_tnx_id;

            $payload = [
                'merchantId'            => 'DIZCOVERONLINE',
                'merchantUserId'        => 'MUID123',
                'merchantTransactionId' => $tid,
                'originalTransactionId' => strrev($tid),
                'amount'                => 100000,
                'callbackUrl'           => route('pay-refund-callback'),
            ];

            //Making Data Encoded

            $encode = base64_encode(json_encode($payload));

            //Getting The Checksum Value

            $finalXvaify = self::get_checksum_value_refund($encode);

            $response = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/refund')
                ->withHeader('Content-Type:application/json')
                ->withHeader('X-VERIFY:' . $finalXvaify)
                ->withData(json_encode(['request' => $encode]))
                ->post();

            $rData = json_decode($response);

            $finalXvaifyStatus = hash('sha256', '/pg/v1/status/' . 'DIZCOVERONLINE' . '/' . $tid . $saltKey) . '###' . $saltIndex;

            $responsestatus = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/' . 'DIZCOVERONLINE' . '/' . $tid)
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:' . $finalXvaifyStatus)
                ->withHeader('X-MERCHANT-ID:' . $tid)
                ->get();

            return view('refund')->with('res_data', $response)->with('res_data_status', $responsestatus);
        } catch (Exception $e) {
            return view('refund')->with('res_data', $e->getMessage());
        }
    }

    //Phone Pay Refund Function

    public function payment_refund_callback(Request $request)
    {
        try {
            dd($request->all());
        } catch (Exception $e) {
            return view('refund')->with('res_data', $e->getMessage());
        }
    }
    public function confirm(){
        return view('web.checkout_confirm');
    }
}
