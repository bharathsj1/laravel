<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $transactionType = null;
        if ($request->has('transaction_type')) {
            $transactionType = $request->transaction_type;
            $wallet = Wallet::where('user_id', $user->id)->latest()->first();
            if ($wallet) {
                if ($transactionType == 'credited') {
                    $wallet->balance = strval(doubleval($wallet->balance) + doubleval($request->amount));
                    $wallet->update();
                } else if ($transactionType == 'debited') {

                    if (doubleval($request->amount) > doubleval($wallet->balance)) {
                        return response()->json([
                            'success' => true,
                            'data' => [],
                            'message' => 'Insufficient Balance Available',
                        ]);
                    } else {
                        $wallet->balance = strval(doubleval($wallet->balance) - doubleval($request->amount));
                        $wallet->update();
                    }
                } else if ($transactionType == 'token_plus') {
                    $wallet->token = strval(doubleval($wallet->token) + doubleval($request->amount));
                    $wallet->update();
                } else if ($transactionType == 'token_minus') {
                    if (doubleval($request->token) > doubleval($wallet->token)) {
                        return response()->json([
                            'success' => true,
                            'data' => [],
                            'message' => 'Insufficient Token Available',
                        ]);
                    } else {
                        $wallet->token = strval(doubleval($wallet->token) - doubleval($request->amount));
                        $wallet->update();
                    }
                }
            }
        } else {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Transaction Type Required',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        //
    }
}
