<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('status', '有料会員登録にはログインが必要です。');
        }

        if ($user->is_paid_member) {
            return redirect()->route('membership')->with('status', 'すでに有料会員です。');
        }

        $secretKey = config('services.stripe.secret');
        $priceId = config('services.stripe.price_id');

        if (! $secretKey || ! $priceId || ! class_exists(StripeClient::class)) {
            return redirect()
                ->route('membership')
                ->with('status', 'Stripeの設定が未完了です。STRIPE_SECRET と STRIPE_PRICE_ID を設定してください。');
        }

        $stripe = new StripeClient($secretKey);
        $session = $stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'customer_email' => $user->email,
            'client_reference_id' => (string) $user->id,
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('membership'),
            'metadata' => [
                'user_id' => (string) $user->id,
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request): View
    {
        return view('payments.success', [
            'sessionId' => $request->query('session_id'),
        ]);
    }

    public function webhook(Request $request): Response
    {
        $secret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (! $secret || ! class_exists(Webhook::class)) {
            Log::warning('Stripe webhook skipped because configuration is incomplete.');

            return response('Webhook configuration missing', 400);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (SignatureVerificationException $exception) {
            Log::warning('Stripe webhook signature verification failed.', ['message' => $exception->getMessage()]);

            return response('Invalid signature', 400);
        } catch (\UnexpectedValueException $exception) {
            Log::warning('Stripe webhook payload is invalid.', ['message' => $exception->getMessage()]);

            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            /** @var Session $session */
            $session = $event->data->object;
            $this->activatePaidMembership($session);
        }

        if (in_array($event->type, ['customer.subscription.deleted', 'invoice.payment_failed'], true)) {
            $this->deactivatePaidMembership($event->data->object->customer ?? null);
        }

        return response('OK');
    }

    private function activatePaidMembership(Session $session): void
    {
        $userId = $session->metadata->user_id ?? $session->client_reference_id ?? null;

        if (! $userId) {
            return;
        }

        User::whereKey($userId)->update([
            'is_paid_member' => true,
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $session->subscription,
            'paid_member_since' => now(),
        ]);
    }

    private function deactivatePaidMembership(?string $stripeCustomerId): void
    {
        if (! $stripeCustomerId) {
            return;
        }

        User::where('stripe_customer_id', $stripeCustomerId)->update([
            'is_paid_member' => false,
        ]);
    }
}
