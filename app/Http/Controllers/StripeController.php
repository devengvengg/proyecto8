<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class StripeController extends Controller {
    // Funcionalidades:
    public function index() {
        return view('stripe.index');
    }
    public function checkout() {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Send Money'
                        ],
                        'unit_amount' => 720
                    ],
                    'quantity' => 1
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('index')
        ]);
        return redirect()->away($session->url);
    }
    public function success() {
        return view('stripe.index');
    }
}
