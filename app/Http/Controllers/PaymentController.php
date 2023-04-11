<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Supporter;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    /**
     * successfull transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction($payment_id, Request $request)
    {
        $provider = new PayPalClient;
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        $payment = Payment::where('id', $payment_id)->firstOrFail();
        try {
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $payment->status = $response['status'];
                /*$payment->firstname = $response['payer']['name']['given_name'];
                $payment->lastname = $response['payer']['name']['surname'];
                $payment->email = $response['payer']['email_address'];
                $payment->currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
                $payment->amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                $payment->address = $response['purchase_units'][0]['shipping']['address']['address_line_1'];*/
                $payment->save();

                $supporter = new Supporter;
                $supporter->amount_donated = $payment->amount;
                $supporter->fundraiser_id = $payment->fundraiser_id;
                if ($payment->isAnonymous) {
                    $supporter->name = 'Well Wisher';
                } else {
                    $supporter->name = $payment->firstname; //. ' ' . $payment->lastname;
                }
                $supporter->save();

                return response()->json([
                    'success' => true,
                    'supporter' => $supporter,
                    'message' => 'Payment completed',
                ], 201);
            } else {
                return response()->json(['message' => $response['message'] ?? 'Something went wrong.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.' . $payment . $e->getMessage()], 500);
        }
    }
    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request, $payment_id)
    {
        $provider = new PayPalClient;
        $provider->getAccessToken();
        $response = $provider->showOrderDetails($request['token']);
        $payment = Payment::where('id', $payment_id)->firstOrFail();
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $payment->status = $response['status'];
        } else {
            $payment->status = 'CANCELED';
        }

        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment declined',
        ], 201);
    }
}
