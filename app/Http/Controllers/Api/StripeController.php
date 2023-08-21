<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Импортируем модель User

class StripeController extends Controller
{
    public function getSession()
    {
        $stripe = new \Stripe\StripeClient('sk_test_51Ng6pYDtedehY5ENIwiy6IZryUlzSwu4UMuQBXXNp1LVK6qJolEXUT16xQFaogy1wQpwpaKAcGTO0w06BLK2L9g100DGsTAl71');

        $user = auth()->user(); // Получаем текущего пользователя

        $checkout = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost:8080/preferences',
            'cancel_url' => 'http://localhost:8080/dashboard',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => 500,
                        'product_data' => [
                            'name' => "Stripe controller Test",
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'customer_email' => $user->email, // Передаем email пользователя
            'mode' => 'payment',
        ]);

        $sub = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost:8080/preferences',
            'cancel_url' => 'http://localhost:8080/dashboard',
            'line_items' => [
                [
                    'price' => 'price_1NhTtUDtedehY5EN7rLqIEr4',
                    'quantity' => 1,
                ],
            ],
            'customer_email' => $user->email, // Передаем email пользователя
            'mode' => 'subscription',
        ]);

        return ['oneTime' => $checkout, 'sub' => $sub];
    }

    public function webhook(Request $request)
    {
        \Illuminate\Support\Facades\Log::info("webhook");

        //move to .env
        $endpoint_secret = 'whsec_4c275c82fe12738bc37533ac130236751eb3fa7192ada98615741cd165fc62c9';

        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $payload = @file_get_contents('php://input');
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            echo 'Webhook error while validation signature';
            http_response_code(400);
            exit();
        }

        if ($request->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $customerEmail = $session->customer_details->email;

            $user = User::where('email', $customerEmail)->first();
            if ($user) {
                $user->premium_at = now();
                $user->save();
                \Illuminate\Support\Facades\Log::info("Updated user {$user->id} to premium status.");
            }
        }

        return response()->json(["status" => 'success']);
    }
}
