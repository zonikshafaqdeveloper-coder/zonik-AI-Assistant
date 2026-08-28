<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use Exception;
use Twilio\Rest\Client;

class AuthController extends Controller
{



    // public function index()
    // {
    //     $receiverNumber = "8637745572";
    //     $message = "This is testing from CodeSolutionStuff.com";

    //     try {

    //         $account_sid = config('services.twilio.account_sid');
    //         $auth_token = config('services.twilio.auth_token');
    //         $twilio_number = config('services.twilio.phone_number');

    //         $client = new Client($account_sid, $auth_token);
    //         $client->messages->create($receiverNumber, [
    //             'from' => $twilio_number,
    //             'body' => $message]);

    //         dd('SMS Sent Successfully.');

    //     } catch (Exception $e) {
    //         dd("Error: ". $e->getMessage());
    //     }
    // }

    // use Twilio\Rest\Client;
    // use Illuminate\Http\Request;

    public function index(Request $request)
    {
        $receiverNumber = config('services.twilio.test_recipient');
        $message = "This is testing from CodeSolutionStuff.com";

        try {
            $account_sid = config('services.twilio.account_sid');
            $auth_token = config('services.twilio.auth_token');
            $twilio_number = config('services.twilio.phone_number');

            $client = new Client($account_sid, $auth_token,  [
                'curlOptions' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                ],
            ]);


            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);

        dd('SMS Sent Successfully.');

        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }


    // public function index(Request $request)
    // {
    //     // Generate a random 6-digit OTP
    //     $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    //     // Retrieve customer ID and name from the request
    //     $customerId = $request->input('customer_id');
    //     $customerName = $request->input('customer_name');

    //     // Send OTP via Twilio
    //     $receiverNumber = config('services.twilio.test_recipient');
    //     $account_sid = env("TWILIO_ACCOUNT_SID"); // Replace with your Twilio Account SID
    //     $auth_token = env("TWILIO_AUTH_TOKEN"); // Replace with your Twilio Auth Token
    //     $twilio_number = env("TWILIO_PHONE_NUMBER"); // Replace with your Twilio phone number

    //     try {
    //         $client = new Client($account_sid, $auth_token, [
    //             'curlOptions' => [
    //                 CURLOPT_SSL_VERIFYPEER => false,
    //             ],
    //         ]);

    //         $message = "Your OTP: $otp"; // Include the OTP in the message

    //         $client->messages->create($receiverNumber, [
    //             'from' => $twilio_number,
    //             'body' => $message
    //         ]);

    //         // Store OTP, Customer ID, and Name in the database
    //         Customer::create([
    //             'id' => $customerId,
    //             'name' => $customerName,
    //             'otp' => $otp,
    //         ]);

    //         return response()->json(['message' => 'OTP Sent Successfully.']);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    //     }
    // }


}





