<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentModeRequest;
use App\Http\Requests\UpdatePaymentModeRequest;
use App\Models\PaymentMode;

class PaymentModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentModeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMode $paymentMode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentModeRequest $request, PaymentMode $paymentMode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMode $paymentMode)
    {
        //
    }
}
