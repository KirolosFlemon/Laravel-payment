<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Traits\StripPayment;

class TransactionController extends Controller
{
    use StripPayment;

    public function showForm()
    {
        // dd(session()->get('token')->token);
        return view('transaction');
    }

    public function processTransaction(TransactionRequest $request)
    {
        // Implement validation for $request data
        // Process payment using the StripPayment trait
        try {
            $result = $this->stripPayment($request);
            // Check the result and handle accordingly
            if ($result instanceof \App\Models\Transaction) {
                // Payment success
                return view('transaction-result', ['transaction' => $result]);
            } else {
                // Payment error
                return view('transaction-result', ['error' => $result['message']]);
            }
        } catch (\Exception $e) {
            // Handle exceptions

            // return response()->json(['error' => $e->getMessage(),'status'=>'error']);
            return view('transaction-result', ['error' => $e->getMessage()]);
        }
    }
}
