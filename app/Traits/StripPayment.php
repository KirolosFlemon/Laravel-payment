<?php

namespace App\Traits;

use App\Models\Order\Order;
use App\Models\Transaction;
use GuzzleHttp\Psr7\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait StripPayment
{
    const BASE_URL = 'https://api.stripe.com';

    /**
     * @param $textMessage
     * @param $data
     * @param array $tokens
     * @param null $task_id
     * @return bool
     */
    public function stripPayment($request)
    {

        $user = Auth()->user();

        // $address = $process->address->load('district.city.country');
        $payment_url = self::BASE_URL . '/v1/payment_methods';
        $expirationDate = Carbon::parse($request->expiration_date);
        $formattedMonth = $expirationDate->format('m');
        $formattedYear = $expirationDate->format('Y');
        $payment_data = [
            'type' => 'card',
            'card[number]' => $request['card_number'],
            'card[exp_month]' => $formattedMonth,
            'card[exp_year]' => $formattedYear,
            'card[cvc]' => $request['cvv'],
            // 'billing_details[address][city]' => $address->district?->name_en,
            // 'billing_details[address][state]' => $input['state'],
            // 'billing_details[address][country]' => $input['country'],
            // 'billing_details[address][line1]' => $input['line1'],
            // 'billing_details[address][postal_code]' => $input['postal_code'],
            'billing_details[email]' => $user['email'],
            'billing_details[name]' => $user['name'] . ' ' . $user['name'],
            // 'billing_details[phone]' => $request['phone'],
        ];

        $payment_payload = http_build_query($payment_data);
        $payment_headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . env('STRIPE_SECRET'),
        ];
        // sending curl request
        // see last function for code
        $payment_body = $this->curlPost($payment_url, $payment_payload, $payment_headers);
        $payment_response = json_decode($payment_body, true);

        if (isset($payment_response['id']) && $payment_response['id'] != null) {

            $request_url = self::BASE_URL . '/v1/payment_intents';

            $request_data = [
                'amount' => $request->price * 100, // multiply amount with 100
                'currency' => 'usd',
                'payment_method_types[]' => 'card',
                'payment_method' => $payment_response['id'],
                'confirm' => 'true',
                'capture_method' => 'automatic',
                // 'return_url' => route('api.stripeResponse', $process['transaction_id']),
                'payment_method_options[card][request_three_d_secure]' => 'automatic',
            ];

            $request_payload = http_build_query($request_data);

            $request_headers = [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . env('STRIPE_SECRET'),
            ];

            // another curl request
            $response_body = $this->curlPost($request_url, $request_payload, $request_headers);
            $response_data = json_decode($response_body, true);

            // transaction required 3d secure redirect
            if (isset($response_data['next_action']['redirect_to_url']['url']) && $response_data['next_action']['redirect_to_url']['url'] != null) {
                // dd();
                return response()->json([
                    'redirect' => true,
                    'message' => 'code error',
                    'url' => $response_data['next_action']['redirect_to_url']['url'],
                ]);

                // transaction success without 3d secure redirect
            } elseif (isset($response_data['status']) && $response_data['status'] == 'succeeded') {
                $subtotal = $request->price *  $request->amount;



                $user = Auth::user();

                $transaction = Transaction::create($request->except([
                    'card_number',
                    'expiration_date',
                    'cvv',
                    '_token',
                    'price'
                ]) +
                    [
                        'user_id' => $user->id,
                        'qty' => $request->qty,
                        'subtotal' => $subtotal,
                        'status' => 'success',
                    ]);

         
                return $transaction;


                // transaction declined because of error
            } elseif (isset($response_data['error']['message']) && $response_data['error']['message'] != null) {

                return response()->json([
                    'message' => $response_data['error']['message'],
                ], 500);
            } else {

                return response()->json([
                    'message' => 'Something went wrong, please try again.',
                ], 500);
            }

            // error in creating payment method
        } elseif (isset($payment_response['error']['message']) && $payment_response['error']['message'] != null) {
            throw new \Exception($payment_response['error']['message']);
        }
    }

    /**
     * create curl request
     * we have created seperate method for curl request
     * instead of put code at every request
     *
     * @return Stripe response
     */
    private function curlPost($url, $data, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
   

}
