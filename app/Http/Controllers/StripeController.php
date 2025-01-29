<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required',
            'nom' => 'required',
            'numero' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvc' => 'required',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = \Stripe\PaymentIntent::create([
                'amount' => 500, // 5 DHS en centimes
                'currency' => 'mad',
                'payment_method_types' => ['card'],
                'description' => 'Paiement de 5 DHS via Stripe',
                'metadata' => [
                    'nom' => $request->nom,
                ],
            ]);

            return response()->json(['message' => 'Paiement effectué avec succès !'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
