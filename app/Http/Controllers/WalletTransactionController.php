<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                    if (doubleval($wallet->token) < doubleval($request->amount)) {
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
                $walletTransaction = WalletTransaction::create([
                    'user_id' => $user->id,
                    'transaction_type' => $request->transaction_type,
                    'amount' => $request->amount,
                ]);
            }
            $wallet = Wallet::where('user_id', $user->id)->get()->first();
            return response()->json([
                'success' => true,
                'data' => $wallet,
                'message' => 'updated',
            ]);
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

    public function addToken(Request $request)
    {
        if (!$request->has('user_id')) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'User id is required'
            ]);
        }
        $wallet = Wallet::where('user_id', $request->user_id)->latest()->first();
        if (!$wallet) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Didn't find any wallet against the given user id",
            ]);
        }

        $wallet->token = strval(doubleval($wallet->token) + doubleval($request->token));
        $wallet->update();
        $walletTransaction = WalletTransaction::create([
            'user_id' => $request->user_id,
            'transaction_type' => 'token_plus',
            'amount' => $request->token,
        ]);

        return response()->json([
            'success' => true,
            'data' => $wallet,
            'message' => 'Token added Successfully',
        ]);
    }

    public function getLoggedUserTransaction()
    {
        $user = Auth::user();
        $userTransactions = WalletTransaction::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $userTransactions,
            'message' => 'Users All Transactions',
        ]);
    }
}
