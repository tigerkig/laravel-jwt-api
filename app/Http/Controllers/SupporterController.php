<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\Payment;
use App\Models\Supporter;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use Validator;

class SupporterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fundraiser_id)
    {
        try {
            $supporters = Supporter::where('fundraiser_id', $fundraiser_id)->get();
            if ($supporters->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No Supporters found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $supporters,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve supporters: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $fundraiser_id)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken([
            'force_refresh' => true,
            'cache' => false
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'amount_donated' => 'required|numeric|min:1',
                'currency' => 'nullable|string|in:USD,EUR,AUD,BRL,CAD,CZK,DKK,HKD,HUF,ILS,JPY,MYR,MXN,NOK,NZD,PHP,PLN,GBP,RUB,SGD,SEK,CHF,TWD,THB,TRY',
                'firstname' => 'nullable|string',
                'lastname' => 'nullable|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'country' => 'nullable|string',
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'isAnonymous' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $fundraiser = Fundraiser::find($fundraiser_id);

            if ($fundraiser->status !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This fundraiser is not active'
                ], 400);
            }

            $fundraiser->amount_raised += $request->input('amount_donated');

            if ($fundraiser->amount_raised >= $fundraiser->target) {
                $fundraiser->status = 1;
            }

            $fundraiser->save();
            $user = auth()->user();
            $firstname = $user ? $user->name : $request->input('firstname');
            $lastname = $user ? $user->name: $request->input('lastname');
            $address = $user ? $user->address : $request->input('address');
            $country = $user ? $user->country : $request->input('country');
            $state = $user ? $user->state :  $request->input('state');
            $city = $user ? $user->city : $request->input('city');
            $email = $user ? $user->email : $request->input('email');
            $phone = $user ? $user->telephone : $request->input('phone');

            $payment = Payment::create([
                'currency' => $request->input('currency'),
                'firstname' => $firstname,
                'lastname' => $lastname,
                'amount' => $request->input('amount_donated'),
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'country' => $country,
                'state' => $state,
                'city' => $city,
                'fundraiser_id' => $fundraiser_id,
                'isAnonymous' => $request->input('isAnonymous'),
            ]);

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('payment.success', ['payment_id' => $payment->id]),
                    "cancel_url" => route('payment.cancelled', ['payment_id' => $payment->id]),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $request->currency,
                            "value" => $request->amount_donated
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {

                return response()->json([
                    'success' => true,
                    'message' => 'Payment is being processed.',
                    'approval_url' => $response['links'][1]['href'],
                    'paypalToken' => $paypalToken
                ], 201);
            } else {
                return response()->json(['message' => $response['message'] ?? 'Something went wrong.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to donate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $supporter
     * @return \Illuminate\Http\Response
     */
    public function show($supporter)
    {
        try {
            $result = Supporter::where('id', $supporter)
                ->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Supporter not found"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to fetch supporter data: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supporter $supporter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy($supporter)
    {
        try {
            $supporter = Supporter::findOrFail($supporter);
            $supporter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supporter successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete supporter: ' . $e->getMessage(),
            ], 500);
        }
    }
}
